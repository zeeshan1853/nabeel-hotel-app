<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>
Hello <?= $user->username ?>,
Below is the passowrd reset code. You can add this code in app to reset your password.
<?= $user->password_reset_token?>

<?php// $resetLink ?>
