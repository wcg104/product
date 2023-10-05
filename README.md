
Laravel CRUD operation 
======




Installation
-----

Run a command,

```
composer require wcg104/product
```
To publish configurations,

```
php artisan vendor:publish --tag=product-category
```


Usage
-----
To use CRUD API operation for lead module Run below command.

Run Migration
```
php artisan migrate
```
Add resource route in route file
```php
// To use api resource add this in route and change name of lead according to your requirement

Route::apiResource('/product', ProductController::class);
Route::apiResource('/category', CategoryController::class);
Route::apiResource('/variation', VariationController::class);
```

License
-----
This package is licensed under the `MIT` License. Please see the [License File][(https://github.com/wcg104/lead/blob/master/LICENSE)](https://github.com/wcg104/product/blob/master/LICENSE)https://github.com/wcg104/product/blob/master/LICENSE for more details.
