<?php

use yii\db\ActiveRecord;

namespace common\models;

/**
 * This is the model class for table "hotellocation".
 *
 * @property int $id
 * @property int $hotel_id
 * @property int $lat
 * @property int $lng
 */
class Hotellocation extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hotellocation';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['hotel_id', 'lat', 'lng'], 'required'],
            [['hotel_id', 'lat', 'lng'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'hotel_id' => 'Hotel ID',
            'lat' => 'Lat',
            'lng' => 'Lng',
        ];
    }
}
