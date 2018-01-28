<?php
declare(strict_types=1);
namespace TicTacToe\Game;

use TicTacToe\Game\Interfaces\BotInterface;

class Bot implements BotInterface
{
    public const DIFFICULTY_RANDOM = "random";
    public const DIFFICULTY_BEGINNER = "beginner";
    public const DIFFICULTY_NORMAL = "normal";
    public const DIFFICULTY_EXPERT = "expert";

    private const DIFFICULTIES = [
        self::DIFFICULTY_RANDOM,
        self::DIFFICULTY_BEGINNER,
        self::DIFFICULTY_NORMAL,
        self::DIFFICULTY_EXPERT,
    ];

    private $board;
    private $possibleMoves;
    private $chosenMove;

   /* public function __construct(string $Botmarker = null, string $playerMarker = null, $)
    {

    }*/

    public function makeMove(array $board): array
    {
        $this->board = $board;
        $this->analyzeBoard();
        $this->calculateMove();
        return $this->returnMove();
    }

    private function analyzeBoard()
    {
        //geta list of all available move
        $this->possibleMoves = [];
        foreach ($this->board as $xkey => $x) {
            foreach ($x as $ykey => $y) {
                if ($y == false) {
                    $this->possibleMoves[] = [$xkey, $ykey];
                }
            }
        }
    }

    private function calculateMove()
    {
        $count = count($this->possibleMoves);
        if ($count === 0) {
            $this->chosenMove = ["error" => "no moves left"];
        } elseif ($count === 1) {
            $this->chosenMove = $this->possibleMoves[0];
        } else {
            $this->chosenMove = $this->possibleMoves[\random_int(0, count($this->possibleMoves)-1)];
        }
    }

    private function returnMove()
    {
        return $this->chosenMove;
    }
}
