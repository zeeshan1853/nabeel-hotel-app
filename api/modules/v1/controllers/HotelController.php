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
use yii\web\UploadedFile;

/**
 * Description of HotelController
 *
 * @author zeeshan
 */
class HotelController extends CController {

    public function actionTest() {
        $range = 1000; // km
        // earth's radius in km = ~6371
        $radius = 6371;
        $lng = 71.507051;
        $lat = 30.117629;
        $maxlat = $lat + rad2deg($range / $radius);
        $minlat = $lat - rad2deg($range / $radius);

        $maxlng = $lng + rad2deg($range / $radius / cos(deg2rad($lat)));
        $minlng = $lng - rad2deg($range / $radius / cos(deg2rad($lat)));

        $q = Hotel::find()->where(['between', 'lat', $minlat, $maxlat])->andWhere(['between', 'lng', $minlng, $maxlng]);

        $q->select(['*', "ROUND((((acos(sin((" . $lat . "*pi()/180)) * sin(('lat'*pi()/180))+cos((" . $lat . "*pi()/180)) * cos((`lat`*pi()/180)) * cos(((" . $lng . "- 'lng')*pi()/180))))*180/pi())*60*1.1515*1.609344),2) as distance"]); //distance in km 
        $q->having('distance <=' . $range);
        $q->orderBy('distance');
        // echo $q->createCommand()->getRawSql();die();
        return $POSlist = $q->all();
        return 'test';
    }

    public function actionIndex() {
        $lon = 71.507051;
        $lat = 30.117629;
        $miles = 1000;
        $search = empty(\Yii::$app->request->get('search')) ? "%" : \Yii::$app->request->get('search');
//        $lat = \Yii::$app->request->get('lat');
//        $lon = \Yii::$app->request->get('lng');

        $connection = Yii::$app->getDb();
        $command = $connection->createCommand(""
                . "SELECT hotel.name,hotel.city,hotel.img,hotel.lat,hotel.lng,hotel.status,category.name as category, 
( 3959 * acos( cos( radians('$lat') ) * 
cos( radians( lat ) ) * 
cos( radians( lng ) - 
radians('$lon') ) + 
sin( radians('$lat') ) * 
sin( radians( lat ) ) ) ) 
AS distance FROM hotel left join category on category_id = category.id WHERE (category.name LIKE '%$search%' OR hotel.name= '$search' OR hotel.city= '$search') AND hotel.status = 1 HAVING distance < '$miles'  ORDER BY distance ASC LIMIT 0, 5"
                . "");
        return $result = $command->queryAll();
    }

    public function actionDetail($id) {
        $hotel = Hotel::findOne($id);
        if ($hotel) {
            return $hotel;
        }
        $this->commonError('Unable to find the requested Hotel.');
    }

    public function actionCreate() {

        $required_params = ['name', 'category_id', 'lat', 'lng'];
        $request = Yii::$app->request->post();
        
        $this->checkRequiredParams($required_params, $request);

        $model = new Hotel();

        $nameOfImage = '';
        if (!(Yii::$app->request->isPost && $model->load(Yii::$app->request->post(), ''))) {
            $this->commonError('Invalid request');
        }
        
        if ($imageFile = UploadedFile::getInstanceByName('img')) {
            $nameOfImage = $imageFile->baseName . '.' . $imageFile->extension;
            $imageFile->saveAs('../backend/web/uploads/' . $nameOfImage);
            $model->img = $nameOfImage;
        }
        
        if (!$model->validate()) {
            return $model;
        }
        if ($model->save()) {
            $this->setMessage('Restaurant added successfully.');
        }
        return $model;
    }

}
