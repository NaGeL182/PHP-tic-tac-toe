<?php
namespace TicTacToe;

require_once __DIR__.'/../vendor/autoload.php';

$app = new \Silex\Application();

$app->register(new \Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

$app->get('/', function () use ($app) {
    return $app['twig']->render('index.html.twig');
});

$app->get('/api', 'TicTacToe\\API\\Routes::apiAction');
$app->get('/newgame/{boardSize}', 'TicTacToe\\API\\Routes::newGameAction')
    ->value('boardSize', 3)
    ->assert('boardSize', '\d+');
$app->post('/move', 'TicTacToe\\API\\Routes::moveAction');

return $app;
