<?php

namespace frontend\models;

use common\models\Project;
use common\models\Task;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "chat_log".
 *
 * @property int $id
 * @property string|null $username
 * @property int|null $created_at
 * @property string|null $message
 * @property int $type
 * @property int $task_id [int(11)]
 * @property int $project_id [int(11)]
 * @property Project $project
 * @property Task $task
 */
class ChatLog extends \yii\db\ActiveRecord
{
    public const SHOW_HISTORY = 1;
    public const SEND_MESSAGE = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'chat_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        $rules = [
            [['created_at', 'type', 'project_id', 'task_id'], 'integer'],
            [['username', 'type'], 'required'],
            [['message'], 'string'],
            [['username'], 'string', 'max' => 255],
        ];
        if ($this->type === self::SEND_MESSAGE) {
            $rules[] = [['message'], 'required'];
        }

        return $rules;
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className() => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
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
            'username' => 'Username',
            'created_at' => 'Created At',
            'message' => 'Message',
            'type' => 'Type',
        ];
    }

    /**
     * @param array $data
     * @return bool
     */
    public static function create(array $data)
    {
        try {
            $model = new self([
                'username' => $data['username'],
                'message' => $data['message'],
                'type' => $data['type'],
                'project_id' => $data['project_id'] ?? null,
                'task_id' => $data['task_id'] ?? null,
            ]);
            if ($model->save()) {
                return true;
            } else {
                $model->errors;
            }
        } catch (\Throwable $throwable) {
            Yii::error($throwable->getTraceAsString());
            Yii::error(json_encode($data));
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Task::class, ['id' => 'task_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProject()
    {
        return $this->hasOne(Project::class, ['id' => 'project_id']);
    }
}
