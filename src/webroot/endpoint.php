<?php
/**
 * This is the page that'll be hit when someone types in channel.
 * User: billythekid
 * Date: 22/04/15
 * Time: 14:54
 */
namespace BTK;
include __DIR__.'../../../../../../vendor/autoload.php';

use ThreadMeUp\Slack;
use Dotenv;
ini_set('display_errors', 1);

Dotenv::load(__DIR__);

$config = [
    'token' => getenv('CLIENT_TOKEN'),
    'team' => getenv('CLIENT_TEAM'),
    'username' => getenv('CLIENT_USERNAME'),
    'icon' => getenv('CLIENT_ICON'),
    'parse' => '',
];

$slack = new Slack\Client($config);
$params = array('client'=>$slack);
$bot = new TriviaBot($params);
$bot->sendMessageToChannel('#trivia',"testing!");