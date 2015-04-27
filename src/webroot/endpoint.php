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
}
else
{
    // just fail, they're being a tool
    http_response_code(404);
}






unset($bot);//just to get rid of the unused var warning in phpstorm!


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