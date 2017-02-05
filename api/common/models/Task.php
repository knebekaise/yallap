<?php

namespace api\common\models;

use \yii\redis\ActiveRecord;

class Task extends ActiveRecord
{
    const STATUS_NEW = 'new';
    const STATUS_PROCESS = 'process';
    const STATUS_FAIL = 'fail';
    const STATUS_SUCCESS = 'success';
    
	public function attributes()
    {
        return ['id', 'original_file_name', 'file_name', 'start_time', 'end_time', 'status', 'user_id', 'video_id', 'created_date'];
    }

    public function getVideo()
    {
        return $this->hasOne(Video::className(), ['video_id' => 'id']);
    }

    public function beforeValidate()
    {
        if ($this->getIsNewRecord()) {
            $this->status = self::STATUS_NEW;
        }

        return parent::beforeValidate();
    }
}
