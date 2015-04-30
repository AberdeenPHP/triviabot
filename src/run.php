<?php
/**
 * This runs the bot on a timer (CRON JOB)
 * User: billythekid
 */
namespace BTK;
ini_set('display_errors', 1);
include_once(__DIR__.'/config.php');
include_once(__DIR__.'/TriviaBot.php');
include_once(__DIR__.'/db.php');
$game = \Game::first();
$timestamp = time();
if (($game->last_asked + $game->delay) <= $timestamp && $game->started == 1)
{
    $game->last_asked = $timestamp;
    $game->save();
    $bot = new TriviaBot("Trivia Bot");

    //check if there's a question being asked
    $question = $bot->getCurrentQuestion();
    if (!empty($question))
    {
        $questiontext = $question->question;
        $hint = "*Hint {$question->current_hint}*: "; //this will hold our hint
        //get the (first) possible answer
        $answer = unserialize($question->answer)[0];
        $letters = str_split($answer);
        $previousLetter = " ";// the letter after a space are always shown pretend we're starting with one

        //show first letter of each word
        if ($question->current_hint == 1)
        {
            foreach ($letters as $letter)
            {
                if ($previousLetter == " " || $letter == " " || stripos("abcdefghijklmnopqrstuvwxyz", $letter) === false)
                {
                    $hint .= $letter;
                } else
                {
                    $hint .= "*";
                }
                $previousLetter = $letter;
            }
        } //show first 3 letters of first word
        elseif ($question->current_hint == 2)
        {
            foreach ($letters as $key => $letter)
            {
                if ($previousLetter == " " || $letter == " " || stripos("abcdefghijklmnopqrstuvwxyz", $letter) === false || $key <= 2)
                {
                    $hint .= $letter;
                } else
                {
                    $hint .= "*";
                }
                $previousLetter = $letter;
            }
        } //show all vowels
        elseif ($question->current_hint == 3)
        {
            foreach ($letters as $key => $letter)
            {
                if ($previousLetter == " " || $letter == " " || stripos("abcdefghijklmnopqrstuvwxyz", $letter) === false || $key <= 2 || stripos("aeiou", $letter) > -1)
                {
                    $hint .= $letter;
                } else
                {
                    $hint .= "*";
                }
                $previousLetter = $letter;
            }
        } else //by this time we just want to see the answer - no more clues!
        {
            $questiontext = "";
            $hint = "*Nobody got it!* The answer was _{$answer}_";

            $question->current_hint = -1; // this gets incremented by 1 (to 0 - off) after these conditionals
            $game->questions_without_reply++;
            if ($game->questions_without_reply == 10)
            {
                $game->stopping = 1;
                $hint .= "\nNobody appears to be playing!";
            }
            if ($game->stopping == 1)
            {
                $game->started = 0;
                $game->stopping = 0;
                $game->save();
                $hint .= "\n *GAME STOPPED*";
            } else
            {
                $hint .= "\nNext question coming up...";
                //set up the next question
                $bot->start(); //this sets a random question's current_hint to 1
            }
        }
        $question->current_hint = $question->current_hint + 1;
        $question->save();

        //send the question and hint/answer to channel

        $message = "{$questiontext}\n{$hint}";
        echo "Messsage: $message";
        $url = SLACK_INCOMING_WEBHOOK_URL;
        $data = ['payload'=>$bot->sendMessageToChannel($message)];

        // use key 'http' even if you send the request to https://...
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data),
            ),
        );
        $context  = stream_context_create($options);
        file_get_contents($url, false, $context);
    }
    else {
        $bot->start();
    }
}