<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace api\components;

use Throwable;
use yii\web\HttpException;

/**
 * Description of ApiException
 *
 * @author zeeshan
 */
class ApiException extends HttpException {

    public $statusCode;

    public function __construct($message = '', $code = 0, Throwable $previous = null) {
        parent::__construct($code, $message, $previous);
    }

}
