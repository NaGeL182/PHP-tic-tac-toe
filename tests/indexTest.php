<?php
namespace TicTacToe\Tests;

use PHPUnit\Framework\TestCase;
use Silex\WebTestCase;

final class IndexTest extends WebTestCase
{
    private $ch;

    public function createApplication()
    {
        // app.php must return an Application instance
        $app = require __DIR__.'/../src/app.php';
        $app["debug"] = true;
        unset($app['exception_handler']);
        return $app;
    }

    private function setupCURL(string $URL)
    {
        //cURL
        $this->ch = curl_init($URL);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($this->ch, CURLOPT_HEADER, false);
        curl_setopt($this->ch, CURLOPT_VERBOSE, false);
    }

    private function closeCURL()
    {
        curl_close($this->ch);
    }

    public function testAPIEndpoint()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/api');
        $this->assertTrue($client->getResponse()->isOk());
        $this->assertEquals('{"version":"1.0.0"}', $client->getResponse()->getContent());
    }

    public function testdefaultNewGameEndpoint()
    {
        $this->setupCURL("http://localhost/newgame");
        $data = curl_exec($this->ch);
        $this->assertNotFalse($data);
        $this->closeCURL();
        $data = json_decode($data, true);
        $this->assertEquals(false, $data["winner"]);
        $this->assertEquals(false, $data["over"]);
        $this->assertEquals("X", $data["player"]);
        $this->assertCount(3, $data["board"]);
    }

    public function testNewGameEndpointWithBoardSizeIs5()
    {
        $this->setupCURL("http://localhost/newgame/5");
        $data = curl_exec($this->ch);
        $this->assertNotFalse($data);
        $this->closeCURL();
        $data = json_decode($data, true);
        $this->assertEquals(false, $data["winner"]);
        $this->assertEquals(false, $data["over"]);
        $this->assertEquals("X", $data["player"]);
        $this->assertCount(5, $data["board"]);
    }

    public function testNewGameEndpointWithBadArg()
    {
        $this->setupCURL("http://localhost/newgame/bah");
        $data = curl_exec($this->ch);
        $this->assertNotFalse($data);
        $info = curl_getinfo($this->ch);
        $this->closeCURL();
        $this->assertEquals(404, $info["http_code"]);
    }

    public function testNewGameEndpointWithBoardSizeLessThan3()
    {
        $this->setupCURL("http://localhost/newgame/1");
        $data = curl_exec($this->ch);
        $this->assertNotFalse($data);
        $info = curl_getinfo($this->ch);
        $this->closeCURL();
        $data = json_decode($data, true);
        $this->assertEquals(200, $info["http_code"]);
        $this->assertArrayHasKey('error', $data);
    }

    /**
     * @dataProvider noWinDataProvider
     */
    public function testMoveEndpointwithDataNoWin($data)
    {
        $this->setupCURL("http://localhost/move");
        curl_setopt($this->ch, CURLOPT_POST, 1);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, http_build_query($data));
        $data = curl_exec($this->ch);
        $this->closeCURL();
        $data = json_decode($data, true);
        $this->assertArrayNotHasKey("error", $data);
        $this->assertFalse($data["over"]);
    }

    public function noWinDataProvider()
    {
        return [
            "emptyBoard" => [
                [
                    "player" => "X",
                    "board" => [
                                [false,false, false],
                                [false, false, false],
                                [false, false, false]
                    ],
                    "boardSize" => 3
                ]
            ],
            "two_steps" => [
                [
                    "player" => "X",
                    "board" => [
                                ["X",false, false],
                                [false, "O", false],
                                [false, false, false]
                    ],
                    "boardSize" => 3
                ]
            ]
        ];
    }
}
