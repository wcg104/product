
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
php artisan vendor:publish --tag=product-category --force
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
Route::apiResource('/upload-photo', UploadPhotoController::class);
```


Information
-----
This new version of package will provide you further upgradation of the Ecommerce project . It contains Products , product_item , product_item_sizes, product_media.
these tables are connected with the ids which means if the user will add 1 product it will be have atleast 1 or more than 1 item and 1 item have multiple sizes and multiple media whether it maybe video or image . Everything is managed by the ids. in older version everything was managed differently but in the table is changed and the data is also changed . 

 product table contain the data and type 

 | name | datatype | 
| --------------- | --------------- | 
| name | varchar |
| category_id | uuid | 
| short_description | varchar(150) | 
| brand | varchar(50)  | 
| is_active | boolean  | 
| product_type | varchar(100)  | 

 product_item table contain the data and type 

 | name | datatype | 
| --------------- | --------------- | 
| product_id | uuid |
| color | varchar | 
| price | integer | 
| final_price | float  | 
| is_available |  boolean  | 
| tags | varchar | 
| ordering | integer  | 
| quantity | integer  | 

 product_item_sizes table contain the data and type 

 | name | datatype | 
| --------------- | --------------- | 
| product_item_id | uuid |
| itemname | varchar | 
| itemquantity | varchar | 

product_item_media table contain the data and type 

 | name | datatype | 
| --------------- | --------------- | 
| product_item_id | uuid |
| name | varchar | 
| image | varchar | 
| ordering | integer | 
| path | varchar | 
| type | enum('video','image') | 

there is another api which is going to upload the image and then it will add the image in the temp folder while adding a product user needs to just add the name of the image created at the temp folder in public so while storing the product the image will be tranfer from temp folder to public_media folder.

License
-----
This package is licensed under the `MIT` License. Please see the [License File][(https://github.com/wcg104/lead/blob/master/LICENSE)](https://github.com/wcg104/product/blob/master/LICENSE)https://github.com/wcg104/product/blob/master/LICENSE for more details.
