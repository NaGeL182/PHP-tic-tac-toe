<?php
declare(strict_types=1);
namespace TicTacToe\Game\Interfaces;

interface BotInterface
{
    public function makeMove(array $board);
}
