<?php

namespace api\common\components;

use yii\rest\ActiveController;

class BaseController extends ActiveController
{
    public function behaviors()
    {
    	$behaviors = parent::behaviors();
    	$behaviors['contentNegotiator'] = [
		    'class' => \yii\filters\ContentNegotiator::className(),
		    'formats' => [
		        'application/json' => \yii\web\Response::FORMAT_JSON,
		    ],
		];

		return $behaviors;
    }
}