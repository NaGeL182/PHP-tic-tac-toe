<?php
declare(strict_types=1);
namespace TicTacToe\API;

use \Silex\Application;
use \Symfony\Component\HttpFoundation\Request;
use \Silex\Api\ControllerProviderInterface;

class RESTController implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        // creates a new controller based on the default route
        $controllers = $app['controllers_factory'];

        $controllers->get('/api', 'TicTacToe\\API\\RESTController::apiAction');
        $controllers->match('/newgame', 'TicTacToe\\API\\RESTController::newGameAction')
            ->method('GET|POST');
        $controllers->post('/move', 'TicTacToe\\API\\RESTController::moveAction');

        return $controllers;
    }

    public function apiAction(Request $request, Application $app)
    {
        return $app->json(['version' => '1.0.0']);
    }

    public function newGameAction(Request $request, Application $app)
    {
        $game = $app['TicTacToe.Game'];
        return $app->json($game->newGame($request->get('boardSize', 3), $request->get('player', "X")));
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
        if (!\is_array($board)) {
            return $board;
        }
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
