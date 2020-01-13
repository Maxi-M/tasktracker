<?php


namespace common\behaviors;


use yii\base\Behavior;
use yii\db\ActiveRecord;

/**
 * Class TimestampTransformBehavior
 * @package app\components
 *
 * Поведение принимает атрибуты, в которых хранится дата в формате Unix Timestamp.
 * Реагирует на события:
 *  ActiveRecord::EVENT_BEFORE_INSERT
 *  ActiveRecord::EVENT_BEFORE_UPDATE
 * При наступлении данных событий преобразует дату в указанных атрибутах к виду MySql Timestamp ГГГГ-ММ-ДД ЧЧ:ММ:СС
 */
class TimestampTransformBehavior extends Behavior
{
    /**
     * Массив атрибутов модели, в которых хранится дата в формате Unix timestamp
     * @var string[]
     */
    public $attributes = [];

    /**
     * Объявляет обработчики событий для событий владельца (модели)
     * @return array
     */
    public function events(): array
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'transform',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'transform'
        ];
    }

    /**
     * Преобразует дату из Unix timestamp в MySql timestamp, который принимает данные вида ГГГГ-ММ-ДД ЧЧ:ММ:СС
     */
    public function transform()
    {
        foreach ($this->attributes as $attribute) {
            if (is_int($this->owner->$attribute)) {
                $this->owner->$attribute = date('Y-m-d H:i:s', $this->owner->$attribute);
            }
        }
    }
}