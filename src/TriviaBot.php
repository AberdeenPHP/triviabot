<?php
/**
 * Created by PhpStorm.
 * User: billythekid
 * Date: 23/04/15
 * Time: 08:41
 */

namespace BTK;

use ThreadMeUp\Slack;

class TriviaBot {

    private $currentSet;
    private $currentQuestion;
    private $currentAnswer;
    private $client;

    public function __construct($params)
    {
        $this->currentSet = (!empty($params['currentSet'])) ? $params['currentSet'] : -1;
        $this->currentQuestion = (!empty($params['currentQuestion'])) ? $params['currentQuestion'] : "";
        $this->currentAnswer = "";
    }

    /**
     * @param string $channel
     * @param string $message
     *
     * @return void
     */
    public function sendMessageToChannel($channel, $message)
    {
        include "config.php";
        $this->setClient(new Slack\Client($config));
        $chat = $this->client->chat($channel);
        $chat->send($message);
    }


    /**
     * @return int
     */
    public function getCurrentSet()
    {
        return $this->currentSet;
    }

    /**
     * @param mixed $client
     */
    public function setClient($client)
    {
        $this->client = $client;
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