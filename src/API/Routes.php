<?php
namespace TicTacToe\API;

use \Silex\Application;
use \Symfony\Component\HttpFoundation\Request;
use \Silex\Api\ControllerProviderInterface;

class Routes implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        // creates a new controller based on the default route
        $controllers = $app['controllers_factory'];

        $controllers->get('/api', 'TicTacToe\\API\\Routes::apiAction');
        $controllers->get('/newgame/{boardSize}', 'TicTacToe\\API\\Routes::newGameAction')
            ->value('boardSize', 3)
            ->assert('boardSize', '\d+');
        $controllers->post('/move', 'TicTacToe\\API\\Routes::moveAction');

        return $controllers;
    }

    public function apiAction(Request $request, Application $app)
    {
        return $app->json(['version' => '1.0.0']);
    }

    public function newGameAction(Request $request, Application $app, $boardSize)
    {
        $game = $app['TicTacToe.Game'];
        return $app->json($game->newGame($boardSize));
    }

    public function moveAction(Request $request, Application $app)
    {
        $gameData = [];
        $gameData["board"] = $this->convertFalseStringAnd0ToFalse($request->get('board', false));
        $gameData["boardSize"] = (int)$request->get('boardSize', false);
        $gameData["player"] = $request->get('player', false);
        $game = $app['TicTacToe.Game'];
        return $app->json($game->move($gameData));
    }

    private function convertFalseStringAnd0ToFalse($board)
    {
        foreach ($board as $x => $row) {
            foreach ($row as $y => $field) {
                if ($field === "false" || $field === "" || $field ===  0 || $field === "0") {
                    $board[$x][$y] = false;
                }
            }
        }
        return $board;
    }
}
