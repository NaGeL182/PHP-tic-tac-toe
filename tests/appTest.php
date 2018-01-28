<?php
namespace TicTacToe\Tests;

use \Silex\WebTestCase;

final class AppTest extends WebTestCase
{
    /**
     * Returns Silex App
     *
     * @return \Silex\Application
     */
    public function createApplication()
    {
        // app.php must return an Application instance
        $app = require __DIR__.'/../src/app.php';
        $app["debug"] = true;
        unset($app['exception_handler']);
        return $app;
    }

    public function testAccessTicTacToeBot()
    {
        $app = $this->createApplication();
        $this->assertInstanceOf(
            \TicTacToe\Game\Interfaces\BotInterface::class,
            $app['TicTacToe.Bot'],
            'It is not a Bot Interface'
        );
    }

    public function testAccessTicTacToeGame()
    {
        $app = $this->createApplication();
        $this->assertInstanceOf(
            \TicTacToe\Game\Game::class,
            $app['TicTacToe.Game'],
            'It is not a Bot Interface'
        );
    }

    /**
     * NativeJS TictacToe Indexx page
     *
     * @return void
     */
    public function testIndexAction()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/');
        $this->assertTrue($client->getResponse()->isOk());
        $this->assertContains(
            "main.js",
            $client->getResponse()->getContent(),
            'main.js (vanillaJS) is not in the default Index'
        );
    }
}
