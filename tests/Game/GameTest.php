<?php
declare(strict_types=1);
namespace TicTacToe\Tests;

use \PHPUnit\Framework\TestCase;
use \TicTacToe\Game\Game;
use \TicTacToe\Game\Bot;
/**
 * @coversDefaultClass \TicTacToe\Game\Game
 */
final class GameTest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function testnewGameObject()
    {
        $game = new Game(new Bot());
        $this->assertInstanceof(Game::class, $game);
        return $game;
    }

    /**
     * @depends testnewGameObject
     */
    public function testNewGame(Game $game)
    {
        $new = $game->newGame();
        $this->assertEquals(false, $new["winner"]);
        $this->assertEquals(false, $new["over"]);
        $this->assertEquals("X", $new["player"]);
        $this->assertCount(3, $new["board"]);
    }

    /**
     * @depends testnewGameObject
     */
    public function testNewGameWithDefaultBoardSize(Game $game)
    {
        $new = $game->newGame();
        $this->assertEquals(false, $new["winner"]);
        $this->assertEquals(false, $new["over"]);
        $this->assertEquals("X", $new["player"]);
        $this->assertCount(3, $new["board"]);
    }

    /**
     * @dataProvider badBoardSizeProvider
     * @depends testnewGameObject
     */
    public function testNewGameWithBadBoardSize(int $size, Game $game)
    {
        $new = $game->newGame($size);
        $this->assertArrayHasKey('error', $new);
    }

    /**
     * @dataProvider invalidBoardSizeProvider
     * @depends testnewGameObject
     */
    public function testNewGameWithBadArgument($badArgument, Game $game)
    {
        $this->expectException(\TypeError::class);
        $new = $game->newGame($badArgument);
    }

    /**
     * @depends testnewGameObject
     */
    public function testMoveWithFalseData(Game $game)
    {
        $data = $game->move([
            "board" => false,
            "boardSize" => false,
            "player" => false
            ]);
        $this->assertArrayHasKey('error', $data);
    }

    /**
     * @depends testnewGameObject
     */
    public function testMoveWithNoData(Game $game)
    {
        $data = $game->move([]);
        $this->assertArrayHasKey('error', $data);
        $this->assertEquals('Game data is empty!', $data["error"]);
    }

    /**
     * @depends testnewGameObject
     */
    public function testMoveWithNoBoardSize(Game $game)
    {
        $data = $game->move([
            "board" => [[false,false, false], [false, false, false], [false, false, false]],
            "player" => "X"
            ]);
        $this->assertArrayHasKey('error', $data);
        $this->assertEquals('No BoardSize', $data["error"]);
    }
    /**
     * @depends testnewGameObject
     */
    public function testMoveWithNoBoard(Game $game)
    {
        $data = $game->move([
            "boardSize" => 3,
            "player" => "X"
            ]);
        $this->assertArrayHasKey('error', $data);
        $this->assertEquals('No Board', $data["error"]);
    }

    /**
     * @depends testnewGameObject
     */
    public function testMoveWithNoPlayer(Game $game)
    {
        $data = $game->move([
            "boardSize" => 3,
            "board" => [[false,false, false], [false, false, false], [false, false, false]]
            ]);
        $this->assertArrayHasKey('error', $data);
        $this->assertEquals('No player', $data["error"]);
    }

    /**
     * @depends testnewGameObject
     */
    public function testMoveWithNonArrayBoard(Game $game)
    {
        $data = $game->move([
            "boardSize" => 5,
            "player" => "X",
            "board" => "",
            ]);
        $this->assertArrayHasKey('error', $data);
        $this->assertEquals('board is not an arrray!', $data["error"]);
    }

    /**
     * @depends testnewGameObject
     */
    public function testMoveWithNoNStringPlayer(Game $game)
    {
        $data = $game->move([
            "boardSize" => 3,
            "player" => 54,
            "board" => [[false,false, false], [false, false, false], [false, false, false]]
            ]);
        $this->assertArrayHasKey('error', $data);
        $this->assertEquals('player is not an string!', $data["error"]);
    }

    /**
     * @depends testnewGameObject
     */
    public function testMoveWithIncorrectPlayer(Game $game)
    {
        $data = $game->move([
            "boardSize" => 3,
            "player" => "addgdg",
            "board" => [[false,false, false], [false, false, false], [false, false, false]]
            ]);
        $this->assertArrayHasKey('error', $data);
        $this->assertEquals('player is not a valid mark! (X, O)', $data["error"]);
    }

    /**
     * @depends testnewGameObject
     */
    public function testMoveWithIncorrectBoardSize(Game $game)
    {
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
     * @depends testnewGameObject
     */
    public function testMoveWithIncorrectBoardData($board, Game $game)
    {
        $data = $game->move([
            "boardSize" => 3,
            "player" => "X",
            "board" => $board,
            ]);
        $this->assertArrayHasKey('error', $data);
    }

    /**
     * @depends testnewGameObject
     */
    public function testMoveWithStrangeBoardDataData(Game $game)
    {
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

    /**
     * @depends testnewGameObject
     */
    public function testMoveWithCorrectData(Game $game)
    {
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

    /**
     * @depends testnewGameObject
     */
    public function testMoveWithOPlayer(Game $game)
    {
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

    /**
     * @depends testnewGameObject
     */
    public function testMoveCheckTotalMoves(Game $game)
    {
        $data = $game->move([
            "boardSize" => 3,
            "player" => "X",
            "board" => [[false,false, "O"], ["X", false, false], ["X", false, false]]
        ]);
        $this->assertEquals(4, $data["totalMoves"]);
    }

    public function testSetBot()
    {
        $game = new Game();
        $game->setBot(new Bot());
        $this->assertInstanceOf(Bot::class, $game->getBot());
    }

    public function testMoveBotcantMakeMove()
    {
        $game = new Game();
        $bot = $this->createMock('\TicTacToe\Game\Bot');
        $bot->method('makeMove')
            ->willReturn(["error" => "no moves left"]);
        $game->setBot($bot);
        $data = $game->move([
            "boardSize" => 3,
            "player" => "X",
            "board" => [[false,false, false], [false, false, false], [false, false, false]]
            ]);
        $this->assertArrayHasKey('error', $data);
        $this->assertEquals('no moves left', $data["error"]);
    }

    /**
     * @depends testnewGameObject
     */
    public function testXWon(Game $game)
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
        $data = $game->move($request);
        $this->assertTrue($data["over"]);
        $this->assertEquals("X", $data["winner"]);
    }

    /**
     * @depends testnewGameObject
     */
    public function testOWon(Game $game)
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
        $data = $game->move($request);
        $this->assertTrue($data["over"]);
        $this->assertEquals("O", $data["winner"]);
    }

    /**
     * @dataProvider boardProvider
     * @depends testnewGameObject
     */
    public function testGameWinConditions($board, Game $game)
    {
        $size = count($board);
        $request = [
            "boardSize" => $size,
            "player" => "X",
            "board" => $board
        ];
        $game = $this->testnewGameObject();
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
