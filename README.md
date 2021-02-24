APP Setup
========================

- Run 
```composer install``` to install all dependencies
- Run these commands **only if you don't have** sqlite and sqlite driver installed
  
    ```sudo apt-get install sqlite3 libsqlite3-dev```
    ```sudo apt-get install php-sqlite3```
- Run  ```php bin/console server:run``` to start the server
- You can access the app from http://127.0.0.1:8000/

How To Run The Tests?
--------------

- Create data directory inside the var folder to save the test database there
- Run this command to install the test db schema
  
  ```php bin/console doctrine:schema:create --env=test```

- Run this command to see run the tests
  
  ```./vendor/bin/simple-phpunit```

Points To Improve
--------------
- Cover the file uploader service with tests
- Cover the delete functionality with tests
- Cover the file upload during Add or edit functionality wit test 
- Add a static analysis tool like phpcsfixer as git hook to have a consistent code
