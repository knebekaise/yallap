<?php

namespace api\modules\v1\controllers;

use Yii;
use api\common\controllers\VideosController as BaseVideosController;
use api\modules\v1\models\Task;
use yii\web\UploadedFile;
use yii\web\ServerErrorHttpException;
use yii\web\NotFoundHttpException;
use yii\web\HttpException;
use yii\helpers\Url;
use yii\data\ActiveDataProvider;
use webtoucher\amqp\controllers\AmqpTrait;
use PhpAmqpLib\Message\AMQPMessage;

class VideosController extends BaseVideosController
{
    use AmqpTrait;
    /**
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions();
        $actions['index']['prepareDataProvider'] = [$this, 'prepareIndexDataProvider'];
        $actions['view']['findModel'] = [$this, 'findModel'];

        unset($actions['create'], $actions['update']);
        return $actions;
    }

    /**
     * @return Task
     */
    public function actionCreate()
    {

        $params = Yii::$app->getRequest()->getBodyParams();
        $model = new Task();
        $model->setScenario(Task::SCENARIO_CREATE);
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        $model->video = UploadedFile::getInstanceByName('video');
        $model->user_id = Yii::$app->user->getId();
        if ($model->upload() && $model->save()) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(201);
            $id = implode(',', array_values($model->getPrimaryKey(true)));
            $response->getHeaders()->set('Location', Url::toRoute(['view', 'id' => $id], true));
            $this->addTask($model);
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }

        return $model;
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->status === Task::STATUS_FAIL) {
            $model->status = Task::STATUS_WAITING;
            $model->save();
            $this->addTask($model);
        } else {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(304);
        }
        return $model;
    }

    /**
     * @return ActiveDataProvider
     */
    public function prepareIndexDataProvider()
    {
        $page = Yii::$app->getRequest()->getQueryParam('page', 1);
        $perPage = Yii::$app->getRequest()->getQueryParam('per_page', self::DEFAULT_PAGE_SIZE);
        return new ActiveDataProvider([
            'query' => Task::find()->where(['user_id' => Yii::$app->user->getId()]),
            'pagination' => [
                'page' => $page - 1,
                'pageSize' => $perPage,
            ],
        ]);
    }

    /**
     * @param  integer $id
     * @return Task
     * @throws NotFoundHttpException
     */
    public function findModel($id)
    {
        $model = Task::find()
            ->where(['id' => $id])
            ->andWhere(['user_id' => Yii::$app->user->getId()])
            ->one();

        if (isset($model)) {
            return $model;
        } else {
            throw new NotFoundHttpException("Object not found: $id");
        }
    }

    /**
     * @param Task $model
     */
    protected function addTask($model)
    {
        $channel = $this->channel;
        $channel->queue_declare('queueVideoProcessing');
        $msg = new AMQPMessage(json_encode(['task_id' => $model->id]));
        $channel->basic_publish($msg, '', 'queueVideoProcessing');
    }
}
