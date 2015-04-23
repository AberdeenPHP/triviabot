<?php
/**
 * Created by PhpStorm.
 * User: billythekid
 * Date: 22/04/15
 * Time: 14:54
 */
include '../vendor/autoload.php';
include 'ourConfig.php'; // .gitignored - simply creates a $config var using the settings below

$config = (!empty($config)) ? $config : [
    'token' => 'USER-API-TOKEN',
    'team' => 'YOUR-TEAM',
    'username' => 'BOT-NAME',
    'icon' => 'ICON', // Auto detects if it's an icon_url or icon_emoji
    'parse' => '', // __construct function in Client.php calls for the parse parameter
];
