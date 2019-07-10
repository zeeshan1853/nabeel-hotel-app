<?php

namespace common\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "hotel".
 *
 * @property int $id
 * @property string $name
 * @property int $category_id
 * @property int $created_at
 * @property string $update_at
 *
 * @property Category $category
 */
class Hotel extends ActiveRecord {

    /**
     * {@inheritdoc}
     */
    const STATUS_IN_ACTIVE = 0;
    const STATUS_ACTIVE = 1;

    public static function tableName() {
        return 'hotel';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
                [['name', 'category_id'], 'required'],
                [['category_id', 'created_at', 'status'], 'integer'],
                [['update_at', 'map_id'], 'safe'],
                [['contact_email'], 'safe'],
                [['video_hotel'], 'file', 'extensions' => 'mp4', 'maxSize' => 1024*1024*10,'tooBig'=>'Limit is 10MB'],
                [['lat', 'lng', 'phone_no'], 'string', 'max' => 50],
                [['name', 'contact_email'], 'string', 'max' => 100],
                [['website', 'fb_address'], 'string', 'max' => 200],
                [['street', 'city'], 'string', 'max' => 300],
                [['img'], 'required', 'on' => 'create'],
                [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'fb_address' => 'FB',
            'website' => 'website',
            'phone_no' => 'Phone Number',
            'category_id' => 'Category ID',
            'created_at' => 'Created At',
            'update_at' => 'Update At',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getCategory() {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    public static function getStatusList() {
        return [
            static::STATUS_ACTIVE => 'Active',
            static::STATUS_IN_ACTIVE => 'InActive'
        ];
    }

    public function getStatusString() {
        return $this->status == static::STATUS_ACTIVE ? "Active" : "In-active";
    }

}
