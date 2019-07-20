# Overview

[![Latest Version on Packagist](https://img.shields.io/packagist/v/kharysharpe/laravel-json-api-resource.svg?style=flat-square)](https://packagist.org/packages/kharysharpe/laravel-json-api-resource)
[![Build Status](https://img.shields.io/travis/kharysharpe/laravel-json-api-resource/master.svg?style=flat-square)](https://travis-ci.org/kharysharpe/laravel-json-api-resource)
[![Quality Score](https://img.shields.io/scrutinizer/g/kharysharpe/laravel-json-api-resource.svg?style=flat-square)](https://scrutinizer-ci.com/g/kharysharpe/laravel-json-api-resource)
[![Total Downloads](https://img.shields.io/packagist/dt/kharysharpe/laravel-json-api-resource.svg?style=flat-square)](https://packagist.org/packages/kharysharpe/laravel-json-api-resource)

This is potentially a drop in replacement for Laravel API Resource that produces JSON API response as per specification. (https://jsonapi.org/).

\* Needs refactoring and tests, pull requests welcomed.

## Installation

You can install the package via composer:

```bash
composer require kharysharpe/laravel-json-api-resource
```

## Usage

Pre-made example
https://github.com/kharysharpe/laravel-json-api-resource-example

From scratch

```
laravel new json-server
cd json-server
composer require kharysharpe/laravel-json-api-resource
```

routes/api.php

```php
<?php

Route::get('/users', 'UserController@index');
Route::get('/users/{id}', 'UserController@show');
```

app/Http/Controllers/UserController.php

```php
<?php

namespace App\Http\Controllers;

use App\User;

use Kharysharpe\LaravelJsonApiResource\Http\Resource\JsonApi\Resource;
use Kharysharpe\LaravelJsonApiResource\Http\Resource\JsonApi\ResourceCollection;

class UserController extends Controller
{
    public function index()
    {
        $user = User::all();

        return new ResourceCollection($user);
    }

    public function show($id)
    {
        $user = User::find($id);

        return new Resource($user);
    }
}
```

# OR

```php
<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Resource\UserCollection;
use App\Http\Resource\UserResource;

class UserController extends Controller
{
    public function index()
    {
        $user = User::all();

        return new UserCollection($user);
    }

    public function show($id)
    {
        $user = User::find($id);

        return new UserResource($user);
    }
}
```

app/Http/Resource/UserResource.php

```php
<?php

namespace App\Http\Resource;

use Kharysharpe\LaravelJsonApiResource\Http\Resource\JsonApi\Resource;

class UserResource extends Resource
{
    //
}
```

app/Http/Resource/UserCollection.php

```php
<?php

namespace App\Http\Resource;

use Kharysharpe\LaravelJsonApiResource\Http\Resource\JsonApi\ResourceCollection;

class UserCollection extends ResourceCollection
{

}
```

Prepare the database (Don't forget to add data to the user table)

```
php artisan migrate
```

Start your laravel application

```
php artisan serve
```

Visit

- http://127.0.0.1:8000/api/users
- http://127.0.0.1:8000/api/users/1

### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email kharysharpe@gmail.com instead of using the issue tracker.

### TODO

- Add credits and/or references
- Look at using https://github.com/spatie/laravel-json-api-paginate to fix pagination

## Credits

- [Khary Sharpe](https://github.com/kharysharpe)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
