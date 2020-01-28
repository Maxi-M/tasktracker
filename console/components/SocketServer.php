<?php


namespace console\components;

use common\models\Project;
use common\models\Task;
use frontend\events\InformationUpdateEvent;
use frontend\models\ChatLog;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class SocketServer implements MessageComponentInterface
{
    protected $clients;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        $this->sendHelloMessage($conn);
        echo "New connection! ({$conn->resourceId})\n";

    }

    /**
     * @param ConnectionInterface $from
     * @param string $msg
     * @throws \yii\base\InvalidConfigException
     */
    public function onMessage(ConnectionInterface $from, $msg)
    {
        $msgArray = json_decode($msg, true);
        ChatLog::create($msgArray);
        if ($msgArray['type'] === ChatLog::SHOW_HISTORY) {
            $this->showHistory($from, $msgArray);
        } else {
            foreach ($this->clients as $client) {
                $msgArray['created_at'] = \Yii::$app->formatter->asDatetime(time());
                $client->send(json_encode($msgArray));
            }
        }
    }

    private function showHistory(ConnectionInterface $conn, array $msg)
    {
        $chatLogsQuery = ChatLog::find()->orderBy('created_at ASC');
        if (isset($msg['task_id'])) {
            $chatLogsQuery->andWhere(['task_id' => (int)$msg['task_id']]);
        }
        if (isset($msg['project_id'])) {
            $chatLogsQuery->andWhere(['project_id' => (int)$msg['project_id']]);
        }

        foreach ($chatLogsQuery->each() as $chatLog) {
            /**
             * @var ChatLog $chatLog
             */
            $this->sendMessage($conn, [
                'message' => $chatLog->message,
                'username' => $chatLog->username,
                'created_at' => \Yii::$app->formatter->asDatetime($chatLog->created_at)
            ]);
        }
    }

    /**
     * @param ConnectionInterface $conn
     * @param array $msg
     */
    private function sendMessage(ConnectionInterface $conn, array $msg): void
    {
        $conn->send(json_encode($msg));
    }

    /**
     * @param ConnectionInterface $conn
     * @throws \yii\base\InvalidConfigException
     */
    private function sendHelloMessage(ConnectionInterface $conn): void
    {
        $this->sendMessage($conn, ['message' => 'Добро пожаловать', 'username' => 'Чат:', 'created_at' => \Yii::$app->formatter->asDatetime(time())]);
    }

    public function onClose(ConnectionInterface $conn)
    {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }


}