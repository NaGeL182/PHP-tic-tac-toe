<?php
declare(strict_types=1);
namespace TicTacToe\Game;

use TicTacToe\Game\Interfaces\BotInterface;

class Game
{
    private $player;
    private $board;
    private $boardSize;
    private $totalMoves;
    private $maxMoves;
    private $over;
    private $winner;

    /**
     * @var BotInterface $bot Holds the Computer AI thatmakes moves againt the player
     */
    private $bot;

    public function __construct(BotInterface $bot = null)
    {
        if ($bot !== null) {
            $this->bot = $bot;
        }
    }

    /**
     * Sets Game's Bot.
     *
     * @param BotInterface $bot
     * @return void
     */
    public function setBot(BotInterface $bot)
    {
        $this->bot = $bot;
    }

    /**
     * Returns Bot Object
     *
     * @return BotInterface
     */
    public function getBot()
    {
        return $this->bot;
    }

    public function newGame(int $size = 3, string $player = "X")
    {
        if ($size < 3) {
            return ["error" => "Board Size can't be smaller than 3"];
        }
        $playerValidity = $this->checkPlayerValidity($player);
        if ($playerValidity !== true) {
            return $playerValidity;
        }
        $this->start($size, $player);
        $this->newBoard($size);
        return $this->returnState();
    }

    public function move($gameData)
    {
        $validity = $this->checkGameDataValidity($gameData);
        if ($validity !== true) {
            return $validity;
        }
        $this->setupBoard($gameData);
        $this->checkAndSetGameResults();
        if ($this->over == true) {
            return $this->returnState();
        }
        $move = $this->bot->makeMove($this->board);
        if (\array_key_exists("error", $move)) {
            return $move;
        }
        if ($this->player == "X") {
            $this->board[$move[0]][$move[1]] = "O";
        } else {
            $this->board[$move[0]][$move[1]] = "X";
        }
        $this->increaseTotalMoves();
        $this->checkAndSetGameResults();
        return $this->returnState();
    }

    private function start($size, $player)
    {
        $this->player = $player;
        $this->board = [];
        $this->boardSize = $size;
        $this->totalMoves = 0;
        $this->maxMoves = $size * $size;
        $this->over = false;
        $this->winner = false;
    }

    private function increaseTotalMoves(int $amount = 1)
    {
        $this->totalMoves += $amount;
    }

    public function setupBoard($gameData)
    {
        $this->player = $gameData["player"];
        $this->board = $gameData["board"];
        $this->boardSize = $gameData["boardSize"];
        $this->totalMoves = $this->calculateTotalMoves();
        $this->maxMoves = $gameData["boardSize"] * $gameData["boardSize"];
        $this->over = false;
        $this->winner = false;
    }

    private function newBoard(int $size)
    {
        $this->boardSize = $size;
        for ($x = 0; $x < $size; $x++) {
            $this->board[$x] = [];
            for ($y = 0; $y < $size; $y++) {
                $this->board[$x][$y] = false;
            }
        }
    }

    private function returnState()
    {
        $state = [];
        $state["winner"] = $this->winner;
        $state["over"] = $this->over;
        $state["player"] = $this->player;
        $state["board"] = $this->board;
        $state["boardSize"] = $this->boardSize;
        $state["totalMoves"] = $this->totalMoves;
        return $state;
    }

    private function setGameOverStat($winner)
    {
        $this->over = true;
        $this->winner = $winner;
    }

    private function calculateTotalMoves()
    {
        $total = 0;
        for ($x = 0; $x < $this->boardSize; $x++) {
            for ($y = 0; $y < $this->boardSize; $y++) {
                if ($this->board[$x][$y] != false) {
                    $total++;
                }
            }
        }
        return $total;
    }

    private function checkPlayerValidity($player)
    {
        if (!\is_string($player)) {
            return ["error" => "player is not an string!"];
        }
        if (\mb_strtolower($player) != "x" && \mb_strtolower($player) != "o") {
            return ["error" => "player is not a valid mark! (X, O)"];
        }
        return true;
    }

    private function checkGameDataValidity($gameData)
    {
        if (empty($gameData)) {
            return ["error" => "Game data is empty!"];
        }
        if (!\array_key_exists("boardSize", $gameData)) {
            return ["error" => "No BoardSize"];
        }
        if (!\array_key_exists("board", $gameData)) {
            return ["error" => "No Board"];
        }
        if (!\array_key_exists("player", $gameData)) {
            return ["error" => "No player"];
        }
        if (!\is_int($gameData["boardSize"])) {
            return ["error" => "boardSize is not a number!"];
        }
        if (!\is_array($gameData["board"])) {
            return ["error" => "board is not an array!"];
        }
        $playerValidity = $this->checkPlayerValidity($gameData["player"]);
        if ($playerValidity !== true) {
            return $playerValidity;
        }
        if ($gameData["boardSize"] != \count($gameData["board"])) {
            return ["error" => "the board size and boardSize is not equal!"];
        }
        for ($x = 0; $x < $gameData["boardSize"]; $x++) {
            if ($gameData["boardSize"] != \count($gameData["board"][$x])) {
                return ["error" => "the board size and boardSize is not equal!"];
            }
        }
        return true;
    }

    private function checkAndSetGameResults()
    {
        //check row
        for ($x = 0; $x < $this->boardSize; $x++) {
            if (\count(\array_unique($this->board[$x])) === 1 && $this->board[$x][0] !== false) {
                //this row is the winner
                $this->setGameOverStat(\array_unique($this->board[$x])[0]);
                return;
            }
        }

        //check column
        for ($x = 0; $x < $this->boardSize; $x++) {
            $column = \array_column($this->board, $x);

            if (\count(\array_unique($column)) === 1 && $column[0] !== false) {
                //this row is the winner
                $this->setGameOverStat(\array_unique($column)[0]);
                return;
            }
        }

        // check lefttop to right bottom diagonal
        $diagonal = [];
        for ($x = 0; $x < $this->boardSize; $x++) {
            $diagonal[] = $this->board[$x][$x];
        }
        if (\count(\array_unique($diagonal)) === 1 && $diagonal[0] !== false) {
            //this row is the winner
            $this->setGameOverStat(\array_unique($diagonal)[0]);
            return;
        }

        // check right top to left bottom diagonal
        $diagonal = [];
        $y = $this->boardSize -1;
        for ($x = 0; $x < $this->boardSize; $x++) {
            $diagonal[] = $this->board[$x][$y];
            $y--;
        }
        if (\count(\array_unique($diagonal)) === 1 && $diagonal[0] !== false) {
            //this row is the winner
            $this->setGameOverStat(\array_unique($diagonal)[0]);
            return;
        }


        //no one had winning move, see if its the max moves
        if ($this->totalMoves >= $this->maxMoves) {
            $this->setGameOverStat("tie");
        }
    }
}
