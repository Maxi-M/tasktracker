<?php


namespace frontend\controllers;


use common\models\Task;
use common\models\User;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\HttpException;

class TaskController extends Controller
{
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

    public function actionIndex(): string
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
     * @throws ForbiddenHttpException
     */
    public function actionCreate()
    {
        if (!Yii::$app->user->can('create_task')) {
            throw new ForbiddenHttpException('Доступ запрещён');
        }
        $model = new Task();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->save();
            return Yii::$app->getResponse()->redirect(['/task/view', 'id' => $model->id]);
        }

        $templates = Task::find()->where(['is_template' => true, 'author_id' => Yii::$app->user->id])->all();
        $templates = ArrayHelper::map($templates, 'id', 'name');

        return $this->render('form', [
            'model' => $model,
            'templates' => $templates ?? [],
        ]);
    }

    /**
     * Отображает задачу с id, полученным из запроса
     * @param int $id
     * @return string
     * @throws ForbiddenHttpException
     * @throws HttpException
     */
    public function actionView(int $id): string
    {

        if ($model = Task::findOne($id)) {
            if (Yii::$app->user->can('view_task', ['task' => $model])) {
                return $this->render('view', ['model' => $model]);
            }
            throw new ForbiddenHttpException('Доступ запрещён');
        }
        throw new HttpException('404', 'Не удалось найти задачу с указанным id');
    }

    /**
     * Отображает форму редактирования задачи с id, полученным из запроса
     * @param int $id
     * @return string
     * @throws ForbiddenHttpException
     * @throws HttpException
     */
    public function actionUpdate(int $id): string
    {
        if ($model = Task::find()->where(['id' => $id, 'author_id' => Yii::$app->user->id])->one()) {
            if (Yii::$app->user->can('edit_task', ['task' => $model])) {
                if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                    $model->save();
                    if ($urlFrom = Url::previous()) {
                        return Yii::$app->getResponse()->redirect($urlFrom);
                    }
                    return Yii::$app->getResponse()->redirect(['task/view', 'id' => $model->id]);
                }
                return $this->render('form', ['model' => $model, 'templates' =>[]]);
            }
            throw new ForbiddenHttpException('Доступ запрещён');
        }
        throw new HttpException('404', 'Не удалось найти задачу с указанным id');
    }

    /**
     * Удаляет запись по id, полученному из запроса.
     * @param int $id
     * @return string
     * @throws HttpException
     */
    public function actionDelete(int $id): string
    {
        if ($model = Task::find()->where(['id' => $id, 'author_id' => Yii::$app->user->id])->one()) {
            if (Yii::$app->user->can('delete_task', ['activity' => $model])) {
                try {
                    $model->delete();
                } catch (\Throwable $e) {
                    throw new HttpException(500, 'Ошибка удаления задачи');
                }
                if ($urlFrom = Url::previous()) {
                    return Yii::$app->getResponse()->redirect($urlFrom);
                }
                return Yii::$app->getResponse()->redirect('/task/index');
            }
            throw new ForbiddenHttpException('Доступ запрещён');
        }
        throw new HttpException('404', 'Не удалось найти задачу с указанным id');
    }
}