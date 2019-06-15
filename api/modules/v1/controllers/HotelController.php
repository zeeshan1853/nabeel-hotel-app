<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace api\modules\v1\controllers;

use api\components\CController;
use api\modules\v1\models\Hotel;
use Yii;

/**
 * Description of HotelController
 *
 * @author zeeshan
 */
class HotelController extends CController {

    public function actionTest() {

        $lon = 71.507051;
        $lat = 30.117629;
        $miles = 500;
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand(""
                . "SELECT *, 
( 3959 * acos( cos( radians('$lat') ) * 
cos( radians( lat ) ) * 
cos( radians( lng ) - 
radians('$lon') ) + 
sin( radians('$lat') ) * 
sin( radians( lat ) ) ) ) 
AS distance FROM hotellocation HAVING distance < '$miles' ORDER BY distance ASC LIMIT 0, 5"
                . "");
        return $result = $command->queryAll();
    }

    public function actionIndex() {
        return Hotel::find()->all();
    }

    public function actionDetail($id) {
        $hotel = Hotel::findOne($id);
        if ($hotel) {
            return $hotel;
        }
        $this->commonError('Unable to find the requested Hotel.');
    }

}
