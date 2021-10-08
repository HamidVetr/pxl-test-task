### How to run

1) Copy `.env.example` to `.env` and change the config for db connection:
   ```.dotenv
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=
    DB_USERNAME=
    DB_PASSWORD=
   ```
2) Run "composer install"
3) Run "php artisan key:generate"
4) Run "php artisan migrate:fresh --seed"
5) Create a folder in `storage/app` directory called `data` and put `challenge.json` file inside this folder. The end result should be like this:
   ```
   storage/app/data/challenge.json
   ```
6) Run "php artisan schedule:run" to start data mapping
7) The process then can be resumed by doing the 3rd step again
