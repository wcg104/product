
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
To use CRUD API operation for Product Category module Run below command.

Run Migration
```
php artisan migrate
```
Add resource route in route file
```php
// To use api resource add this in route and change name of product and category according to your requirement

Route::apiResource('/product', ProductController::class);
Route::apiResource('/category', CategoryController::class);
Route::apiResource('/variation', VariationController::class);
```


Information
-----
This package is about the CRUD operations for the product , category and its variations . The category will have there own variation which will be directly applicable to the product while choseing the category. Each and every product is distinguish with the slug which is auto generated at the time of creating the product. slug is created with the product name and the product id . 

License
-----
This package is licensed under the `MIT` License. Please see the [License File][(https://github.com/wcg104/lead/blob/master/LICENSE)](https://github.com/wcg104/product/blob/master/LICENSE)https://github.com/wcg104/product/blob/master/LICENSE for more details.
