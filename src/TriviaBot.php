<?php
/**
 * Created by PhpStorm.
 * User: billythekid
 * Date: 23/04/15
 * Time: 08:41
 */

namespace BTK;

class TriviaBot {

    private $currentSet;
    private $currentQuestion;
    private $currentAnswer;
    private $channel;
    private $icon_emoji;
    private $bot_name;


    public function __construct($bot_name)
    {
        $this->bot_name = $bot_name;
        $this->channel = "";
        $this->icon_emoji = ":grinning:";
        $this->currentSet = -1;
        $this->currentQuestion = "";
        $this->currentAnswer =  "";
    }

    public function start()
    {
        //set the flag in the database to say the game is running
    }

    public function stop()
    {
        //set the flag in the database to say the game is not running
    }

    public function load($question_file)
    {
        //add questions from the given file to the database
    }

    public function unload($set_name)
    {
        //remove questions from the given set name from the database
    }

    /**
     * @param string $message
     *
     * @return string JSON encoded
     */
    public function sendMessageToChannel($message)
    {
        return json_encode(array(
            "text"=>$message,
            "channel"=>$this->getChannel(),
            "username"=>$this->getBotName(),
            "icon_emoji"=> $this->getIconEmoji())
        );
    }

    /**
     * @return mixed
     */
    public function getBotName()
    {
        return $this->bot_name;
    }

    /**
     * @param mixed $bot_name
     */
    public function setBotName($bot_name)
    {
        $this->bot_name = $bot_name;
    }

    /**
     * @return int
     */
    public function getCurrentSet()
    {
        return $this->currentSet;
    }

    /**
     * @param int $currentSet
     */
    public function setCurrentSet($currentSet)
    {
        $this->currentSet = $currentSet;
    }

    /**
     * @return string
     */
    public function getCurrentQuestion()
    {
        return $this->currentQuestion;
    }

    /**
     * @param string $currentQuestion
     */
    public function setCurrentQuestion($currentQuestion)
    {
        $this->currentQuestion = $currentQuestion;
    }

    /**
     * @return string
     */
    public function getCurrentAnswer()
    {
        return $this->currentAnswer;
    }

    /**
     * @param string $currentAnswer
     */
    public function setCurrentAnswer($currentAnswer)
    {
        $this->currentAnswer = $currentAnswer;
    }

    /**
     * @return mixed
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * @param mixed $channel
     */
    public function setChannel($channel)
    {
        $this->channel = $channel;
    }

    /**
     * @return mixed
     */
    public function getIconEmoji()
    {
        return $this->icon_emoji;
    }

    /**
     * @param mixed $icon_emoji
     */
    public function setIconEmoji($icon_emoji)
    {
        $this->icon_emoji = $icon_emoji;
    }

}