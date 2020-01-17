<?php


namespace console\controllers;
use yii\console\Controller;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use console\components\SocketServer;
class SocketController extends Controller
{
    public function actionStart($port = 8080)
    {
        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new SocketServer()
                )
            ),
            $port
        );
        echo 'Сервер запущен'.PHP_EOL;
        $server->run();
        echo 'Сервер выключен'.PHP_EOL;
    }
}