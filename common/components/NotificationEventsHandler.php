<?php


namespace common\components;


use frontend\events\InformationUpdateEvent;
use frontend\models\ChatLog;
use yii\base\Component;

class NotificationEventsHandler extends Component
{
    public function init()
    {
        \Yii::$app->on(InformationUpdateEvent::EVENT_INFORMATION_CHANGE, [$this, 'onInformationEventUpdate']);
    }

    public function onInformationEventUpdate($event)
    {
        $msgArray = [
            'created_at' => \Yii::$app->formatter->asDatetime(time()),
            'message' => $event->notificationMessage(),
            'username' => 'Система уведомлений',
            'type' => ChatLog::SEND_MESSAGE
        ];
        ChatLog::create($msgArray);
    }
}