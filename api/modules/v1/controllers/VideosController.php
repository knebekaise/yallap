<?php

namespace api\modules\v1\controllers;

use Yii;
use api\common\controllers\VideosController as BaseVideosController;
use api\modules\v1\models\Task;
use yii\web\UploadedFile;
use yii\web\ServerErrorHttpException;
use yii\helpers\Url;

class VideosController extends BaseVideosController
{
    /**
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create']);
        return $actions;
    }

    /**
     * @return Task
     */
    public function actionCreate()
    {

        $params = Yii::$app->getRequest()->getBodyParams();
        $model = new Task();
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        $model->video = UploadedFile::getInstanceByName('video');
        $model->user_id = Yii::$app->user->getId();
        if ($model->upload() && $model->save()) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(201);
            $id = implode(',', array_values($model->getPrimaryKey(true)));
            $response->getHeaders()->set('Location', Url::toRoute(['view', 'id' => $id], true));
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }

        return $model;
    }
}
