<?php

/*
|--------------------------------------------------------------------------
| URL
|--------------------------------------------------------------------------
*/

if (!defined('BASE_URL')) {
    define(
        'BASE_URL',
        '/MiniShop_quachvanduy/'
    );
}

if (!defined('PRODUCT_IMAGE_URL')) {
    define(
        'PRODUCT_IMAGE_URL',
        BASE_URL . 'uploads/products/'
    );
}


/*
|--------------------------------------------------------------------------
| CART
|--------------------------------------------------------------------------
*/

if (!defined('CART_SESSION_KEY')) {
    define(
        'CART_SESSION_KEY',
        'cart'
    );
}


/*
|--------------------------------------------------------------------------
| AUTOLOAD
|--------------------------------------------------------------------------
*/

spl_autoload_register(function ($className) {

    $prefixes = [

        'Controllers\\' => __DIR__ . '/controllers/',
        'DAO\\'         => __DIR__ . '/dao/',
        'Models\\'      => __DIR__ . '/models/',
        'Middleware\\'  => __DIR__ . '/middleware/',
        'Config\\'      => __DIR__ . '/config/',
        'Composers\\'   => __DIR__ . '/composers/',

    ];

    foreach ($prefixes as $prefix => $baseDir) {

        if (str_starts_with($className, $prefix)) {

            $relativeClass = substr(
                $className,
                strlen($prefix)
            );

            $file = $baseDir
                . str_replace('\\', '/', $relativeClass)
                . '.php';

            if (file_exists($file)) {

                require_once $file;
            }

            return;
        }
    }
});