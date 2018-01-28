<?php
declare(strict_types=1);
namespace TicTacToe\Tests;

use \PHPUnit\Framework\TestCase;
use \TicTacToe\Game\Board;

/**
 * @coversDefaultClass \TicTacToe\Game\Board
 */
final class BoardTest extends TestCase
{
    /**
     * @covers ::setBoard
     * @covers ::getBoard
     */
    public function testSetGetBoardArrayGiven()
    {
        $array = [];
        $board = new Board();
        $board->setBoard($array);
        $this->assertEquals($array, $board->getBoard());
    }

    /**
     * @covers ::checkValidity
     * @covers ::isValid
     */
    public function testCheckValidityNoBoard()
    {
        $board = new Board();
        $board->checkValidity();
        $this->assertEquals(false, $board->isValid());
    }

    /**
     * @covers ::checkValidity
     * @covers ::isValid
     */
    public function testCheckValidityEmptyBoard()
    {
        $array = [[]];
        $board = new Board();
        $board->setBoard($array);
        $board->checkValidity();
        $this->assertEquals(false, $board->isValid());
    }

    /**
     * @covers ::checkValidity
     * @covers ::isValid
     */
    public function testCheckValidityInvalidRow()
    {
        $array = ["hello"];
        $board = new Board();
        $board->setBoard($array);
        $board->checkValidity();
        $this->assertEquals(false, $board->isValid());
    }

    /**
     * @covers ::checkValidity
     * @covers ::isValid
     */
    public function testCheckValidityInvalidColumnCount()
    {
        $array = [
            [false, false],
            [false, false, false]
        ];
        $board = new Board();
        $board->setBoard($array);
        $board->checkValidity();
        $this->assertEquals(false, $board->isValid());
    }

    /**
     * @covers ::checkValidity
     * @covers ::isValid
     */
    public function testCheckValidityValidBoard()
    {
        $array = [
            [false, false, false],
            [false, false, false],
            [false, false, false],
        ];
        $board = new Board();
        $board->setBoard($array);
        $board->checkValidity();
        $this->assertEquals(true, $board->isValid());
    }
}
