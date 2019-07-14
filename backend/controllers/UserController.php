<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace backend\controllers;

use yii\web\Controller;

/**
 * Description of UserController
 *
 * @author zeeshan
 */
class UserController extends Controller {

    public function actionIndex() {
        $users = \common\models\User::find()->where(['username' => null])->all();
        return $this->render('index', ['users' => $users]);
    }

}
