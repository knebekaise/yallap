<?php

namespace api\common\models;

use \yii\redis\ActiveRecord;

class User extends ActiveRecord
{
	public function attributes()
    {
        return ['id', 'registration_date'];
    }

    public function getVideos()
    {
        return $this->hasMany(Video::className(), ['user_id' => 'id']);
    }
}
