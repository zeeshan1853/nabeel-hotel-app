<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Hotel */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Restaurants', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="hotel-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ])
        ?>
    </p>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
                [
                'attribute' => 'category',
                'value' => $model->category->name,
            ],
                [
                'attribute' => 'img',
                'format' => 'html',
                'label' => 'Image',
                'value' => function ($data) {
                    return Html::img(yii\helpers\Url::base() . '/uploads/' . $data['img'], ['width' => '70px']);
                },
            ],
                [
                'attribute' => 'city',
                'label' => 'Address',
            ],
            'website',
            'fb_address',
            'phone_no',
            'contact_email',
            'lat',
            'lng',
                [
                'attribute' => 'video_hotel',
                'label' => 'video',
//                'value' => function($data) {
//                    return "<video width = '320' height = '220' controls = ''>".
//                    "<source src = '../uploads/videos/".$data['video_hotel']."' type = 'video/mp4'>".
//                    "</video>";
//                }
            ]
        ],
    ])
    ?>

</div>
