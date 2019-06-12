<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "ads".
 *
 * @property int $id
 * @property int $img
 * @property int $lat
 * @property int $lng
 * @property int $status
 */
class Ad extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'ads';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
                [['lat', 'lng'], 'required'],
                [['img'], 'required', 'on' => 'create'],
                [['lat', 'lng', 'status'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'img' => 'Img',
            'lat' => 'Lat',
            'lng' => 'Lng',
            'status' => 'Status',
        ];
    }

}
