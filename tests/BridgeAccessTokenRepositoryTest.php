<?php

use Carbon\Carbon;

class BridgeAccessTokenRepositoryTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function test_access_tokens_can_be_persisted()
    {
        $expiration = Carbon::now();

        $database = Mockery::mock('Illuminate\Database\Connection');

        $database->shouldReceive('table->insert')->once()->andReturnUsing(function ($array) use ($expiration) {
            $this->assertEquals(1, $array['oauth_access_token']);
            $this->assertEquals(2, $array['userid']);
            $this->assertEquals('client-id', $array['oauth_clientid']);
            $this->assertEquals(false, $array['revoked']);
            $this->assertInstanceOf('DateTime', $array['added_at']);
            $this->assertInstanceOf('DateTime', $array['updated_at']);
            $this->assertEquals($expiration, $array['expires_at']);
        });

        $accessToken = new Laravel\Passport\Bridge\AccessToken(2, [new Laravel\Passport\Bridge\Scope('scopes')]);
        $accessToken->setIdentifier(1);
        $accessToken->setExpiryDateTime($expiration);
        $accessToken->setClient(new Laravel\Passport\Bridge\Client('client-id', 'name', 'redirect'));

        $repository = new Laravel\Passport\Bridge\AccessTokenRepository($database);

        $repository->persistNewAccessToken($accessToken);
    }
}
