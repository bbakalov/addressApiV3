# addressApiV3 #
##Environment:##
MySQL   **5.6.32**
PHP     **5.5.9**
Apache  **2.4.7**
##Interface:##
 Method|         URI           |                           Action                                          |
------| ---------------------- | ------------------------------------------------------------------------- |
 GET  | /addresses             | protected/app/Controllers/AddressController.php@getAction@getAllAddresses |
 GET  | /addresses/{addressId} | protected/app/Controllers/AddressController.php@getAction@getAddress      |
 POST | /addresses             | protected/app/Controllers/AddressController.php@postAction@createAddress  |
 POST | /addresses/{addressId} | protected/app/Controllers/AddressController.php@putAction@updateAddress   |
 ----------------------------------------------------------------------------------------------------------
##Important things to do:##
* Create database and set configuration to **protected/app/Model/DbModel.php**
* Create table in database from **db/shcema.sql**

***Result:***
* new table 'address' with one test raw, will be create in DB
