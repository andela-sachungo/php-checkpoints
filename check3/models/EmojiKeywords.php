<?php
namespace Stacey\Emoji\Model;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * A model class for working with the emoji_keywords table.
 *
 * @author Stacey Achungo
 */

class EmojiKeywords extends Eloquent
{
    /**
     * The keyword table attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = ['emoji_id', 'keyword'];

    /**
     * A keyword belongs to an emoji.
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function emojis()
    {
        return belongsTo('Stacey\Emoji\Model\User', 'emoji_id');
    }
}
