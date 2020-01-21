<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use common\models\User;

/* @var $this yii\web\View */
/* @var $model common\Models\Task */
/* @var $form ActiveForm */
?>
<div class="task-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'id')->hiddenInput()->label(false, ['style' => 'display:none']) ?>
    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'due_at')->widget("kartik\date\DatePicker", [
        'name' => 'due_at',
        'options' => [
            'placeholder' => 'Выполнить до...',
            'value' => empty($model->due_at) ? '' : Yii::$app->formatter->asDate($model->due_at, 'php:d.m.Y'),
        ],
        'convertFormat' => true,
        'pluginOptions' => [
            'format' => 'php:d.m.Y',
            'todayHighlight' => true,
            'autoClose' => true
        ]
    ]) ?>
    <?= $form->field($model, 'responsible_id')
        ->dropDownList(ArrayHelper::map(User::findAll(['status' => \common\models\User::STATUS_ACTIVE]), 'id', 'username'))
        ->label('Исполнитель')
    ?>


    <?= $form->field($model, 'description')->textarea() ?>


    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div><!-- task-form -->
