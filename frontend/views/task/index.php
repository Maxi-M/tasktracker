<?php

use yii\grid\ActionColumn;
use common\models\Task;
use yii\grid\SerialColumn;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */
/* @var $provider ActiveDataProvider */
/* @var $data Task */


echo GridView::widget([
    'dataProvider' => $provider,
    'columns' => [
        ['class' => SerialColumn::class],
        [
            'attribute' => 'id',
        ],
        [
            'attribute' => 'name',
        ],
                [
            'label' => 'Ответственный за исполнение',
            'attribute' => 'user.username',
        ],
        [
            'class' => ActionColumn::class,
        ]
    ]
]);

echo Html::a('Создать новую задачу', Url::to(['/task/create']), ['class' => 'btn btn-success']);