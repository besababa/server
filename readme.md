## BESABABA

###### 1) Clone repository.
###### 2) Run composer install.
###### 3) Add database name in .env besababa.
###### 4) run sudo vim vendor/zizaco/entrust/src/commands/MigrationCommand.php 
######    and change 'fire' function name to 'handle'.
###### 5) php artisan entrust:migration.
###### 6) Run php artisan migrate.
