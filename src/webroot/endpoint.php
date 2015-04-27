<?php
/**
 * This is the page that'll be hit when someone types in channel.
 * User: billythekid
 * Date: 22/04/15
 * Time: 14:54
 */
namespace BTK;
ini_set('display_errors', 1);
require_once('../config.php');

if (!empty($_POST) && (!empty($_POST['token']) && $_POST['token'] === SLACK_TOKEN))
{
    require_once('../TriviaBot.php');
    $bot = new TriviaBot();
    require_once('../db.php');
/*
    //check if it's a command for the bot !start !stop !load etc.

    //otherwise...

    //check if a question is active

    //check if the answer is correct

    //grab the player
    $player_id = $_POST['user_id'];
    $player_name = $_POST['user_name'];

    //add to their current monthly score

    //tell the channel
    echo $bot->sendMessageToChannel("");
*/
    $player_name = $_POST['user_name'];
    $player_answer = trim($_POST['text']);
    $player_channel = $_POST['channel_name'];
    echo $bot->sendMessageToChannel("I just saw {$player_name} say {$player_answer} in {$player_channel}!");

    unset($bot);//just to get rid of the unused var warning in phpstorm!

}
else
{
    // just fail, they're being a tool
    http_response_code(404);
}








/* Example POSTed data from channel
token=FWfMETEsdOxzYZPjm76PhJiL
team_id=T0001
team_domain=example
channel_id=C2147483705
channel_name=test
timestamp=1355517523.000005
user_id=U2147483697
user_name=Steve
text=googlebot: What is the air-speed velocity of an unladen swallow?
trigger_word=googlebot:
*/