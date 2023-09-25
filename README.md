Project :- NorthStar

Prerequisite: 
1. Docker need to install with docker-compose
2. Install Git
3. Postman

1. Clone the repository
2. move inside folder
3. rename .evn.example to .env
4. go inside docker folder
5. Run `docker-compose up`
6. Open `http://127.0.0.1:8080/` url and login to phpmyadmin using root:root
7. Create a database named as `northstar`
8. Run `composer install`
9. Run `php artisan migrate`  if there will be issue on db connection so please DB_HOST=mysql to DB_HOST=0.0.0.0 in env for migration only and then revert it.

Now setup is ready and available at http://localhost:8088
