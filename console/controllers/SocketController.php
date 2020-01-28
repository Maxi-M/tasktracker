<?php


namespace console\controllers;
use common\models\Project;
use frontend\events\InformationUpdateEvent;
use frontend\events\ProjectUpdateEvent;
use yii\console\Controller;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use console\components\SocketServer;
class SocketController extends Controller
{
    protected $_server;
    public function actionStart($port = 8080)
    {

        $this->_server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new SocketServer()
                )
            ),
            $port
        );

        echo 'Сервер запущен'.PHP_EOL;
        \Yii::$app->on(InformationUpdateEvent::EVENT_INFORMATION_CHANGE, static function ($event) {
            $this->_server->app->notifyChanges($event);
        });
        $this->_server->run();
        echo 'Сервер выключен'.PHP_EOL;
    }
}