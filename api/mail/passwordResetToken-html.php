<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>
<div class="password-reset">
    <p>Hello <?= Html::encode($user->username) ?>,</p>

    <p>Below is the password reset code. You can add this code in app to reset your password.</p>

    <?= $user->password_reset_token ?>
<!--<p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>-->
</div>
