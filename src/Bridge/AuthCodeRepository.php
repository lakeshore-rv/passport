<?php

namespace Laravel\Passport\Bridge;

use DateTime;
use Illuminate\Database\Connection;
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;

class AuthCodeRepository implements AuthCodeRepositoryInterface
{
    use FormatsScopesForStorage;

    /**
     * The database connection.
     *
     * @var \Illuminate\Database\Connection
     */
    protected $database;

    /**
     * Create a new repository instance.
     *
     * @param  \Illuminate\Database\Connection  $database
     * @return void
     */
    public function __construct(Connection $database)
    {
        $this->database = $database;
    }

    /**
     * {@inheritdoc}
     */
    public function getNewAuthCode()
    {
        return new AuthCode;
    }

    /**
     * {@inheritdoc}
     */
    public function persistNewAuthCode(AuthCodeEntityInterface $authCodeEntity)
    {
        $this->database->table('oauth_auth_code')->insert([
            'oauth_auth_code' => $authCodeEntity->getIdentifier(),
            'userid' => $authCodeEntity->getUserIdentifier(),
            'oauth_clientid' => $authCodeEntity->getClient()->getIdentifier(),
            'revoked' => false,
            'added_at' => new DateTime,
            'updated_at' => new DateTime,
            'expires_at' => $authCodeEntity->getExpiryDateTime(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function revokeAuthCode($codeId)
    {
        $this->database->table('oauth_auth_code')
                    ->where('oauth_auth_code', $codeId)->update(['revoked' => true]);
    }

    /**
     * {@inheritdoc}
     */
    public function isAuthCodeRevoked($codeId)
    {
        return $this->database->table('oauth_auth_code')
                    ->where('oauth_auth_code', $codeId)->where('revoked', 1)->exists();
    }
}
