<?php
namespace doclerPHP;

require_once __DIR__.'/../vendor/autoload.php';

$app = new \Silex\Application();

$app->register(new \Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

$app->get('/', function () use ($app) {
    return $app['twig']->render('index.html.twig');
});

$app->get('/api', 'doclerPHP\\API\\Routes::api');
$app->get('/newgame/{boardSize}', 'doclerPHP\\API\\Routes::newgame')
    ->value('boardSize', 3)
    ->assert('boardSize', '\d+');
$app->post('/move', 'doclerPHP\\API\\Routes::move');
$app->run();
return $app;
