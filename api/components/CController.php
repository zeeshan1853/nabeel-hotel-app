<?php

namespace api\components;

use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\rest\Controller;
use yii\web\UnauthorizedHttpException;

/**
 * @author zeeshan
 */
class CController extends Controller {

    private $message;
    private $error;
    private $status;

    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CHttpBearerAuth::class,
            'optional' => [
                'user/test',
                'user/login',
                'user/register',
                'user/forget-password',
            ],
        ];
        return $behaviors;
    }

    public function beforeAction($action) {
//        $header = Yii::$app->getRequest()->getHeaders();
        return parent::beforeAction($action);
    }

    public function setMessage($msg) {
        $this->message = $msg;
    }

    public function getMessage() {
        return $this->message;
    }

    public function setErrors($err) {
        $this->error = $err;
    }

    public function getErrors() {
        return $this->error;
    }

    public function setStatus($status = 404) {
        $this->status = $status;
    }

    public function getStatus() {
        return $this->status;
    }

    public function commonError($error = null) {
        if ($error === null) {
            $error = 'Expectation failed';
        }
        throw new ApiException($error, 417);
    }

    protected function checkRequiredParams($required, $request) {
        foreach ($required as $param) {
            if (!array_key_exists($param, $request)) {
                $this->paramMissing($param);
            }
        }
    }

    protected function paramMissing($param = null) {
        throw new ApiException('Required param missing ' . $param, 460);
    }

    /**
     * @throws UnauthorizedHttpException
     */
    protected function unauthorizedAccess() {
        throw new UnauthorizedHttpException('Your request was made with invalid credentials.');
    }

}
