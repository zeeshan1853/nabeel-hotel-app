<?php

namespace api\common\models;

use api\modules\v1\models\User;
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
    public $social_token;
    public $device_token;
    public $rememberMe;

    public function rules() {
        return [
                [['email', 'password'], 'required'],
                [['device_token', 'social_token'], 'safe'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
                /**
                 * Social media login Fields rules
                 */
//                [['social_token'], 'required'],
//                ['social_token', 'validateSocialMediaToken'],
        ];
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
        if ($this->scenario === self::SCENARIO_SOCIAL_MEDIA_LOGIN) {
            $this->_user = User::findOne(['social_token' => $this->social_token]);
        } else {
            $this->_user = User::findByEmail($this->email);
        }
        return $this->_user;
    }

}
