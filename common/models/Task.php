<?php

namespace common\models;

use common\behaviors\FillAuthorBehavior;
use common\behaviors\TimestampTransformBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "tasks".
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string $created_at
 * @property string $updated_at
 * @property string|null $due_at
 * @property int $author_id
 * @property int $responsible_id
 *
 * @property User $author
 * @property User $responsible
 */
class Task extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'tasks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'responsible_id'], 'required'],
            [['description'], 'string'],
            [['created_at', 'updated_at', 'due_at'], 'safe'],
            [['responsible_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['responsible_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['responsible_id' => 'id']],
        ];
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className() => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
            TimestampTransformBehavior::className() => [
                'class' => TimestampTransformBehavior::className(),
                'attributes' => ['created_at', 'updated_at', 'due_at'],
            ],
            FillAuthorBehavior::className() => [
                'class' => FillAuthorBehavior::className(),
                'target' => 'author_id'
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'description' => 'Описание задачи',
            'created_at' => 'Задача создана',
            'updated_at' => 'Задача обновлена',
            'due_at' => 'Вывполнить до',
            'author_id' => 'ID Автора',
            'responsible_id' => 'ID Ответственного',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'author_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResponsible()
    {
        return $this->hasOne(User::className(), ['id' => 'responsible_id']);
    }

    /**
     * {@inheritdoc}
     * @return TasksQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TasksQuery(get_called_class());
    }
}
