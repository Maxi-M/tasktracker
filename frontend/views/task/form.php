<?php

use common\models\Priority;
use common\models\Project;
use common\models\ProjectAssignments;
use common\models\Status;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use common\models\User;

/* @var $this yii\web\View */
/* @var $model common\Models\Task */
/* @var $form ActiveForm */
/* @var $templates array */

?>
<div class="task-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php if (!empty($templates)): ?>
        <?= $form->field($model, 'template_id')->dropDownList($templates, ['prompt' => 'Создать на основании шаблона?']) ?>
    <?php endif; ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'project_id')->dropDownList(ArrayHelper::map(Project::find()
        ->where(['OR',
            ['leader_id'=>Yii::$app->user->id],
            ['IN','id', ArrayHelper::getColumn(ProjectAssignments::find()
                ->where(['user_id' => Yii::$app->user->id])
                ->asArray()->all(), 'project_id')]
        ])
        ->asArray()->all(), 'id', 'name'), ['prompt' => 'Задача относится к проекту?'])
    ?>

    <?= $form->field($model, 'priority_id')->dropDownList(ArrayHelper::map(Priority::find()->asArray()->all(), 'id', 'name')) ?>

    <?= $form->field($model, 'status_id')->dropDownList(ArrayHelper::map(Status::find()->asArray()->all(), 'id', 'name')) ?>

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
        ->dropDownList(ArrayHelper::map(User::findAll(['status' => \common\models\User::STATUS_ACTIVE]), 'id', 'username'),
            ['value' => empty($model->responsible_id) ? Yii::$app->user->id : $model->responsible_id])
        ->label('Исполнитель')
    ?>

    <?= $form->field($model, 'description')->textarea() ?>

    <?= $form->field($model, 'is_template')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div><!-- task-form -->
