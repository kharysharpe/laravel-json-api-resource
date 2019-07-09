<?php

namespace Kharysharpe\LaravelJsonApiResource\Http\Resource\JsonApi;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection as JsonResourceCollection;
use Illuminate\Pagination\AbstractPaginator;

class ResourceCollection extends JsonResourceCollection
{
    protected $resourceItemClass = null;
    protected $paginate = false;

    public function toArray($request)
    {
        if ($this->resource instanceof AbstractPaginator) {
            $this->paginate = true;
        }

        $class = $this->resolveResourceItemClass();

        $data = [
            'data' => $class::collection($this->collection),
        ];

        return $data;
    }

    public function with($request)
    {
        $with = [];

        $with['included'] = $this->when($this->hasIncludes(), $this->getIncudedResources());

        if (isset($this->route)) {
            if ($this->paginate) {
                $paginator = $this->getPagination();
                $with['links'] = $paginator['links'];
                $with['meta'] = $paginator['meta'];
            } else {
                $with['links'] = [
                    'self' => $this->getSelfLink()
                ];
            }
        }

        return $with;
    }

    public function resolveResourceItemClass()
    {
        if ($this->resourceItemClass) {
            return $this->resourceItemClass;
        }

        //Let's guess
        $class = get_class($this);

        $class = substr_replace($class, 'Item', -10);

        if (class_exists($class)) {
            return $class;
        }

        throw(new \Exception("ResourceItemClass not set, tried $class but didn't find it. Did you name it correctly?"));
    }

    public function include(array $relations)
    {
        $this->includes = $relations;

        return $this;
    }

    public function hasIncludes()
    {
        return (isset($this->includes) && !empty($this->includes));
    }

    public function getIncudedResources()
    {
        $result = [];

        $this->loadResourceRelationships();

        foreach ($this->resources as $resource) {
            $result[] = $resource;
        }

        return $result;
    }

    public function loadResourceRelationships()
    {
        $resources = [];
        $models = [];
        $items = [];

        foreach ($this->collection as $item) {
            $item->load(array_keys($this->related));
            $items[] = $item->getRelations();
        }

        foreach ($items as $models) {
            foreach ($models as $model) {
                if ($model == null) {
                    continue;
                }

                $relationName = $model->getTable();

                $class = $this->related[$relationName];

                $resources[$model->id] = new $class($model);
            }
        }

        return $this->resources = $resources;
    }

    public function getSelfLink()
    {
        $routeParameters = [];

        $parameters = $this->getRouteParameters();

        foreach ($parameters as $resourceName) {
            // Register self
            if ($resourceName == $this->resolveResourceName()) {
                $routeParameters[$resourceName] = $this->id;
                continue;
            }

            if (isset($this->resources[$resourceName])) {
                $routeParameters[$resourceName] = $this->resources[$resourceName]->id;
            }
        }

        return route($this->route, $routeParameters);
    }

    public function getRouteParameters()
    {
        $parameters = [];

        $router = app('Illuminate\Routing\Router');

        $routes = $router->getRoutes();

        if ($routes->hasNamedRoute($this->route)) {
            $parameters = $routes->getByName($this->route)->parameterNames();
        }

        return $parameters;
    }

    public function getPagination()
    {
        return [
            'links' => [
                'self' => $this->resource->url($this->resource->currentPage()),
                'first' => $this->resource->url(1),
                'prev' => $this->resource->previousPageUrl(),
                'next' => $this->resource->nextPageUrl(),
                'last' => $this->url($this->lastPage()),
            ],
            'meta' => [
                'current_page' => $this->resource->currentPage(),
                'from' => $this->resource->firstItem(),
                'last_page' => $this->resource->lastPage(),
                'path' => $this->getSelfLink(),
                'per_page' => $this->resource->perPage(),
                'to' => $this->resource->lastItem(),
                'total' => $this->resource->total(),
            ]
        ];
    }

    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function toResponse($request)
    {
        return JsonResource::toResponse($request);
    }
}
