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
        $range = 10000; // km
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
    }

    public function actionIndex() {
//        $lon = 71.507051;
//        $lat = 30.117629;
        $miles = 395855555.8;
        $search = empty(\Yii::$app->request->get('search')) ? "%" : '%' . \Yii::$app->request->get('search') . '%';
        $miles = empty(\Yii::$app->request->get('radius')) ? 395855555.8 : \Yii::$app->request->get('radius');
        $miles = ($miles == 0 || $miles == '0') ? 395855555.8 : $miles;
        $lat = \Yii::$app->request->get('lat');
        $lon = \Yii::$app->request->get('lng');

        $categoryCondition = empty(\Yii::$app->request->get('category')) ? '' : ' AND hotel.category_id = ' . \Yii::$app->request->get('category');
        $statusCondition = 'AND hotel.status = 1';
        $likeSearch = "hotel.name like '$search' OR hotel.city like '$search' ";

        $connection = Yii::$app->getDb();
        $command = $connection->createCommand(""
                . "SELECT hotel.name,hotel.city,hotel.img,hotel.lat,hotel.lng,hotel.website,hotel.fb_address,hotel.phone_no,hotel.contact_email,hotel.status,hotel.map_id,hotel.city,hotel.street,hotel.video_hotel,category.name as category,category.id as category_id, 
( 3959 * acos( cos( radians('$lat') ) * 
cos( radians( lat ) ) * 
cos( radians( lng ) - 
radians('$lon') ) + 
sin( radians('$lat') ) * 
sin( radians( lat ) ) ) ) 
AS distance FROM hotel left join category on category_id = category.id where ($likeSearch)  $categoryCondition  $statusCondition HAVING distance < '$miles'  ORDER BY distance ASC"
                . "");
//        return $command->getRawSql();
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

    public function actionCategories() {
        return \api\modules\v1\models\Category::find()->all();
    }

    public function actionLikeHotel() {
        $hotel_id = Yii::$app->request->post('hotel_id');
        if (!Hotel::find()->where(['id' => $hotel_id])->count()) {
            $this->commonError('Invalid hotel id');
        }
        $alreadyExist = \api\modules\v1\models\HotelLiked::find()->where(['user_id' => Yii::$app->user->identity->id, 'hotel_id' => $hotel_id])->one();
        if ($alreadyExist) {
            $this->setMessage('UnLiked');
            return $alreadyExist->delete() ? TRUE : FALSE;
        }
        $hotelLiked = new \api\modules\v1\models\HotelLiked();
        $hotelLiked->user_id = Yii::$app->user->identity->id;
        $hotelLiked->hotel_id = $hotel_id;
        $this->setMessage('Liked');
        return $hotelLiked->save();
    }

    public function actionLikedHotels() {
        $hotelLiked = \api\modules\v1\models\HotelLiked::find()->where(['user_id' => Yii::$app->user->identity->id])->all();
        return $hotelLiked;
    }

}
