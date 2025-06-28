<?php

/**
 * This file instantiates the Plugin class
 * 
 * The Plugin class is the base class responsible for initializing the plugin's functionality
 * 
 */

// Loads the base plugin class
add_action('plugins_loaded', function () {
    $plugin = new \HP\Plugin();
    $plugin->run();
});
