<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace api\components;

use yii\base\Action;
use yii\filters\auth\HttpBearerAuth;
use yii\base\Module;
/**
 * Description of CHttpBearerAuth
 *
 * @author zeeshan
 */
class CHttpBearerAuth extends HttpBearerAuth{

    /**
     * @param Action $action
     * @return string
     */
    protected function getActionId($action) {

        if ($this->owner->module instanceof Module) {
            $mid = $this->owner->module->getUniqueId();
            $id = $action->getUniqueId();

            if ($mid !== '' && strpos($id, $mid) === 0) {
                $id = substr($id, strlen($mid) + 1);
            }
        } else {
            $id = $action->id;
        }
        return $id;
    }

}
