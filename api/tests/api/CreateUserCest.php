<?php


class CreateUserCest
{
    public function _before(ApiTester $I)
    {
    }

    public function _after(ApiTester $I)
    {
    }

    // tests
    public function testCreateUser(ApiTester $I)
    {
        $I->wantTo('test user registration');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/users');
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::CREATED);
        $I->seeResponseIsJson();
        $response = json_decode($I->grabResponse(), true);
        $I->assertTrue(isset($response['auth_key']));
        $user = $I->grabRecord('api\modules\v1\models\User', ['auth_key' => $response['auth_key']]);
        $I->assertNotNull($user);
        $I->wantTo('get user info');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', 'Bearer ' . $response['auth_key']);
        $I->sendGET('/users');
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
    }
}
