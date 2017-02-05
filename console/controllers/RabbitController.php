<?php

namespace console\controllers;

use webtoucher\amqp\controllers\AmqpConsoleController;
use api\common\models\Task;
use api\common\models\Video;
use Yii;
use yii\base\Exception;


class RabbitController extends AmqpConsoleController
{
    public function actionRun() {
        $queueName = 'queueVideoProcessing';
        $channel = $this->channel;
        list($queueName) = $channel->queue_declare($queueName);
        $channel->basic_consume($queueName, '', false, false, false, false, [$this, 'process']);
        while(count($channel->callbacks)) {
            $channel->wait();
        }
        $channel->close();
        $this->connection->close();
    }

    public function process($msg)
    {
        $data = json_decode($msg->body, true);
        if (isset($data['task_id'])) {
            $task = Task::find()->where(['id' => $data['task_id']])->one();
            if (isset($task)) {
                try {
                    $task->status = Task::STATUS_PROCESS;
                    $task->save();
                    $sleep = rand(30, 180);
                    sleep($sleep);
                    $result = round(rand());
                    if ($result) {
                        $task->status = Task::STATUS_SUCCESS;
                        $video = new Video();
                        $video->file_name = $task->file_name;
                        $video->duration = $task->end_time - $task->start_time;
                        if ($video->save()) {
                            $task->video_id = $video->id;     
                        } else {
                            $task->status = Task::STATUS_FAIL;
                        }
                    } else {
                        $task->status = Task::STATUS_FAIL;
                    }
                    $task->save();
                } catch (Exception $e) {
                    $task->status = Task::STATUS_FAIL;
                    $task->save();
                }
            }
        }
    }
}