<?php
namespace Stacey\Emoji\Model;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * A model class for working with the users table.
 *
 * @author Stacey Achungo
 */

class User extends Eloquent
{
    /**
     * The user attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = ['username', 'password', 'name', 'token'];

     /**
     * The attribute that should be hidden for arrays or JSON representation.
     *
     * @var array
     */

    protected $hidden = ['password'];

    /**
     * Get the user with the unique username.
     *
     * @return Illuminate\Database\Eloquent\Collection
     */

    public static function getUser($username)
    {
        return static::where('username', $username)->first();
    }

    /**
     * A user has many emojis.
     *
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function emojis()
    {
        return $this->hasMany('Stacey\Emoji\Model\Emoji');
    }
}
