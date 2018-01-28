# PHP-tic-tac-toe

Written by Adrián Muszik for practice and job application. The goal was in [this](https://github.com/NaGeL182/PHP-tic-tac-toe/blob/master/practical_backend_task_tic_tac_toe.pdf) document.

Docker link: [Docker image](https://hub.docker.com/r/nagel182/phptictactoe/)


## Requirements

- PHP7.2
- XDebug (for PHPUnit Code Coverage)
- WebServer with rewrite mode activated

## Before running

run the following code
>php composer.phar install

## what I achieved

- [x] NxN grid
- [x] user plays against the bot
- [x] Simple frontend to play the game
- [x] No database

## what I used

- Composer
- Silex
- Twig
- PHPUnit
- Jquery

## Possible improvments

- [ ] Better Bot!
- [ ] Unit test the frontend!
- [X] Complete backend unit test
- [ ] Use an actual Frondend framework
- [ ] Create Vangrant file too

## Dates submited

Here i list the places i submited this code to where and when

- [X] Docler Holding: 2017-01-25

## Changelog

### 2018-01-26

- Added Code Coverage report to phpunit.xml
    it will now put html report under tests/report
- Found a better way to test Silex app that includes the test coverage
- Still need to implement every Route
- Ran Code throught PHP_CodeSniffer with PSR-2 ruleset.
- Noticed html output in while running phpunit binary...*needs fixing*

### 2018-01-27

- Renamed namespace
- Better PHPUnit test for Board.php
- Renamed Board Class to Game
- Made BotInterface
- Made Game's Bot Injectable. (Dependency Injection)
- Fully Completed Test Coverage for Game.php
- [FIX] Html output in PHPUnit test  
    When PHPUnit ran the test it read and ran all the whitelisted files... including index.php which returned HTML and printed it.
- Fully Completed Test Coverage for Bot.php

### 2018-01-28

- Fully Completed Test Coverage for app.php
- Renamed Routes.php to RESTController.php
- reed Routes class to RESTController class
- changed Game::newgame so it accepts player input
- [FIX] you could place markers on already placed fields.
- Fully Completed Test Coverage for RESTController.php
- 100% PHP test Coverage!
