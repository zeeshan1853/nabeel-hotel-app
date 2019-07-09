<?php

namespace backend\controllers;

use common\models\LoginForm;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * Site controller
 */
class SiteController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                        [
                        'actions' => ['login', 'error', 'script'],
                        'allow' => true,
                    ],
                        [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex() {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin() {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionScript() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $file = fopen("finalFile.csv", "r");
        $headerRow = array_map('trim', array_map('strtolower', fgetcsv($file))); //
        $companyAttributeMapArray = [
            'name' => 'name',
            'category' => 'category_id',
            'website' => 'website',
            'fb_address' => 'fb_address',
            'phone_no' => 'phone_no',
            'contact_email' => 'contact_email',
//            'street' => 'street',
            'city' => 'city',
            'zip' => 'zip',
            'map_id' => 'map_id',
            'lat' => 'lat',
            'lng' => 'lng',
        ];
        if (empty($headerRow)) {
            echo 'empty';
            return;
        }
        $rowNo = 1;
        $models = [];
        while (!feof($file)) {
            $rowNo++;
            $hotelModel = new \common\models\Hotel();
            $dataRow = fgetcsv($file);

            if (!empty($dataRow) && count(array_filter($dataRow))) {
                foreach ($headerRow as $key => $value) {
                    if (isset($companyAttributeMapArray[$value])) {
                        $companyAttributes[$companyAttributeMapArray[$value]] = mb_convert_encoding(trim($dataRow[$key]), 'UTF-8', 'UTF-8');
                    } elseif (!empty($value)) {
                        fclose($file);
                        return ['result' => FALSE, 'msg' => '<b>Invalid field "' . $value . '" at Row ' . $rowNo . ' and Column ' . $key . '</b> <br>'];
                    }
                }
                $hotelModel->attributes = $companyAttributes;
//                if ($this->isHotelExist($hotelModel->name)) {
//                    continue;
//                }
//                echo '<pre>';
//                echo $hotelModel->name;
//                echo '</br>';

                $hotelModel->category_id = $this->getcategoryId($hotelModel->category_id);

//                $lat_lng_place = $this->getLatLng($companyAttributes['street'] . ' ' . $companyAttributes['city']);
//                $hotelModel->lat = (string) $lat_lng_place['lat'];
//                $hotelModel->lng = (string) $lat_lng_place['long'];
//                $hotelModel->map_id = (string) $lat_lng_place['map_id'];

                if (!$hotelModel->validate()) {
                    echo '<pre>';
//                    print_r($lat_lng_place);
                    print_r($hotelModel);
                    print_r($hotelModel->getErrors());
                    fclose($file);
                    return ['result' => FALSE, 'msg' => '<b>Following error occured at row ' . $rowNo . '</b> <br>' . $this->modelErrorsToString($hotelModel->getErrors()), 'row' => json_encode($dataRow)];
                }
                $hotelModel->save();
                echo $rowNo;
                echo '<br>';
            }
//            if ($rowNo == 2) {
//                return 'scripted completed';
//            }
        }
        echo 'Script completed';
    }

    public function getcategoryId($category_name) {
        $categoryFind = \common\models\Category::find()->where(['name' => $category_name])->one();
        if ($categoryFind) {
            return $categoryFind->id;
        }
        $categoryModel = new \common\models\Category();
        $categoryModel->name = $category_name;
        $categoryModel->save();
        $categoryModel->refresh();
        return $categoryModel->id;
    }

    public function isHotelExist($hotel_name) {
        return \common\models\Hotel::find()->where(['name' => $hotel_name])->count() > 0 ? TRUE : FALSE;
    }

    public function getLatLng($address) {
        $google_api_url = 'https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyCuA5x2k5IZqeET8m16UqZ1O4ryPfceqHs';
        $url = $google_api_url . '&address=' . rawurlencode($address);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_TIMEOUT, 25000);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        for ($i = 0; $i < 3; $i++) {
            $json = curl_exec($curl);
            if ($json) {
                $mapData = json_decode($json);
                if ($mapData && $mapData->status == "UNKNOWN_ERROR") {
                    continue;
                } elseif ($mapData && $mapData->status == 'OK') {
                    return ['lat' => $mapData->results[0]->geometry->location->lat, 'long' => $mapData->results[0]->geometry->location->lng, 'map_id' => $mapData->results[0]->place_id];
                } else if ($mapData && $mapData->status == 'OVER_QUERY_LIMIT') {
                    sleep(1);
                    continue;
                } else {
                    return ['error' => $mapData->status];
                }
            }
        }
        return FALSE;
    }

    public function modelErrorsToString($errors) {
        $errorString = '';
        foreach ($errors as $error) {
            $errorString = $errorString . $error[0] . '<br>';
        }
        return $errorString;
    }

}
