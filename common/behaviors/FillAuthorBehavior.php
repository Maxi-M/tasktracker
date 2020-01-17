<?php


namespace common\behaviors;


use yii\base\Behavior;
use yii\db\ActiveRecord;

class FillAuthorBehavior extends Behavior
{
    /**
     * Название поля с id автора
     * @var string
     */
    public $target;

    /**
     * Объявляет обработчики событий для событий владельца (модели)
     * @return array
     */
    public function events(): array
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'fill',
        ];
    }

    /**
     * Заполняет поле с id автора
     */
    public function fill(): void
    {
        $this->owner->$this->target = \Yii::$app->user->id;
    }
}