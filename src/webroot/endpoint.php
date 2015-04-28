<?php
/**
 * This is the page that'll be hit when someone types in channel.
 * We deal with all settings of the bot as well as listening for answers.
 * User: billythekid
 */

namespace BTK;
ini_set('display_errors', 1);
require_once('../config.php');

if (!empty($_POST) && (!empty($_POST['token']) && $_POST['token'] == SLACK_OUTGOING_TOKEN && $_POST['user_name'] !== 'slackbot' && $_POST['user_id'] !== "USLACKBOT"))
{
    include_once('../TriviaBot.php');

    $bot = new TriviaBot("Trivia Bot");
    //$player_id = $_POST['user_id'];
    $player_name = $_POST['user_name'];
    $player_text = $_POST['text'];
    $player_channel = $_POST['channel_name'];

    $bot->setChannel($player_channel);


    //check if it's a command for the bot !start !stop !load !delay etc.
    if (trim($player_text)[0] == "!") //commands start with a ! character
    {
        $command = explode(" ", $player_text); //each word is a token for the command
        switch ($command[0])
        {
            case "!load":
                if (empty($command[1]))
                {
                    $bot->setIconEmoji(":interrobang:");
                    die($bot->sendMessageToChannel("You forgot to tell me what file to load, silly!"));
                }
                elseif (empty($command[2] || $command[2] == "false"))
                {
                    $loaded = $bot->load($command[1]);
                    die($bot->sendMessageToChannel($loaded));
                }
                else
                {
                    $loaded = $bot->load($command[1],true);
                    die($bot->sendMessageToChannel($loaded));
                }
                break;
            case "!start":
                //start the bot
                $bot->setIconEmoji(":sunglasses:");
                $bot->start();
                break;
            case "!stop":
                //stop the bot after this question
                $bot->setIconEmoji(":hand:");
                $bot->stop();
                die($bot->sendMessageToChannel("*Game stopped by {$player_name} after this question*"));
                break;
            case "!help":
                //send the help text to the channel
                $helpText = "The options available are...";
                die($bot->sendMessageToChannel($helpText));
                break;
        }
    } else
    {

        //check if a question is active

        //check if the answer is correct

        //add to their current monthly score

        //tell the channel
    }
} else
{
    http_response_code(404);
    die();
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