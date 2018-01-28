<?php
declare(strict_types=1);
namespace TicTacToe\Game\Interfaces;

interface BoardInterface
{
    public function setBoard(array $board): void;

    public function getBoard(): array;

    public function isValid(): bool;

    public function checkValidity(): void;
}
