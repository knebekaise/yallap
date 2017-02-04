<?php

namespace api\common\controllers;

use api\common\components\BaseController;

class UsersController extends BaseController
{
	public $modelClass = \api\common\models\User::class;
}
