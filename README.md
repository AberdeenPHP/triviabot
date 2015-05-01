# triviabot
Trivia bot that runs in a Slack channel.

To install run `composer require btk/triviabot dev-master`.

It's strongly recommended to make the root of your webserver/subdomain the folder triviabot/src/webroot as this will keep your db and config files etc away from prying eyes.

Things you'll need to set up if you want this to run:

* Incoming web hook integration in slack (put these details in config.php)
* Outgoing web hook in slack (put these details in config.php)
* DB details (put these in config.php)
* A way of running a script at an interval (e.g. CRON) (you want to run vendor/btk/triviabot/src/run.php as often as you can stomach)

Your web server should ideally use a sub domain where the web root is the folder /vendor/btk/triviabot/src/webroot/ so your outgoing web hook URL can be, e.g. http://triviabot.example.com/endpoint.php

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

In the vendor/btk/triviabot/ folder there is a triviabot.sql file that you can import into your database to set up your tables for the bot properly.

###Loading Questions

Question files should be placed in the questions folder. An example question file is included for reference. To load this in the bot type the command `!trivia load example.txt` in your slack trivia channel.
You should receive a response from the bot saying your file is loaded.
Loading large files may require your PHP max_execution_time is set to a larger number, 30 seconds is the default. A value of 300 allowed me to load a 350,000 question strong file.

I have included a file named questions.sql.zip which can be inserted into your database should you wish a pre-populated list of over 160,000 questions to kick you off. This includes the example.txt questions. **Load this file via MySQL not via the** `!trivia load [file]` **command**