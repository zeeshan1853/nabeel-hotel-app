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
