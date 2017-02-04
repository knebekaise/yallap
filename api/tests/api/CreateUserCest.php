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
    public function tryToTest(ApiTester $I)
    {
        $I->wantTo('test user registration');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/users');
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::CREATED);
        $I->seeResponseIsJson();
        $response = json_decode($I->grabResponse());
        $I->wantTo('be authenticated with auth key');
        $I->sendGET('/users');

    }
}
