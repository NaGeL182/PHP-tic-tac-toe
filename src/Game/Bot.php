<?php
namespace doclerPHP\Game;

class Bot {
    private $board;
    private $possibleMoves;
    private $chosenMove;

    public function makeMove(array $board)
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
        foreach ($this->board as $xkey => $x) 
        {
            foreach ($x as $ykey => $y) 
            {
                if ($y == false) 
                {
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
        } else if ($count === 1){
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