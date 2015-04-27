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

    public function __construct()
    {
        $this->currentSet = -1;
        $this->currentQuestion = "";
        $this->currentAnswer =  "";

    }

    /**
     * @param string $message
     *
     * @return string JSON encoded
     */
    public function sendMessageToChannel($message)
    {
        return json_encode(array("text"=>$message));
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


}