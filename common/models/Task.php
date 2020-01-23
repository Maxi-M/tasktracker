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
 * @property bool $status_id [tinyint(3)]
 * @property bool $is_template [tinyint(1)]
 * @property int $priority_id [int(11)]
 * @property int $project_id [int(11)]
 *
 * @property User $author
 * @property Task $template
 * @property User $responsible

 */
class Task extends \yii\db\ActiveRecord
{
    /**@var $template_id - id шаблона на базе которого создаётся задача (не сохраняется в базе) */
    public $template_id;

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
            [['is_template'], 'boolean'],
            [['name', 'responsible_id'], 'required'],
            [['description'], 'string'],
            [['created_at', 'updated_at', 'due_at'], 'safe'],
            [['responsible_id', 'template_id', 'status_id', 'priority_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['responsible_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['responsible_id' => 'id']],
            [['template_id'], 'exist', 'skipOnError' => true, 'targetClass' => self::className(), 'targetAttribute' => ['template_id' => 'id']],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => Status::className(), 'targetAttribute' => ['status_id' => 'id']],
            [['priority_id'], 'exist', 'skipOnError' => false, 'targetClass' => Priority::className(), 'targetAttribute' => ['priority_id' => 'id']],
            [['project_id'], 'exist', 'skipOnError' => false, 'targetClass' => Project::className(), 'targetAttribute' => ['project_id' => 'id']],
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
            'is_template' => 'Шаблон?',
        ];
    }

    public function beforeValidate()
    {
        if (!empty($this->template_id)) {
            $template = $this->template;
            $this->description = $template->description;
            $this->name = $template->name;
        }
        return parent::beforeValidate();
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
     * @return \yii\db\ActiveQuery
     */
    public function getTemplate()
    {
        return $this->hasOne(self::className(), ['id' => 'template_id']);
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
    public function getPriority()
    {
        return $this->hasOne(Priority::className(), ['id' => 'priority_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProject()
    {
        return $this->hasOne(Project::className(), ['id' => 'project_id']);
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
