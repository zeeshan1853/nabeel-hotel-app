<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\services;

use Yii;

/**
 * Description of StorageService
 *
 * @author zeeshan
 */
class StorageService {

    public static $S3_IMAGES_PATH = 'uploads/';
    public static $S3_VIDEO_PATH = 'uploads/videos/';

    public static function uploadToS3($toUpload, $filePath ) {
        try{
        $s3 = Yii::$app->get('s3');
        $result = $s3->upload($toUpload, $filePath);
        } catch (Exception $ex) {
            print_r($ex);
        }
    }

}
