<?php

namespace Config;

use CodeIgniter\Config\AutoloadConfig;

class Autoload extends AutoloadConfig
{
    /**
     * Namespaces for PSR-4 autoloading.
     */
    public $psr4 = [
        'App' => APPPATH,
        // Default application namespace
        'Config'      => APPPATH . 'Config',
        // Additional namespaces can be added here, for example:
        'App\Models'  => APPPATH . 'Models',  // Models directory
        'App\Controllers'  => APPPATH . 'Controllers',  // Models directory
        'App\Libraries' => APPPATH . 'Libraries', // Libraries directory
    ];

    /**
     * Class Map for non-PSR-4 compliant files.
     */
    public $classmap = [];

    /**
     * Helper files to be automatically loaded.
     */
    public $helpers = ['url', 'file', 'custom_helper', 'json_output', 'session'];

    /**
     * Files to include during initialization.
     */
    public $files = [];

    /**
     * Models, Libraries, etc., to preload.
     */
    public $preload = [
        'database', // Enables database access
        'session', // Enables session management
        'form_validation', // Enables form validation
    ];

    /**
     * This method is for preloading models that you want to use in controllers.
     */
    public $models = [
        'App\Models\LoginModel',
        'App\Models\MyModel',
        'App\Models\ApiModel',
        'App\Models\CronJobModel',
    ];
}
