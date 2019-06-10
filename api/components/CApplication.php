<?php
/**
 * Created by IntelliJ IDEA.
 * User: developer
 * Date: 18/9/17
 * Time: 6:07 PM
 */

namespace api\components;


use yii\web\Application;
use yii\web\User;

class CApplication extends Application
{
    /**
     * Returns the user component.
     * @return Vendor the user component.
     */
    public function getVendor()
    {
        return $this->get('vendor');
    }

    /**
     * @return array
     */
    public function coreComponents()
    {
        return array_merge(parent::coreComponents(), ['vendor' => ['class' => User::className()]]);
    }
}