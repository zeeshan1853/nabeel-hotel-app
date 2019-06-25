<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace api\modules\v1\models;

/**
 * Description of Category
 *
 * @author zeeshan
 */
class Category extends \common\models\Category {

    public function fields() {
        return[
            'id',
            'name'
        ];
    }

}
