<?php

use common\models\Project;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Task */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Задачи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="task-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить эту задачу?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'description:ntext',
            [
                'label' => 'Является частью проекта',
                'attribute' => 'project.name',
                'visible' => isset($model->project_id) ? true : false,
                'value' => Html::a($model->project->name, Url::to(['/project/view', 'id' => $model->project_id])),
                'format' => 'html'
            ],
            'created_at',
            'updated_at',
            'due_at',
            [
                'attribute' => 'author.username',
                'label' => 'Автор задачи'
            ],
            [
                'attribute' => 'responsible.username',
                'label' => 'Ответсвенный за исполнение'
            ],
            [
                'attribute' => 'status.name',
                'label' => 'Статус'
            ],
            [
                'attribute' => 'priority.name',
                'label' => 'Приоритет'
            ],
            [
                'attribute' => 'is_template',
                'format' => 'boolean',
                'label' => 'Является шаблоном?'
            ],

        ],
    ]) ?>
    <?= \frontend\widgets\chat\Chat::widget(['task_id' => $model->id]) ?>
</div>
