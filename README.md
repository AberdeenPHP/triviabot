# triviabot
Trivia bot that runs in a Slack channel.

To install run `composer require btk/triviabot dev-master`.

**THIS PACKAGE IS STILL IN DEVELOPMENT** - seriously, it's not even ante-pre-alpha yet.
It's strongly recommended to make the root of your webserver/subdomain the folder triviabot/src/webroot as this will keep your db and config files etc away from prying eyes.

Things you'll need to set up if you want this to run:

* Incoming webhook integration in slack (put these in config.php)
* Outgoing webhook in slack (put these in config.php)
* A way of running a script at an interval (e.g. CRON) (you want to run vendor/btk/triviabot/src/run.php as often as you can stomach)
* DB details (put these in db.php)

Your webserver should ideally use a subdomain where webroot is the folder /vendor/btk/triviabot/src/webroot/ so your outgoing webhook URL can be, e.g. http://triviabot.example.com/endpoint.php

###Example CRON Settings.

I use a 10 second delay between running the scripts. I've tested this with over 30,000 questions loaded in the bot and it works fine for me.
Cron however, only uses minutes as it's minimum time increment. So I set mine up like this...


    * * * * * php /var/www/triviabot/vendor/btk/triviabot/src/run.php  
    * * * * * sleep 10; php /var/www/triviabot/vendor/btk/triviabot/src/run.php
    * * * * * sleep 20; php /var/www/triviabot/vendor/btk/triviabot/src/run.php
    * * * * * sleep 30; php /var/www/triviabot/vendor/btk/triviabot/src/run.php
    * * * * * sleep 40; php /var/www/triviabot/vendor/btk/triviabot/src/run.php
    * * * * * sleep 50; php /var/www/triviabot/vendor/btk/triviabot/src/run.php

The minimum delay between questions/hints (the output of the run.php file) is 20 seconds so this should give more than we need.

###Database setup.

In the vendor/btk/triviabot/ folder there is a triviabot.sql file that you can import into your database to set up your tables for the bot.