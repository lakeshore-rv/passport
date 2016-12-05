<?php

namespace Laravel\Passport\Bridge;

use DateTime;
use Laravel\Passport\TokenRepository;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;

class AccessTokenRepository implements AccessTokenRepositoryInterface
{
    use FormatsScopesForStorage;

    /**
     * The database connection.
     *
     * @var \Illuminate\Database\Connection
     */
    protected $tokenRepository;

    /**
     * Create a new repository instance.
     *
     * @param  \Illuminate\Database\Connection  $database
     * @return void
     */
    public function __construct(TokenRepository $tokenRepository)
    {
        $this->tokenRepository = $tokenRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function getNewToken(ClientEntityInterface $clientEntity, array $scopes, $userIdentifier = null)
    {
        return new AccessToken($userIdentifier, $scopes);
    }

    /**
     * {@inheritdoc}
     */
    public function persistNewAccessToken(AccessTokenEntityInterface $accessTokenEntity)
    {

        $this->database->table('oauth_access_token')->insert([
            'oauth_access_token' => $accessTokenEntity->getIdentifier(),
            'userid' => $accessTokenEntity->getUserIdentifier(),
            'oauth_clientid' => $accessTokenEntity->getClient()->getIdentifier(),
            'site_config_userid' => $this->formatScopesForStorage($accessTokenEntity->getScopes()),
            'revoked' => false,
            'added_at' => new DateTime,
            'updated_at' => new DateTime,
            'expires_at' => $accessTokenEntity->getExpiryDateTime(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function revokeAccessToken($tokenId)
    {

        $this->database->table('oauth_access_token')
                    ->where('oauth_access_token', $tokenId)->update(['revoked' => true]);

    }

    /**
     * {@inheritdoc}
     */
    public function isAccessTokenRevoked($tokenId)
    {

        return ! $this->database->table('oauth_access_token')
                    ->where('oauth_access_token', $tokenId)->where('revoked', false)->exists();

    }
}
