<?php

namespace api\common\models;

use api\modules\v1\models\User;
use Yii;
use yii\base\Model;

/**
 * Description of LoginForm
 *
 * @author zeeshan
 */
class LoginForm extends Model {

    const SCENARIO_LOGIN_DEFAULT = 'login';
    const SCENARIO_SOCIAL_MEDIA_LOGIN = 'social-media-login';

    public $email;
    public $password;
    private $_user;

//    public $rememberMe;

    public function rules() {
        return [
                [['email', 'password'], 'required'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
                /**
                 * Social media login Fields rules
                 */
//                [['social_token'], 'required'],
//                ['social_token', 'validateSocialMediaToken'],
        ];
    }

    public function login() {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser());
        } else {
            return false;
        }
    }

    public function validatePassword($attribute, $params) {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    protected function getUser() {

        $this->_user = User::findByEmail($this->email);

        return $this->_user;
    }

}
