<?php

namespace common\models;

use common\behaviors\FillAuthorBehavior;
use common\behaviors\TimestampTransformBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "projects".
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property int|null $parent_id
 *
 * @property Project $parentProject
 * @property Task[] $tasks
 * @property int $status_id [int(11)]
 * @property int $created_at [timestamp]
 * @property int $updated_at [timestamp]
 * @property Status $status
 * @property User $leader
 * @property int $leader_id [int(11)]
 */
class Project extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'projects';
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
                'attributes' => ['created_at', 'updated_at'],
            ],
            FillAuthorBehavior::className() => [
                'class' => FillAuthorBehavior::className(),
                'target' => 'leader_id'
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'leader_id'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['description'], 'string'],
            [['parent_id', 'leader_id', 'status_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['leader_id'], 'exist', 'skipOnError' => false, 'targetClass' => User::className(), 'targetAttribute' => ['leader_id' => 'id']],
            [['status_id'], 'exist', 'skipOnError' => false, 'targetClass' => Status::className(), 'targetAttribute' => ['status_id' => 'id']],
            [['parent_id'], 'exist', 'skipOnError' => false, 'targetClass' => self::className(), 'targetAttribute' => ['parent_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название проекта',
            'description' => 'Описание',
            'parent_id' => 'Parent ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Task::className(), ['id' => 'project_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParentProject()
    {
        return $this->hasOne(self::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(Status::className(), ['id' => 'status_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLeader()
    {
        return $this->hasOne(User::className(), ['id' => 'leader_id']);
    }
}
