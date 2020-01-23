<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Project */
/* @var $form yii\widgets\ActiveForm */
/* @var array $lists */
?>

<div class="project-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'parent_id')->dropDownList(
        $lists['parent_id'],
        ['prompt' => 'Является частью другого проекта?']
    )->label('Родительский проект') ?>

    <?= $form->field($model, 'leader_id')
        ->dropDownList($lists['leader_id'], ['value' => empty($model->leader_id) ? Yii::$app->user->id : $model->leader_id])
        ->label('Руководитель проекта') ?>

    <?= $form->field($model, 'status_id')
        ->dropDownList($lists['status_id'])
        ->label('Статус') ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
