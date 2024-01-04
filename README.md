Laravel CRUD operation V1
======



### Installation
-----

Run a command,

```
composer require wcg104/product v1.1
```
### To publish configurations,

```
php artisan vendor:publish --tag=product-category --force
```



#### Usage
-----
To use CRUD API operation for Product Category module Run below command.

### Run Migration
```
php artisan migrate
```
### Add resource route in route file
```php
// To use api resource add this in route and change name of product and category according to your requirement

Route::apiResource('/product', ProductController::class);
Route::apiResource('/category', CategoryController::class);
Route::apiResource('/variation', VariationController::class);

```


### Information
-----
This is the version of Product Category and its variation where product , Category and its Variant will be added to table - products , categories , and variants.
where category has variant id which will help to identify which category has which variant . 1 category may have multiple variant and multiple variant has 1 category . Now in Product Category id is added to get the refernce of the category which product have which category.

 product table contain the data and type 

 | name | datatype | 
| --------------- | --------------- | 
| id | uuid |
| name | varchar |
| price | integer | 
| short_description | varchar(150) | 
| long_description | varchar(250) | 
| discounted_price | double int  | 
| in_stock | boolean  | 
| is_active | boolean  | 
| brand | varchar  | 
| main_category | uuid  | 
| cover_image | varchar  | 
| images | varchar  | 
| value | integer  | 
| variant | uuid  | 
| parent_product | integer  | 
| slug | varchar  | 


 Category table contain the data and type 

 | name | datatype | 
| --------------- | --------------- | 
| id | uuid |
| title | varchar |
| description | varchar | 
| parent_category | uuid | 
| slug | varchar  | 

 variation table contain the data and type 

 | name | datatype | 
| --------------- | --------------- |
| id | uuid |
| category_id | uuid |
| title | varchar |
| type | varchar | 
| prefix | varchar | 
| countable | boolean | 
| value | integer | 

-----
This package is licensed under the `MIT` License. Please see the [License File](https://github.com/wcg104/product/blob/master/LICENSE)for more details