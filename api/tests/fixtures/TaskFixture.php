<?php

namespace api\tests\fixtures;

use common\fixtures\redis\ActiveFixture;

class TaskFixture extends ActiveFixture
{
    public $modelClass = 'api\modules\v1\models\Task';
}
