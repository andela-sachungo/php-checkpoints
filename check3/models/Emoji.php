<?php
namespace Stacey\Emoji\Model;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * A model class for working with the emojis table.
 *
 * @author Stacey Achungo
 */

class Emoji extends Eloquent
{
    /**
     * The emoji attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = ['name', 'symbol', 'category'];

    /**
     * An emoji has many keywords.
     *
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function keywords()
    {
        return $this->hasMany('Stacey\Emoji\Model\EmojiKeywords');
    }

    /**
     * An emoji belongs to a user.
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function user()
    {
        return $this->belongsTo('Stacey\Emoji\Model\User');
    }
}
