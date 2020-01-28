<?php


namespace frontend\events;


use common\models\Project;

class ProjectUpdateEvent extends InformationUpdateEvent
{
    public const EVENT_PROJECT_UPDATE = 'project-update-event';

    public function notificationMessage(): string
    {
        /** @var Project $model */
        $model = $this->model;

        switch ($this->action) {
            case InformationUpdateEvent::UPDATE_RECORD:
                $action = 'обновлён';
                break;

            case InformationUpdateEvent::NEW_RECORD:
                $action = 'создан';
                break;

            default:
                $action = 'удалён';
                break;

        }
        return 'Проект "'. $model->name. '" был только что '. $action;
    }
}