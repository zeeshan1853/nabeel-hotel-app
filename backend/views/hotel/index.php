<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\HotelSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Restaurants';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hotel-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

    <p>
        <?= Html::a('Create Restaurant', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
            'id',
            'name',
                [
                'attribute' => 'category_id',
                'label' => 'Category',
                'value' => 'category.name'
            ],
                [
                'attribute' => 'img',
                'format' => 'html',
                'label' => 'Image',
                'value' => function ($data) {
                    return empty($data) ? '' : Html::img(yii\helpers\Url::base() . '/uploads/' . $data['img'], ['width' => '70px']);
                },
            ],
            'website',
            'fb_address',
            'phone_no',
            'contact_email',
            'lat',
            'lng',
                [
                'attribute' => 'status',
                'value' => 'statusString'
            ],
                ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>
    <?php Pjax::end(); ?>
</div>
