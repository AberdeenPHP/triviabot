<?php
/**
 * Created by PhpStorm.
 * User: billythekid
 * Date: 23/04/15
 * Time: 08:41
 */

namespace BTK;

/**
 * Class TriviaBot
 * @package BTK
 */
class TriviaBot
{

    private $channel;
    private $icon_emoji;
    private $bot_name;

    /**
     * @param $bot_name
     */
    public function __construct($bot_name)
    {
        $this->bot_name = $bot_name;
        $this->channel = "";
        $this->icon_emoji = ":grinning:";
    }

    /**
     *
     */
    public function start()
    {
        $game = \Game::first();
        if (empty($game))
        {
            $game = \Game::create(["started"=>0,"stopping"=>0,"delay"=>20]);
        }
        //set all questions to OFF
        while (!empty($this->getCurrentQuestion()))
        {
            $on = $this->getCurrentQuestion();
            $on->current_hint = 0;
            $on->save();
        }
        // \Question::update_all(array('set' => 'current_hint = 0'));
        //set a random question to ON
        $question = \Question::find('first',array("order"=>"RAND()"));
        $question->current_hint = 1;
        $question->save();

        $game->started = 1;
        $game->save();
    }

    /**
     *
     */
    public function stop()
    {
        //set the flag in the database to say the game is not running
        $game = \Game::first();
        $game->stopping = 1;
        $game->save();
    }

    public function getCurrentQuestion()
    {
        return \Question::find('first',array('conditions' => 'current_hint > 0'));
    }


    /**
     * @param bool $question_file
     * @param bool $force
     * @return string
     */
    public function load($question_file,$force = false)
    {
        $response = "";
        if (!$this->is_loaded($question_file) || $force)
        {
            //add questions from the given file to the database
            $file = __DIR__ . '/questions/' . $question_file;
            if (file_exists($file))
            {
                $questions = file($file, FILE_IGNORE_NEW_LINES);

                $title = ltrim($questions[0], "# ");
                $question_set = \Question_set::create(["filename" => $question_file, "title" => $title]);

                foreach ($questions as $question)
                {
                    $question = trim($question);
                    //ignore comment lines in the file
                    if ($question[0] != "#")
                    {
                        //split into token parts
                        $split = explode('|', $question);
                        //first item is the question
                        $q = trim(array_shift($split));
                        if (!empty($q) && !empty($split))
                        {
                            /*
                                @TODO Make this more efficient!
                                @TODO Try array_diff for all questions then in_array this question maybe?
                            */
                            if (true || !$this->check_question_exists($q)) //this is mental, just accept the dupes!
                            {
                                $a = serialize($split);
                                //php-activerecord automatically escapes right?
                                \Question::create([
                                    'set'=> $question_set->id,
                                    'question' => $q,
                                    'answer' => $a
                                ]);
                            }
                        }
                    }
                }
                $total_questions = $this->get_total_questions();
                $response .= "Questions from *{$title}* loaded! There are *{$total_questions}* in the database.";
            }
            else
            {
                $response .= "No question file found";
            }
        }
        else
        {
            $set = $this->get_question_set_by_filename($question_file);
            $response .= "The *{$set->title}* set is already loaded!";
        }
        return $response;
    }

    /**
     * @return mixed
     */
    public function get_total_questions()
    {
        return \Question::count();
    }

    /**
     * @param $question
     * @return bool
     */
    private function check_question_exists($question)
    {
        $q = \Question::find_by_question($question);
        return (!empty($q));
    }

    /**
     * @param $filename
     * @return mixed
     */
    private function get_question_set_by_filename($filename)
    {
        $set = \Question_set::find_by_filename($filename);
        return $set;
    }

    /**
     * @param $set_name
     */
    public function unload($set_name)
    {
        //remove questions from the given set name from the database
    }


    /**
     * @param $question_file
     * @return bool
     */
    private function is_loaded($question_file)
    {
        $set = \Question_set::find_by_filename($question_file);
        return (!empty($set));
    }

    /**
     * @param string $message
     *
     * @return string JSON encoded
     */
    public function sendMessageToChannel($message)
    {
        return json_encode(array(
                "text" => $message,
                "channel" => $this->getChannel(),
                "username" => $this->getBotName(),
                "icon_emoji" => $this->getIconEmoji())
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

    public function started()
    {
        $game = \Game::first();
        return ($game->started == 1);
    }
}