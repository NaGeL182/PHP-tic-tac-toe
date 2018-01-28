<?php
namespace TicTacToe;

require_once __DIR__.'/../vendor/autoload.php';

$app = new \Silex\Application();

$app->register(new \Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

$app['TicTacToe.Bot'] = function () {
    return new \TicTacToe\Game\Bot();
};

$app['TicTacToe.Game'] = function ($app) {
    return new \TicTacToe\Game\Game($app['TicTacToe.Bot']);
};



$app->get('/', function () use ($app) {
    return $app['twig']->render('index.html.twig');
});

$app->mount('/', new \TicTacToe\API\RESTController());

return $app;
