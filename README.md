# FondBot

FondBot is a framework for creating chat bots.

## Installation

1. Create new Laravel 5.4 project:

    composer create-project laravel/laravel:^5.4 fondbot && cd fondbot
    
2. Setup application database connection. (MySQL is recommended).    
    
3. Install FondBot package:

    composer require fondbot/fondbot
 
4. Add `FondBot\Providers\ServiceProvider` class to `providers` array in `config/app.php`.
    
    'providers' => [
        ...
        FondBot\Providers\ServiceProvider::class,
        ...
    ],

5. Setup application database connection. (MySQL is recommended).

6. Run FondBot installation:

   php artisan fondbot:install
    
    
