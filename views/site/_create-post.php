<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Topics */
/* @var $form ActiveForm */
?>
<hr>
<div class="row">
    <div class="col-xs-5 col-xs-offset-4 col-sm-offset-3 col-md-offset-2">
        <?php $form = ActiveForm::begin([
            'action' => '/site/create-post',
        ]); ?>

        <?= $form->field($model, 'topic_id')->label(false)->hiddenInput() ?>

        <?= $form->field($model, 'text')->textarea(['rows' => 5]) ?>

        <div class="form-group">
            <?= Html::submitButton('Добавить', ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
