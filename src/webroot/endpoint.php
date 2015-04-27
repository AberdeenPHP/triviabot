<?php
/**
 * This is the page that'll be hit when someone types in channel.
 * User: billythekid
 * Date: 22/04/15
 * Time: 14:54
 */
namespace BTK;
ini_set('display_errors', 1);
require('../TriviaBot.php');
require('../db.php');

$params = [];
$bot = new TriviaBot($params);
unset($bot);//just to get rid of teh unused var warning in phpstorm!

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