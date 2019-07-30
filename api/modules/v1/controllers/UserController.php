<?php

namespace api\modules\v1\controllers;

use api\common\models\LoginForm;
use api\components\CController;
use api\modules\v1\models\PasswordResetRequestForm;
use api\modules\v1\models\User;
use Yii;

/**
 * Description of UserController
 *
 * @author zeeshan
 */
class UserController extends CController {

    public function actionIndex() {
        return ['variable_name' => 'Variable value'];
    }

    public function actionTest() {
        $this->commonError('eroororro');
        $this->setMessage('message');
    }

    public function actionLogin() {

        $required_params = ['email', 'password'];
        $request = Yii::$app->request->post();

        $this->checkRequiredParams($required_params, $request);

        $model = new LoginForm();
        $model->load($request, '');

        if (!$model->login()) {
            $this->invalidLogin();
        }
        $this->setMessage('Logged in successfully');
        return User::findOne(\Yii::$app->user->identity->id);
    }

    public function actionRegister() {

        $required_params = ['email', 'password'];
        $request = Yii::$app->request->post();

        $this->checkRequiredParams($required_params, $request);

        $user = new User();
        $user->email = $request['email'];
        $user->generateAuthKey();
        $user->setPassword($request['password']);

        if ($user->save()) {
            $this->setMessage('User created successfully');
            return $user;
        }
        return $user;
    }

    public function actionForgetPassword() {
        $required_params = ['email'];
        $request = Yii::$app->getRequest()->post();
        $this->checkRequiredParams($required_params, $request);

        $model = new PasswordResetRequestForm();

        $model->load($request, '');
        if (!$model->validate()) {
            return $model;
        }

        if ($model->sendEmail()) {
            $this->setMessage('Password sent successfully at your email address.');
            return true;
        }
        $this->setMessage('Unable to send email at this time.');
        return false;
    }

    public function actionResetPassword() {
        $required_params = ['resetToken', 'password'];
        $request = Yii::$app->getRequest()->post();
        $this->checkRequiredParams($required_params, $request);
        $user = User::findByPasswordResetToken($request['resetToken']);
        if ($user) {
            $user->setPassword($request['password']);
            $user->password_reset_token = NULL;
            $user->save(FALSE);
            $this->setMessage('Password has been updated.');
            return true;
        }
        $this->setMessage('Unable to find this reset token.');
        $this->setStatus('104');
        return FALSE;
    }

    public function actionIshtehar() {
        $lat = \Yii::$app->request->get('lat');
        $lon = \Yii::$app->request->get('lng');

        $connection = Yii::$app->getDb();
        $command = $connection->createCommand(""
                . "SELECT *, 
( 3959 * acos( cos( radians('$lat') ) * 
cos( radians( lat ) ) * 
cos( radians( lng ) - 
radians('$lon') ) + 
sin( radians('$lat') ) * 
sin( radians( lat ) ) ) ) 
AS distance FROM ads ORDER BY distance ASC LIMIT 1"
                . "");
        return $result = $command->queryAll();
    }

}
