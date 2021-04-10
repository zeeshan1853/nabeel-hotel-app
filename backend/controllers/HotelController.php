<?php

namespace backend\controllers;

use backend\models\HotelSearch;
use common\models\Hotel;
use common\services\StorageService;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * HotelController implements the CRUD actions for Hotel model.
 */
class HotelController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                        [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Hotel models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new HotelSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = 100;

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Hotel model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Hotel model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Hotel();

        $nameOfImage = '';
//        $model->scenario = 'create';
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {

            if ($imageFile = UploadedFile::getInstance($model, 'img')) {
                $nameOfImage = $imageFile->baseName . "-" . strtotime("now") . '.' . $imageFile->extension;
                $imageFile->saveAs('uploads/' . $nameOfImage);
                $model->img = $nameOfImage;
                StorageService::uploadToS3('uploads/' . $nameOfImage, StorageService::$S3_IMAGES_PATH . $nameOfImage);
            }
            if ($model->save()) {
                return $this->redirect('index');
            }
        }


        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing Hotel model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $old_video = $model->video_hotel;
        $img = $model->img;
        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post())) {

                $imageFile = UploadedFile::getInstance($model, 'img');
                $videoFile = UploadedFile::getInstance($model, 'video_hotel');
                if ($imageFile) {
                    $nameOfImage = $imageFile->baseName . '.' . $imageFile->extension;
                    $imageFile->saveAs('uploads/' . $nameOfImage);
                    $img = $nameOfImage;
                    StorageService::uploadToS3('uploads/' . $nameOfImage, StorageService::$S3_IMAGES_PATH . $nameOfImage);
                } else if (empty($model->image_name)) {
                    $img = NULL;
                }
                $model->img = $img;
                $videoName = "video_" . rand() . '_';
                if ($videoFile) {
                    $videoFullName = $videoName . $videoFile->name;
                    if (!$videoFile->saveAs('uploads/videos/' . $videoFullName)) {
                        
                    }
                    StorageService::uploadToS3('uploads/videos' . $videoFullName, StorageService::$S3_VIDEO_PATH . $videoFullName);
                    $model->video_hotel = $videoFullName;
                } else {
                    if (empty($model->video_string)) {
                        $model->video_hotel = null;
                    } else {
                        $model->video_hotel = $old_video;
                    }
                }
                if ($model->save()) {
                    return $this->redirect('index');
                }
            }
        }


        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Hotel model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Hotel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Hotel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Hotel::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
