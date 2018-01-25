<?php
namespace doclerPHP\API;

use \Silex\Application;
use \Symfony\Component\HttpFoundation\Request;
use \doclerPHP\Game\Board;


class Routes {

    public function api(Request $request, Application $app)
    {
        return $app->json(['version' => '1.0.0']);
    }

    public function newgame(Request $request, Application $app, $boardSize)
    {
        $game = new Board();
        return $app->json($game->newGame($boardSize));
    }

    public function move(Request $request, Application $app)
    {
        $gameData = [];
        $gameData["board"] = $this->convertFalseStringAnd0ToFalse($request->get('board', false));
        $gameData["boardSize"] = (int)$request->get('boardSize', false);
        $gameData["player"] = $request->get('player', false);
        $game = new Board();
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
