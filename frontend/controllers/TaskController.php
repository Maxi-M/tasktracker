<?php


namespace frontend\controllers;


use common\models\Task;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;

class TaskController extends Controller
{
    private const ERROR_TITLE = 'Ошибка работы с задачами';
    private const ERROR_PARAMETER_MISSING = 'Обязательный параметр задан некорректно, или отсутствует';
    private const ERROR_ACCESS_DENIED = 'Доступ запрещён';
    private const ERROR_NO_SUCH_TASK = 'Такой задачи не существует';
    private const ERROR_OPERATION_FAILED = 'Операция не удалась.';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $activitiesProvider = new ActiveDataProvider([
            'query' => Task::find()->where(['author_id' => Yii::$app->user->id])
                ->orWhere(['responsible_id' => Yii::$app->user->id]),
            'pagination' => [
                'pageSize' => 15,
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ]
            ]
        ]);
        Url::remember();
        return $this->render('index', ['provider' => $activitiesProvider]);
    }

    /**
     * Выводит форму создания нового задания и отвечает за обработку результата.
     * @return string
     */
    public function actionCreate()
    {
        if (!Yii::$app->user->can('create_task')) {
            return $this->render('task-error', [
                'name' => self::ERROR_TITLE,
                'message' => self::ERROR_ACCESS_DENIED
            ]);
        }
        $model = new Task();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->save();
            return Yii::$app->getResponse()->redirect(['/task/view', 'id' => $model->id]);
        }

        return $this->render('form', ['model' => $model]);
    }

    public function actionView()
    {
        $result = $this->getAllowedModel('view_task');
        if (gettype($result) === Task::className()) {
            return $this->render('view', ['model' => $result ]);
        }
        return $result;
    }

    public function actionUpdate()
    {

    }

    private function getAllowedModel(string $permission)
    {
        if ($id = (int)Yii::$app->request->get('id')) {
            if ($model = Task::findOne($id)) {
                if (Yii::$app->user->can($permission, ['task' => $model])) {
                    return $model;
                }
                return $this->render('task-error', [
                    'name' => self::ERROR_TITLE,
                    'message' => self::ERROR_ACCESS_DENIED
                ]);
            }
            return $this->render('task-error', [
                'name' => self::ERROR_TITLE,
                'message' => self::ERROR_NO_SUCH_TASK
            ]);
        }
        return $this->render('task-error', [
            'name' => self::ERROR_TITLE,
            'message' => self::ERROR_PARAMETER_MISSING
        ]);
    }
}