<?php

namespace api\modules\v1\controllers;

use Yii;
use api\common\controllers\UsersController as BaseUsersController;
use api\modules\v1\models\User;
use yii\helpers\Url;
use yii\web\ServerErrorHttpException;

class UsersController extends BaseUsersController
{

    /**
     * @return array
     */
    public function authExceptedActions()
    {
        return ['create'];
    }

    /**
     * @return aray
     */
    public function actions()
    {
        return [];
    }

    /**
     * @return User
     * @throws ServerErrorHttpException
     */
    public function actionCreate()
    {
        $model = new User();
        $model->registration_date = time();
        if ($model->save()) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(201);
            $id = implode(',', array_values($model->getPrimaryKey(true)));
            $response->getHeaders()->set('Location', Url::toRoute(['view', 'id' => $id], true));
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }

        return $model;
    }

    /**
     * @return User
     */
    public function actionIndex()
    {
        $id = Yii::$app->user->getId();
        $model = User::find()->where(['id' => $id])->one();

        return $model;
    }
}
