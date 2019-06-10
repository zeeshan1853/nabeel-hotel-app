<?php

namespace api\modules\v1\models;

use yii\helpers\ArrayHelper;


/**
 * Description of User
 *
 * @author zeeshan
 */
class User extends \common\models\User {

    const SCENARIO_LOGIN = 'login';
    const SCENARIO_REGISTER = 'register';

    public function rules() {
        $rules = [
                [['email'], 'required']
        ];
        return ArrayHelper::merge(parent::rules(), $rules);
    }

    public function fields() {
        return[
            'email',
        ];
    }

}
