<?php

namespace api\common\models;

use \yii\redis\ActiveRecord;

class Video extends ActiveRecord
{
    /**
     * @return array
     */
    public function attributes()
    {
        return ['id', 'file_name', 'duration'];
    }
}
