<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace api\modules\v1\controllers;

use api\components\CController;
use Yii;

/**
 * Description of SiteController
 *
 * @author zeeshan
 */
class SiteController extends CController {

    public function actionIndex() {
        $required_params = ['email', 'message'];
        $request = Yii::$app->request->post();

        $this->checkRequiredParams($required_params, $request);
        
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'contact-us-html'],
                ['request' => $request]
            )
            ->setFrom($request['email'])
            ->setTo(Yii::$app->params['adminEmail'])
            ->setSubject('Contact Us')
            ->send();
        
    }

}
