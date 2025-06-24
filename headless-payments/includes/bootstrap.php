<?php

/**
 * This files acts as an autoloader
 */

spl_autoload_register(function ($class) {
    if (strpos($class, 'HP\\') === 0) {
        $path = __DIR__ . '/../src/' . str_replace('\\', '/', substr($class, 3)) . '.php';
        if (file_exists($path)) {
            require_once $path;
        }
    }
});

add_action('plugins_loaded', function () {
    $plugin = new \HP\Plugin();
    $plugin->run();
});
