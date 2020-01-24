<?php

namespace frontend\controllers;

use common\models\ProjectAssignments;
use common\models\Status;
use common\models\Task;
use common\models\User;
use frontend\events\InformationUpdateEvent;
use frontend\events\ProjectUpdateEvent;
use Yii;
use common\models\Project;
use common\models\ProjectSearch;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProjectController implements the CRUD actions for Project model.
 */
class ProjectController extends Controller
{
    /**
     * {@inheritdoc}
     */
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
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Project models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProjectSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Project model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            'tasksProvider' => $this->findRelatedTasks($id)
        ]);
    }

    /**
     * Creates a new Project model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Project();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->trigger(
                InformationUpdateEvent::EVENT_INFORMATION_CHANGE,
                new ProjectUpdateEvent([
                    'model' => $model,
                    'action' => ProjectUpdateEvent::NEW_RECORD
                ]));
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'lists' => $this->generateDropDownContents()
        ]);
    }


    /**
     * Updates an existing Project model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->trigger(
                InformationUpdateEvent::EVENT_INFORMATION_CHANGE,
                new ProjectUpdateEvent([
                    'model' => $model,
                    'action' => ProjectUpdateEvent::UPDATE_RECORD
                ]));
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'lists' => $this->generateDropDownContents()
        ]);
    }

    /**
     * Deletes an existing Project model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();
        Yii::$app->trigger(
            InformationUpdateEvent::EVENT_INFORMATION_CHANGE,
            new ProjectUpdateEvent([
                'model' => $model,
                'action' => ProjectUpdateEvent::DELETE_RECORD
            ]));

        return $this->redirect(['index']);
    }

    /**
     * Finds the Project model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Project the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Project::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function generateDropDownContents()
    {
        $lists['leader_id'] = $this->generateLeadersList();
        $lists['parent_id'] = $this->generateProjectsList();
        $lists['status_id'] =  $this->generateStatusesList();
        return $lists;
    }

    /**
     * Возвращает список пользователей для назначения в качестве руководителя проекта
     * @return array
     */
    protected function generateLeadersList()
    {
        // Список доступных руководителй проекта
        $leaders = User::find()->andWhere(['status' => User::STATUS_ACTIVE])->all();
        $leaders = ArrayHelper::map($leaders, 'id', 'username');
        return $leaders;
    }

    /**
     * Возвращает массив проектов для использования в выпадающим списке выбора материнского проекта
     * @return array
     */
    protected function generateProjectsList(): array
    {
        // Список проектов, которые можно указать в качестве родительского
        $query = Project::find();
        // Получить id только тех проектов, к которым пользователь имеет отношение
        $dependencies = ProjectAssignments::find()->andWhere(['user_id' => Yii::$app->user->id])->asArray()->all();
        $dependencies = ArrayHelper::getColumn($dependencies, 'project_id');

        // Исключить из списка проект, который редактируется и те, к которым пользователь не причастен
        if ($id = (int)Yii::$app->request->get('id')) {
            $query->where(['AND',
                ['<>', 'id', $id],
                ['OR',
                    ['IN', 'id', $dependencies],
                    ['leader_id' => Yii::$app->user->id]
                ]
            ]);
        } else {
            $query->where([
                'OR', ['IN', 'id', $dependencies], ['leader_id' => Yii::$app->user->id]
            ]);
        }

        $parentProjects = $query->asArray()->all();
        $parentProjects = ArrayHelper::map($parentProjects, 'id', 'name');

        return $parentProjects;
    }

    /**
     * Возвращает список статусов для выпадающего меню
     * @return array
     */
    protected function generateStatusesList(): array
    {
        // Список статусов
        $statuses = Status::find()->asArray()->all();
        $statuses = ArrayHelper::map($statuses, 'id', 'name');
        return $statuses;
    }

    /**
     * @param int $id
     * @return ActiveDataProvider
     */
    protected function findRelatedTasks(int $id): ActiveDataProvider
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Task::find()->where(['project_id' => $id])
        ]);

        return $dataProvider;
    }
}
