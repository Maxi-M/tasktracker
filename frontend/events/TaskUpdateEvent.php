<?php


namespace frontend\events;


use common\models\Task;

class TaskUpdateEvent extends InformationUpdateEvent
{
    public const EVENT_TASK_UPDATE = 'task-update-event';

    public function notificationMessage(): string
    {
        /** @var Task $model */
        $model = $this->model;

        switch ($this->action) {
            case InformationUpdateEvent::UPDATE_RECORD:
                $action = 'обновлена';
                break;

            case InformationUpdateEvent::NEW_RECORD:
                $action = 'создана';
                break;

            default:
                $action = 'удалена';
                break;

        }
        return 'Задача "'. $model->name. '" была только что '. $action;
    }
}