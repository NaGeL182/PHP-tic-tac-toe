<?php
declare(strict_types=1);
namespace TicTacToe\Tests;

use \PHPUnit\Framework\TestCase;
use \TicTacToe\Game\Game;
use \TicTacToe\Game\Bot;

final class GameTest extends TestCase
{
    public function newGameObject()
    {
        return new Game(new Bot());
    }

    public function testCreateBoard()
    {
        $game = $this->newGameObject();
        $this->assertInstanceof(Game::class, $game);
    }

    public function testNewGame()
    {
        $game = $this->newGameObject();
        $new = $game->newGame();
        $this->assertEquals(false, $new["winner"]);
        $this->assertEquals(false, $new["over"]);
        $this->assertEquals("X", $new["player"]);
        $this->assertCount(3, $new["board"]);
    }

    public function testNewGameWithDefaultBoardSize()
    {
        $game = $this->newGameObject();
        $new = $game->newGame(7);
        $this->assertEquals(false, $new["winner"]);
        $this->assertEquals(false, $new["over"]);
        $this->assertEquals("X", $new["player"]);
        $this->assertCount(7, $new["board"]);
    }

    /**
     * @dataProvider badBoardSizeProvider
     */
    public function testNewGameWithBadBoardSize(int $size)
    {
        $game = $this->newGameObject();
        $new = $game->newGame($size);
        $this->assertArrayHasKey('error', $new);
    }

    /**
     * @dataProvider invalidBoardSizeProvider
     */
    public function testNewGameWithBadArgument($badArgument)
    {
        $this->expectException(\TypeError::class);
        $game = $this->newGameObject();
        $new = $game->newGame($badArgument);
    }

    public function testMoveWithNoData()
    {
        $game = $this->newGameObject();
        $data = $game->move([]);
        $this->assertArrayHasKey('error', $data);
    }

    public function testMoveWithFalseData()
    {
        $game = $this->newGameObject();
        $data = $game->move([
            "board" => false,
            "boardSize" => false,
            "player" => false
            ]);
        $this->assertArrayHasKey('error', $data);
    }
    public function testMoveWithNoBoardSize()
    {
        $game = $this->newGameObject();
        $data = $game->move([
            "board" => [[false,false, false], [false, false, false], [false, false, false]],
            "player" => "X"
            ]);
        $this->assertArrayHasKey('error', $data);
        $this->assertEquals('No BoardSize', $data["error"]);
    }
    public function testMoveWithNoBoard()
    {
        $game = $this->newGameObject();
        $data = $game->move([
            "boardSize" => 3,
            "player" => "X"
            ]);
        $this->assertArrayHasKey('error', $data);
        $this->assertEquals('No Board', $data["error"]);
    }

    public function testMoveWithNoPlayer()
    {
        $game = $this->newGameObject();
        $data = $game->move([
            "boardSize" => 3,
            "board" => [[false,false, false], [false, false, false], [false, false, false]]
            ]);
        $this->assertArrayHasKey('error', $data);
        $this->assertEquals('No player', $data["error"]);
    }

    public function testMoveWithNonArrayBoard()
    {
        $game = $this->newGameObject();
        $data = $game->move([
            "boardSize" => 5,
            "player" => "X",
            "board" => "",
            ]);
        $this->assertArrayHasKey('error', $data);
        $this->assertEquals('board is not an arrray!', $data["error"]);
    }

    public function testMoveWithNoNStringPlayer()
    {
        $game = $this->newGameObject();
        $data = $game->move([
            "boardSize" => 3,
            "player" => 54,
            "board" => [[false,false, false], [false, false, false], [false, false, false]]
            ]);
        $this->assertArrayHasKey('error', $data);
        $this->assertEquals('player is not an string!', $data["error"]);
    }

    public function testMoveWithIncorrectPlayer()
    {
        $game = $this->newGameObject();
        $data = $game->move([
            "boardSize" => 3,
            "player" => "addgdg",
            "board" => [[false,false, false], [false, false, false], [false, false, false]]
            ]);
        $this->assertArrayHasKey('error', $data);
        $this->assertEquals('player is not a valid mark! (X, O)', $data["error"]);
    }

    public function testMoveWithIncorrectBoardSize()
    {
        $game = $this->newGameObject();
        $data = $game->move([
            "boardSize" => 5,
            "player" => "X",
            "board" => [[false,false, false], [false, false, false], [false, false, false]]
            ]);
        $this->assertArrayHasKey('error', $data);
        $this->assertEquals('the board size and boardSize is not equal!', $data["error"]);
    }

    /**
     * @dataProvider incorrectBoardDataProvider
     */
    public function testMoveWithIncorrectBoardData()
    {
        $game = $this->newGameObject();
        $data = $game->move([
            "boardSize" => 3,
            "player" => "X",
            "board" => [[false,false, false], [false, false, false], [false, false, false, false]]
            ]);
        $this->assertArrayHasKey('error', $data);
    }

    public function testMoveWithStrangeBoardDataData()
    {
        $game = $this->newGameObject();
        $data = $game->move([
            "boardSize" => 3,
            "player" => "X",
            "board" => [["X","foo", "X"], ["O", "bar", false], ["X", "buzz", false]]
        ]);
        $this->assertEquals(false, $data["winner"]);
        $this->assertEquals(false, $data["over"]);
        $this->assertEquals("X", $data["player"]);
        $this->assertCount(3, $data["board"]);
    }

    public function testMoveWithCorrectData()
    {
        $game = $this->newGameObject();
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

    public function testMoveWithOPlayer()
    {
        $game = $this->newGameObject();
        $data = $game->move([
            "boardSize" => 3,
            "player" => "O",
            "board" => [[false,false, false], [false, false, false], [false, false, false]]
        ]);
        $this->assertEquals(false, $data["winner"]);
        $this->assertEquals(false, $data["over"]);
        $this->assertEquals("O", $data["player"]);
        $this->assertCount(3, $data["board"]);
    }

    public function testMoveCheckTotalMoves()
    {
        $game = $this->newGameObject();
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
        $game = $this->newGameObject();
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
        $game = $this->newGameObject();
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
        $game = $this->newGameObject();
        $data = $game->move($request);
        $this->assertTrue($data["over"]);
    }

    public function incorrectBoardDataProvider()
    {
        return [
            "extra_field" => [
                [[false,false, false], [false, false, false], [false, false, false, false]]
            ],
            "extra_row" => [
                [[false,false, false], [false, false, false], [false, false, false], [false, false, false]]
            ],
            "extra_column" => [
                [[false,false, false, false], [false, false, false, false], [false, false, false, false]]
            ]
        ];
    }

    public function invalidBoardSizeProvider()
    {
        return [
            "string" => ["this isa bad string"],
            "array" => [[4]],
            "float" => [4.5],
            "object" => [new \stdClass()],
        ];
    }

    public function badBoardSizeProvider()
    {
        return [
            "2" => [2],
            "1" => [1],
            "0" => [0],
            "minus5" => [-5],
        ];
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
