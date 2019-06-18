<?php

namespace api\components;

use Yii;
use yii\web\Response;

/**
 * Description of CResponse
 *
 * @author zeeshan
 */
class CResponse extends Response {

    public function __construct(array $config = []) {
        parent::__construct($config);
        $this->on(self::EVENT_BEFORE_SEND, function ($event) {
            $this->process($event->sender);
        });
    }

    private function process(Response $response) {

        if ($response->getStatusCode() === 404 && (!is_array($response->data) || $response->format !== $response::FORMAT_JSON)) {
            $response->data = 'Page not found';
            $response->format = $response::FORMAT_JSON;
        }
        $data = [
            'status' => $response->getIsSuccessful() ? 200 : 422,
            'status' => $response->getStatusCode(),
            'data' => $response->data,
            'time' => time(),
            'message' => 'Successful operation',
        ];

        $controller = Yii::$app->controller;
        if ($controller !== null) {
            $data['message'] = $controller->getMessage();
            if ($controller->getErrors() !== null) {
                $data['errors'] = $controller->getErrors();
            }
        }
        if ($data['status'] !== 200) {
            $data['message'] = $this->resolveErrorMsg($data['data']);
//            $data['data'] = (object) [];
            $data['data'] = $data['data'];

            $response->statusText = $data['message'];
        }
        $response->data = $data;
    }

    private function resolveErrorMsg($errorResponse) {
        if (is_string($errorResponse)) {
            return $errorResponse;
        }
        if (array_key_exists('message', $errorResponse)) {
            return $errorResponse['message'];
        } else if (array_column($errorResponse, 'field') !== []) {
            return array_column($errorResponse, 'message')[0];
        }
        return 'Uncaught error structure, contact your administrator :(';
    }

}
