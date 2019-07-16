<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace api\modules\v1\models;

/**
 * Description of HotelLiked
 *
 * @author zeeshan
 */
class HotelLiked extends \common\models\HotelLiked{
    
    public function fields() {
//        parent::fields();
        return [
//            'id',
//            'user_id',
            'hotel_id',
            'hotel_name' => function($model){
                return $model->hotel->name;
            }
        ];
    }
    
}
