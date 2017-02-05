<?php

use api\tests\fixtures;

class ProcessTasksCest
{
    public function _before(ApiTester $I)
    {
        $this->loadFixtures($I);
    }

    public function _after(ApiTester $I)
    {
    }

    // tests
    public function testCreateTask(ApiTester $I)
    {
        $user = $I->grabFixture('user', 20);

        $I->wantTo('test video upload');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', 'Bearer ' . $user->auth_key);
        $I->haveHttpHeader('Content-Type', 'multipart/form-data');

        $I->sendPOST(
            '/videos',
            [
                'start_time' => 100,
                'end_time' => 200
            ],
            [
                'video' => codecept_data_dir('files/469806189589743decac204.18134742.mp4')
            ]
        );
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::CREATED);
        $I->seeResponseIsJson();
        $response = json_decode($I->grabResponse(), true);
        $I->assertTrue(isset($response['status']) && ($response['status'] == api\modules\v1\models\Task::STATUS_WAITING));
    }

    public function testListTasks(ApiTester $I)
    {
        $user = $I->grabFixture('user', 30);
        $I->wantTo('test video listing');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', 'Bearer ' . $user->auth_key);
        $I->sendGET('/videos');
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
        $I->seeResponseIsJson();

        $response = json_decode($I->grabResponse(), true);
        $isCurrentUserTasks = true;
        foreach ($response as $task) {
            $usCurrentUserTasks = $isCurrentUserTasks && (isset($task['user_id']) && ($task['user_id'] == $user->id));
        }
        $I->assertTrue($isCurrentUserTasks);
    }

    public function testRestartFailedTask(ApiTester $I)
    {
        $user = $I->grabFixture('user', 30);

        $I->wantTo('test restart failed task');
        $task = $I->grabFixture('task', 30);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', 'Bearer ' . $user->auth_key);
        $I->sendPUT('/videos/'. $task->id);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
        $I->seeResponseIsJson();
        $response = json_decode($I->grabResponse(), true);
        $I->assertTrue(isset($response['status']) && ($response['status'] == api\modules\v1\models\Task::STATUS_WAITING));
    }

    public function testRestartSuccessTask(ApiTester $I)
    {
        $user = $I->grabFixture('user', 30);
        $task = $I->grabFixture('task', 20);

        $I->wantTo('test restart success task');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', 'Bearer ' . $user->auth_key);
        $I->sendPUT('/videos/'. $task->id);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::NOT_MODIFIED);
        $I->seeResponseIsJson();
        $response = json_decode($I->grabResponse(), true);
    }

    protected function loadFixtures(ApiTester $I)
    {
        $I->haveFixtures([
            'user' => [
                'class' => api\tests\fixtures\UserFixture::class,
                'dataFile' => '@api/tests/_data/models/user.php',
            ],
            'task' => [
                'class' => api\tests\fixtures\TaskFixture::class,
                'dataFile' => '@api/tests/_data/models/task.php',
            ],
        ]);
    }
}
