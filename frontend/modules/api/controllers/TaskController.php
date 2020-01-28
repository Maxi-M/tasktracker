<?php


namespace frontend\modules\api\controllers;


use common\models\Task;
use yii\rest\ActiveController;
use yii\web\ForbiddenHttpException;

class TaskController extends ActiveController
{
    public $modelClass = Task::class;

    public function checkAccess($action, $model = null, $params = [])
    {
        /** @var Task $model */
        if ($action === 'view') {
            if ($model->author_id !== \Yii::$app->user->id) {
                throw new ForbiddenHttpException('Нельзя смотреть задачи, где вы не являетесь автором');
            }
        }
    }

    public function actionAuth()
    {
        return \Yii::$app->user->identity;
    }
}