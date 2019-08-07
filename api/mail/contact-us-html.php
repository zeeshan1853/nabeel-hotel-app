<?php

use yii\helpers\Html;
?>
<div class="password-reset">
    <?php if (isset($request['name'])) { ?>
        <p>Name : <?= Html::encode($request['name']) ?>,</p>
    <?php } ?>
    <?php if (isset($request['email'])) { ?>
        <p>Email : <?= Html::encode($request['email']) ?>,</p>
    <?php } ?>
    <?php if (isset($request['phone'])) { ?>
        <p>Phone : <?= Html::encode($request['phone']) ?>,</p>
    <?php } ?>
    <?php if (isset($request['message'])) { ?>
        <p>Message : <?= Html::encode($request['message']) ?>,</p>
    <?php } ?>
</div>
