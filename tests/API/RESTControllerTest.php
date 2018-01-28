<?php
namespace TicTacToe\Tests;

use \Silex\WebTestCase;

/**
 * @coversDefaultClass \TicTacToe\API\RESTController
 */
final class RESTControllerTest extends WebTestCase
{
    /**
     * Returns Silex App
     *
     * @return \Silex\Application
     */
    public function createApplication()
    {
        // app.php must return an Application instance
        $app = require __DIR__.'/../../src/app.php';
        $app["debug"] = true;
        unset($app['exception_handler']);
        return $app;
    }

    /**
     * @covers ::apiAction
     */
    public function testApiGetEndpoint()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/api');
        $this->assertTrue($client->getResponse()->isOk());
        $this->assertEquals('{"version":"1.0.0"}', $client->getResponse()->getContent());
    }

    /**
     * @covers ::newGameAction
     */
    public function testGetNewGameAction()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/newgame');
        $this->assertTrue($client->getResponse()->isOk());
        $data = \json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(false, $data["winner"]);
        $this->assertEquals(false, $data["over"]);
        $this->assertEquals("X", $data["player"]);
        $this->assertCount(3, $data["board"]);
    }

    /**
     * @covers ::newGameAction
     */
    public function testPostNewGameActionNoArguments()
    {
        $client = $this->createClient();
        $crawler = $client->request('POST', '/newgame');
        $this->assertTrue($client->getResponse()->isOk());
        $data = \json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(false, $data["winner"]);
        $this->assertEquals(false, $data["over"]);
        $this->assertEquals("X", $data["player"]);
        $this->assertCount(3, $data["board"]);
    }

    /**
     * @covers ::newGameAction
     */
    public function testPostNewGameActionJustPlayer()
    {
        $client = $this->createClient();
        $crawler = $client->request(
            'POST',
            '/newgame',
            array("player" => "O")
        );
        $this->assertTrue($client->getResponse()->isOk());
        $data = \json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(false, $data["winner"]);
        $this->assertEquals(false, $data["over"]);
        $this->assertEquals("O", $data["player"]);
        $this->assertCount(3, $data["board"]);
    }

    /**
     * @covers ::newGameAction
     */
    public function testPostNewGameActionJustBoard()
    {
        $client = $this->createClient();
        $crawler = $client->request(
            'POST',
            '/newgame',
            array("board" => 3)
        );
        $this->assertTrue($client->getResponse()->isOk());
        $data = \json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(false, $data["winner"]);
        $this->assertEquals(false, $data["over"]);
        $this->assertEquals("X", $data["player"]);
        $this->assertCount(3, $data["board"]);
    }

    /**
     * @covers ::moveAction
     */
    public function testPostMoveActionNoData()
    {
        $client = $this->createClient();
        $crawler = $client->request(
            'POST',
            '/move'
        );
        $this->assertTrue($client->getResponse()->isOk());
        $data = \json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('error', $data);
    }

    /**
     * @dataProvider correctMoveDataProvider
     * @covers ::moveAction
     */
    public function testPostMoveActionWithCorectData($data)
    {
        $client = $this->createClient();
        $crawler = $client->request(
            'POST',
            '/move',
            $data
        );
        $this->assertTrue($client->getResponse()->isOk());
        $response = \json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(false, $response["winner"]);
        $this->assertEquals(false, $response["over"]);
        $this->assertEquals($data["player"], $response["player"]);
        $this->assertCount($data["boardSize"], $response["board"]);
    }

    /**
     * @dataProvider correctMoveDataProvider
     * @covers ::<private>
     */
    public function testConvertFalseStringAnd0ToFalse()
    {
        $data = [
            "player" => "X",
            "boardSize" => 3,
            "board" => [
                ["false", "", 0],
                ["0", false, false],
                [false, false, false],
            ]
        ];
        $client = $this->createClient();
        $crawler = $client->request(
            'POST',
            '/move',
            $data
        );
        $this->assertTrue($client->getResponse()->isOk());
        $response = \json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(false, $response["winner"]);
        $this->assertEquals(false, $response["over"]);
        $this->assertEquals($data["player"], $response["player"]);
        $this->assertCount($data["boardSize"], $response["board"]);
    }

    /**
     * @dataProvider correctMoveDataProvider
     * @covers ::<private>
     */
    public function testConvertFalseStringAnd0ToFalseNonArrayWasGiven()
    {
        $data = [
            "player" => "X",
            "boardSize" => 3,
            "board" => "This is a board"
        ];
        $client = $this->createClient();
        $crawler = $client->request(
            'POST',
            '/move',
            $data
        );
        $this->assertTrue($client->getResponse()->isOk());
        $response = \json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('error', $response);
        $this->assertEquals("board is not an array!", $response["error"]);
    }

    public function correctMoveDataProvider()
    {
        return [
            "default" => [
                [
                    "player" => "X",
                    "boardSize" => 3,
                    "board" => [
                        [false, false, false],
                        [false, false, false],
                        [false, false, false],
                    ]
                ]
            ],
            "Oplayer" => [
                [
                    "player" => "O",
                    "boardSize" => 3,
                    "board" => [
                        [false, false, false],
                        [false, false, false],
                        [false, false, false],
                    ]
                ]
            ],
            "default" => [
                [
                    "player" => "X",
                    "boardSize" => 5,
                    "board" => [
                        [false, false, false, false, false],
                        [false, false, false, false, false],
                        [false, false, false, false, false],
                        [false, false, false, false, false],
                        [false, false, false, false, false],
                    ]
                ]
            ],
        ];
    }
}
