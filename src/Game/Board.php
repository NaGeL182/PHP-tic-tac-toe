<?php
declare(strict_types=1);
namespace TicTacToe\Game;

use \TicTacToe\Game\Interfaces\BoardInterface;

class Board implements BoardInterface
{
    private $board;
    private $valid;

    public function setBoard(array $board): void
    {
        $this->board = $board;
        $this->valid = false;
    }

    public function getBoard(): array
    {
        return $this->board;
    }

    public function checkValidity(): void
    {
        if (empty($this->board)) {
            $this->valid = false;
            return;
        }
        //check if the row count and column count are the same.
        $rowCount = count($this->board);
        foreach ($this->board as $k => $row) {
            if (!\is_array($row)) {
                $this->valid = false;
                return;
            }
            if (\count($row) !== $rowCount) {
                $this->valid = false;
                return;
            }
        }
        $this->valid = true;
        return;
    }

    public function isValid(): bool
    {
        return $this->valid;
    }
}
