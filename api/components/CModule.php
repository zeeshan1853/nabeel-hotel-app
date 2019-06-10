<?php

namespace api\components;

use Yii;
use yii\base\Module;
/**
 * Description of CModule
 *
 * @author zeeshan
 */
class CModule extends Module {

    public function init() {
        Yii::$app->getUser()->enableSession = false;
        Yii::$app->getUser()->loginUrl = null;

        parent::init();
    }

}
