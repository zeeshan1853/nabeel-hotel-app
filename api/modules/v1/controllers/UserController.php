<?php

namespace api\modules\v1\controllers;

use api\common\models\LoginForm;
use api\components\CController;
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
        $request = Yii::$app->request->post();
        $model = new LoginForm();
        $model->load($request, '');
        return $model;
    }

    public function actionRegister() {

        $required_params = ['email', 'password'];
        $request = Yii::$app->request->post();

        $this->checkRequiredParams($required_params, $request);

        $user = new User();
        $user->email = $request['email'];
        $user->generateAuthKey();
        $user->generateAccessToken();
        $user->setPassword($request['password']);

        if ($user->save()) {
            $this->setMessage('User created successfully');
            return $user;
        }
        $this->setMessage('Unable to register user.');
        return FALSE;
    }

    public function actionForgetPassword() {

        $required_params = ['email_address'];
        $request = Yii::$app->getRequest()->post();
        $this->checkRequiredParams($required_params, $request);

        $model = User::findOne(['email' => $request['email_address']]);
        if ($model === null) {
            $this->commonError('EMAIL_NOT_FOUND');
        }

        // Change password and updated access token.
        $new_password = Yii::$app->security->generateRandomKey();
        $model->setPassword($new_password);
        $model->generateAccessToken();
        $model->save();

        #TODO: Send an email with link to reset password
        $model->forgotPasswordMail(['password' => $new_password]);

        $this->setMessage('Password sent successfully at you email address.');
        return [];
    }

}
