<?php


namespace frontend\events;


use yii\base\Event;
use yii\base\Model;

/**
 * Class InformationUpdateEvent
 * Абстрактный класс для дальнейшего неследования конкретных событий изменения, например Project или Task
 * @package frontend\events
 */
abstract class InformationUpdateEvent extends Event
{
    public const EVENT_INFORMATION_CHANGE = 'information-change-event';

    public const NEW_RECORD = 'information-new';

    public const UPDATE_RECORD = 'information-update';

    public const DELETE_RECORD = 'information-delete';

    /**
     * @var Model
     * Содержит модель, с которой произошли изменение
     */
    public $model;

    /**
     * @var string $action
     * Указывает на действие, которой произошло. Должно задаваться в виде
     * InformationUpdateEvent::NEW_RECORD
     */
    public $action;

    /**
     * Должно возвращать сообщение о произошедшем изменении
     * @return string
     */
    abstract public function notificationMessage():string ;
}