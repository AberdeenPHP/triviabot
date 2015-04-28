<?php

require_once '../../../../autoload.php';
ActiveRecord\Config::initialize(function ($cfg)
{
    $cfg->set_model_directory(__DIR__ . '/models');
    $cfg->set_connections(array(
        'development' => 'mysql://' . DB_USER . ':' . DB_PASS . '@' . DB_HOST . '/' . DB_NAME
    ));
});