<?php

namespace api\common\models;

use \yii\redis\ActiveRecord;

class Video extends ActiveRecord
{
	public function attributes()
	{
		return ['id', 'url', 'duration'],
	}
}
