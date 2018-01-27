<?php

namespace TicTacToe\Tests;

use \PHPUnit\Framework\TestCase;
use \TicTacToe\Game\Bot;

/**
 * @coversDefaultClass \TicTacToe\Game\Bot
 */
final class BotTest extends TestCase
{
    /**
     * @dataProvider boardProvider
     */
    public function testDataGivenToBotReturnCorrectMove($board)
    {
        $board = $board;
        $bot = new Bot();
        $move = $bot->makeMove($board);
        $this->assertInternalType('array', $move);
        $this->assertInternalType('int', $move[0]);
        $this->assertInternalType('int', $move[1]);
        $this->assertFalse($board[$move[0]][$move[1]]);
    }

    /**
     * @covers ::calculateMove
     */
    public function testNoMovesLeft()
    {
        $board = [
            ["O","O", "X"],
            ["X", "X", "O"],
            ["O", "X", "X"]
        ];
        $bot = new Bot();
        $move = $bot->makeMove($board);
        $this->assertArrayHasKey("error", $move);
        $this->assertEquals("no moves left", $move["error"]);
    }

    /**
     * @covers ::calculateMove
     */
    public function testOneMovesLeft()
    {
        $board = [
            ["O", false, "X"],
            ["X", "X", "O"],
            ["O", "X", "X"]
        ];
        $bot = new Bot();
        $move = $bot->makeMove($board);
        $this->assertInternalType('array', $move);
        $this->assertInternalType('int', $move[0]);
        $this->assertInternalType('int', $move[1]);
        $this->assertFalse($board[$move[0]][$move[1]]);
    }

    public function boardProvider()
    {
        return [
            [[[false,false, "O"], ["X", false, false], ["X", false, false]]],
            [[[false,false, false], [false, false, false], [false, false, false]]],
            [[[false,false, false, false], [false, false], [false, false]]]
            
        ];
    }
}
