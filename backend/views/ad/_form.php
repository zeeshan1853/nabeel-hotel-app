<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Ad */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
    .labelfile{
        font-size: -webkit-xxx-large
    }
</style>
<div class="a-form">
    <img src="<?= yii\helpers\Url::base() . '/uploads/' . $model->img ?>">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'img')->fileInput()->label('', ['class' => 'labelfile glyphicon glyphicon-upload']) ?>

    <?= $form->field($model, 'lat')->textInput() ?>

    <?= $form->field($model, 'lng')->textInput() ?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
