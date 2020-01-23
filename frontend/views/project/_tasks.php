<?php

/* @var $tasksProvider yii\data\ActiveDataProvider */

use common\models\Task;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

echo GridView::widget([
    'dataProvider' => $tasksProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'name',
            'format' => 'html',
            'value' => function ($data) {
                /** @var Task $data */
                return Html::a($data->name, Url::to(['task/view', 'id' => $data->id]));
            }
        ],
        'description:ntext',
        'responsible.username',
        'status.name',

        ['class' => 'yii\grid\ActionColumn'],
    ],
]);