<?php

use common\models\Category;
use common\models\Hotel;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model Hotel */
/* @var $form ActiveForm */
?>
<style>
    #hotel-img{
        display: none;
    }
    .labelfile{
        font-size: -webkit-xxx-large
    }
</style>
<div class="hotel-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'img')->fileInput()->label('', ['class' => 'labelfile glyphicon glyphicon-upload']) ?>

    <?php $listCategory = ArrayHelper::map(Category::find()->all(), 'id', 'name'); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'category_id')->dropDownList($listCategory, ['prompt' => 'Select category']) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
