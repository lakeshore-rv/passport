<?php

namespace Laravel\Passport;

use Illuminate\Database\Eloquent\Model;

class PersonalAccessClient extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'oauth_personal_access_client';

    /**
     * PrimaryKey
     *
     * @var integer
     */
    protected $primaryKey = 'oauth_personal_access_clientid';

    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = 'added_at';

    /**
     * The guarded attributes on the model.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get all of the authentication codes for the client.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'oauth_clientid', 'oauth_clientid');
    }


}
