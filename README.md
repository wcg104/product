
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
Route::apiResource('/upload-photo', UploadPhotoController::class);
Route::put('/product/{id}/order',[ProductController::class,'updateOrder']); 

```


Information
-----
This new version of package will provide you further upgradation of the Ecommerce project . It contains Products , product_item , product_item_sizes, product_media.
these tables are connected with the ids which means if the  1 product is added then it is mandatory to add  atleast 1 or more than 1 item and 1 item have multiple sizes and multiple media whether it maybe video or image . Everything is managed by the ids. in older version everything was managed differently but in the table is changed and the data is also changed . 

An update of ordering of the product item is also added to the feature where  another api with the route /product/{product id}/order so that the orders of the items can be re-order according to the need. 


table contain the data and type 

 | name | datatype | 
| --------------- | --------------- | 
| id | uuid |
| name | varchar |
| category_id | uuid | 
| short_description | varchar(150) | 
| brand | varchar(50)  | 
| is_active | boolean  | 
| product_type | varchar(100)  | 

 product_item table contain the data and type 

 | name | datatype | 
| --------------- | --------------- | 
| id | uuid |
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
| id | uuid |
| product_item_id | uuid |
| itemname | varchar | 
| itemquantity | varchar | 

product_item_media table contain the data and type 

 | name | datatype | 
| --------------- | --------------- | 
| id | uuid |
| product_item_id | uuid |
| name | varchar | 
| image | varchar | 
| ordering | integer | 
| path | varchar | 
| type | enum('video','image') | 

there is another api which is going to upload the image and then it will add the image in the temp folder while adding a product add the name of the image created at the temp folder in public so while storing the product the image will be tranfer from temp folder to public_media folder.

json raw data example,

```
 {
    "name": "sparx" ,
    "category_id": "1212",
    "brand": "Zudio",
    "is_active":1,
    "product_type": "clothes",
    "short_description": "clothing and accesories",
    "product_item": [
                   {
                       
                       "color":"Green",  
                       "tags": "long tunic",
                       "price": "10",
                       "quantity":"10",
                       "final_price" : "200",
                       "is_available": "1",
                       "product_item_size":[
                           {
                             
                               "itemname":"sm",
                               "itemquantity":"50"  
                           },
                            {
                              
                               "itemname":"xl",
                               "itemquantity":"10"
                           }
                       ],
                        "image" : [ "51697622499.jpg"]  
                   },
                   {
                       
                        "color":"olive green",
                       "tags": "short tunic",
                       "price": "10",
                       "final_price" : "200",
                         "quantity":"20",
                       "is_available": "1",
                     "product_item_size":[
                           {
                               "itemname":"s",
                               "itemquantity":"50"
                           },
                            {
                               "itemname":"m",
                               "itemquantity":"10"
                           }
                       ],
                       "image"  : ["41697622499.jpg"]
                      
                   }
                  
               ]
}
```


License
-----
This package is licensed under the `MIT` License. Please see the [License File][(https://github.com/wcg104/product/blob/master/LICENSE)](https://github.com/wcg104/product/blob/master/LICENSE)https://github.com/wcg104/product/blob/master/LICENSE for more details.
