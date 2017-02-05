<?php

use api\tests\fixtures;

class ProcessTasksCest
{
    public function _before(ApiTester $I)
    {
    }

    public function _after(ApiTester $I)
    {
    }

    // tests
    public function testCreateTask(ApiTester $I)
    {
        $I->haveFixtures([
            'user' => [
                'class' => api\tests\fixtures\UserFixture::class,
                'dataFile' => '@api/tests/_data/models/user.php',
             ],
        ]);
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
        $I->assertTrue(isset($response['status']) && ($response['status'] == api\modules\v1\models\Task::STATUS_NEW));
    }
}
