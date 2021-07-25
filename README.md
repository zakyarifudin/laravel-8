## Laravel 8

Cara install di punya kalian sendiri ,,, moon maaf belum buat dockernya

1. php artisan key:generate
2. php artisan migrate:fresh // create table
3. php artisan db:seed // data dump
4. php artisan passport:install --force // create key oauth

Ini Ngapain ? Buat Boilerplate :

1. Auth : user_id di table oauth_access_token diganti varchar 36 agar bisa insert uuid dari id_user table users
2. CRUD
