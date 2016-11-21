<?php

namespace Laravel\Passport;

use Illuminate\Database\Eloquent\Model;

class AuthCode extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'oauth_auth_code';

    /**
     * primary key
     *
     * @var integer
     */
    protected $primaryKey = 'oauth_auth_codeid';

    /**
     * The guarded attributes on the model.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'revoked' => 'bool',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'expires_at',
        'added_at',
        'updated_at',
    ];

    /**
     * Get the client that owns the authentication code.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function client()
    {
        return $this->hasMany(Client::class);
    }


    /**
     * Overide Find method for this Model
     *
     * @param $id
     * @param array $columns
     * @return mixed
     */
    public static function find($id, $columns = array('*')) {
        return parent::where('oauth_auth_code', $id)->first();
    }

}
