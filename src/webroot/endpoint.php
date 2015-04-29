<?php
/**
 * This is the page that'll be hit when someone types in channel.
 * We deal with all settings of the bot as well as listening for answers.
 * User: billythekid
 */

namespace BTK;
ini_set('display_errors', 1);
require_once('../config.php');
include_once('../db.php');

if (!empty($_POST) && (!empty($_POST['token']) && $_POST['token'] == SLACK_OUTGOING_TOKEN && $_POST['user_name'] !== 'slackbot' && $_POST['user_id'] !== "USLACKBOT"))
{
    include_once('../TriviaBot.php');

    $bot = new TriviaBot("Trivia Bot");
    $player_id = $_POST['user_id'];
    $player_name = $_POST['user_name'];
    $player_text = $_POST['text'];
    $player_channel = $_POST['channel_name'];
    $timestamp = time();
    $player = \Player::find("first",["slack_id"=>$player_id]);
    if (empty($player))
    {
        $player = \Player::create([
            "slack_id"=>$player_id
        ]);
    }
    $player->name = $player_name;
    $player->last_seen = $timestamp;
    $player->save();

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
                elseif (empty($command[2]) || $command[2] == "false")
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
                if (!$bot->started())
                {
                    $bot->setIconEmoji(":sunglasses:");
                    $bot->start();
                    die($bot->sendMessageToChannel("Thanks {$player_name}, I was getting bored! More trivia coming up!"));
                }
                else
                {
                    $bot->setIconEmoji(":stuck_out_tongue_winking_eye:");
                    die($bot->sendMessageToChannel("Pay attention {$player_name}, we're already playing trivia!"));
                }
                break;
            case "!stop":
                if (!$bot->started())
                {
                    $bot->setIconEmoji(":stuck_out_tongue_winking_eye:");
                    die($bot->sendMessageToChannel("We're not even playing trivia {$player_name}! (Type *!start* if you want to play)"));
                }
                else
                {
                    //stop the bot after this question
                    $bot->setIconEmoji(":hand:");
                    $bot->stop();
                    die($bot->sendMessageToChannel("*Game stopped by {$player_name} after this question*"));
                }
                break;
            case "!questions":
                $total = $bot->get_total_questions();
                die($bot->sendMessageToChannel("*{$player_name}*: there are *{$total}* questions loaded in the database."));
                break;
            case "!seen":
                if (empty($command[1]))
                {
                    $bot->setIconEmoji(":interrobang:");
                    die($bot->sendMessageToChannel("You forgot to tell me who you're looking for!"));
                }
                else
                {

                }
                break;
            case "!help":
                //send the help text to the channel
                $helpText = "The options available are...\n";
                $helpText .= "*!start / !stop* - starts or stops the game.\n";
                $helpText .= "*!questions* - shows how many questions are loaded";
                die($bot->sendMessageToChannel($helpText));
                break;

        }
    }
    else
    {
        if ($bot->started())
        {
            //check if the answer is correct
            $question = $bot->getCurrentQuestion();
            $answers = unserialize($question->answer);
            $win = false;
            $cheat = "";
            foreach ($answers as $answer)
            {
                $lowanswer = strtolower($answer);
                $lowguess = strtolower($player_text);
                if (strcasecmp($lowanswer, $lowguess) == 0)
                {
                    $win = true;
                }
                $cheat .= strcasecmp($lowanswer, $lowguess);

            }
            if ($win)
            {
                //this player's right!!
                $score = 30 / $question->current_hint;
                $player->current_score += $score;
                if ($player->current_score > $player->high_score)
                {
                    $player->high_score = $player->current_score;
                }
                $player->current_run++;
                if ($player->current_run > $player->best_run)
                {
                    $player->best_run = $player->current_run;
                }
                $player->save();

                $message = "YES! *{$player_name}* got the answer for {$score} points! _{$player_text}_\n";
                $message .= "Next question coming up...";
                $bot->setIconEmoji(":clap:");
                $bot->start();
                die($bot->sendMessageToChannel($message));
            }
            else
            {
                die($bot->sendMessageToChannel($cheat));

            }
        }
    }
}
else
{
    http_response_code(200);
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