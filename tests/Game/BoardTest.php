<?php
namespace doclerPHP\Tests;

use PHPUnit\Framework\TestCase;
use \doclerPHP\Game\Board;

final class BoardTest extends TestCase
{
    public function testCreateBoard()
    {
        $game = new Board();
        $this->assertInstanceof(Board::class, $game);
    }

    public function testNewGame()
    {
        $game = new Board();
        $new = $game->newGame();
        $this->assertEquals(false, $new["winner"]);
        $this->assertEquals(false, $new["over"]);
        $this->assertEquals("X", $new["player"]);
        $this->assertCount(3, $new["board"]);
    }

    public function testNewGameWithBoardSize()
    {
        $game = new Board();
        $new = $game->newGame(7);
        $this->assertEquals(false, $new["winner"]);
        $this->assertEquals(false, $new["over"]);
        $this->assertEquals("X", $new["player"]);
        $this->assertCount(7, $new["board"]);
    }

    public function testNewGameWithBadBoardSize()
    {
        $game = new Board();
        $new = $game->newGame(-7);
        $this->assertArrayHasKey('error', $new);
    }

    public function testNewGameWithBadArgument()
    {
        $this->expectException(\TypeError::class);
        $game = new Board();
        $new = $game->newGame("badargument");
    }

    public function testMoveWithNoData()
    {
        $game = new Board();
        $data = $game->move([]);
        $this->assertArrayHasKey('error', $data);
    }

    public function testMoveWithFalseData()
    {
        $game = new Board();
        $data = $game->move([
            "board" => false,
            "boardSize" => false,
            "player" => false
            ]);
        $this->assertArrayHasKey('error', $data);
    }
    public function testMoveWithNoBoardSize()
    {
        $game = new Board();
        $data = $game->move([
            "board" => [[false,false, false], [false, false, false], [false, false, false]],
            "player" => "X"
            ]);
        $this->assertArrayHasKey('error', $data);
    }
    public function testMoveWithNoBoard()
    {
        $game = new Board();
        $data = $game->move([
            "boardSize" => 3,
            "player" => "X"
            ]);
        $this->assertArrayHasKey('error', $data);
    }

    public function testMoveWithNoPlayer()
    {
        $game = new Board();
        $data = $game->move([
            "boardSize" => 3,
            "board" => [[false,false, false], [false, false, false], [false, false, false]]
            ]);
        $this->assertArrayHasKey('error', $data);
    }

    public function testMoveWithIncorrectBoardSize()
    {
        $game = new Board();
        $data = $game->move([
            "boardSize" => 5,
            "player" => "X",
            "board" => [[false,false, false], [false, false, false], [false, false, false]]
            ]);
        $this->assertArrayHasKey('error', $data);
    }

    public function testMoveWithIncorrectBoardOneExtraField()
    {
        $game = new Board();
        $data = $game->move([
            "boardSize" => 3,
            "player" => "X",
            "board" => [[false,false, false], [false, false, false], [false, false, false, false]]
            ]);
        $this->assertArrayHasKey('error', $data);
    }

    public function testMoveWithIncorrectBoardOneExtraRow()
    {
        $game = new Board();
        $data = $game->move([
            "boardSize" => 3,
            "player" => "X",
            "board" => [[false,false, false], [false, false, false], [false, false, false], [false, false, false]]
            ]);
        $this->assertArrayHasKey('error', $data);
    }

    public function testMoveWithIncorrectPlayer()
    {
        $game = new Board();
        $data = $game->move([
            "boardSize" => 3,
            "player" => "addgdg",
            "board" => [[false,false, false], [false, false, false], [false, false, false]]
            ]);
        $this->assertArrayHasKey('error', $data);
    }

    public function testMoveWithCorrectData()
    {
        $game = new Board();
        $data = $game->move([
            "boardSize" => 3,
            "player" => "X",
            "board" => [[false,false, false], [false, false, false], [false, false, false]]
        ]);
        $this->assertEquals(false, $data["winner"]);
        $this->assertEquals(false, $data["over"]);
        $this->assertEquals("X", $data["player"]);
        $this->assertCount(3, $data["board"]);
    }

    public function testMoveCheckTotalMoves()
    {
        $game = new Board();
        $data = $game->move([
            "boardSize" => 3,
            "player" => "X",
            "board" => [[false,false, "O"], ["X", false, false], ["X", false, false]]
        ]);
        $this->assertEquals(4, $data["totalMoves"]);
    }

    public function testXWon()
    {
        $request = [
            "boardSize" => 3,
            "player" => "X",
            "board" => [
                ["X","X", "X"],
                [false, false, false],
                [false, false, false]
            ]
        ];
        $game = new Board();
        $data = $game->move($request);
        $this->assertTrue($data["over"]);
        $this->assertEquals("X", $data["winner"]);
    }

    public function testOWon()
    {
        $request = [
            "boardSize" => 3,
            "player" => "O",
            "board" => [
                ["O","O", "O"],
                [false, false, false],
                [false, false, false]
            ]
        ];
        $game = new Board();
        $data = $game->move($request);
        $this->assertTrue($data["over"]);
        $this->assertEquals("O", $data["winner"]);
    }

    /**
     * @dataProvider boardProvider
     */
    public function testGameWinConditions($board)
    {
        $size = count($board);
        $request = [
            "boardSize" => $size,
            "player" => "X",
            "board" => $board
        ];
        $game = new Board();
        $data = $game->move($request);
        $this->assertTrue($data["over"]);
    }

    public function boardProvider()
    {
        return [
            "no_more_move" => [[
                ["O","O", "X"],
                ["X", "X", "O"],
                ["O", "X", "X"]
            ]],
            "first_row_X" => [[
                ["X","X", "X"],
                [false, false, false],
                [false, false, false],
            ]],
            "second_row_X" => [[
                [false,false, false],
                ["X","X", "X"],
                [false, false, false]
            ]],
            "third_row_O" => [[
                [false,false, false],
                [false, false, false],
                ["O","O", "O"]
            ]],
            "left_to_right_diag_X" => [[
                ["X",false, false],
                [false, "X", false],
                [false, false, "X"]
            ]],
            "right_to_left_diag_O" => [[
                [false,false, "O"],
                [false, "O", false],
                ["O", false, false]
            ]],
            "fir_column_X" => [[
                ["X",false, false],
                ["X", false, false],
                ["X", false, false]
            ]],
            "left_to_right_diag_X_large" => [[
                ["X", false, false, false, false],
                [false, "X", false, false, false],
                [false, false, "X", false, false],
                [false, false, false, "X", false],
                [false, false, false, false, "X"]
            ]],
            "right_to_left_diag_O_large" => [[
                [false, false, false, false, "O"],
                [false, false, false, "O", false],
                [false, false, "O", false, false],
                [false, "O", false, false, false],
                ["O", false, false, false, false]
            ]],
        ];
    }
}
