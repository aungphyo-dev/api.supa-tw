<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Following extends Model
{
    use HasFactory;

    public function followerUser () : BelongsTo
    {
        return $this->belongsTo(User::class,"follower_id","id");
    }

    public function followingUser () : BelongsTo
    {
        return $this->belongsTo(User::class,"following_id","id");
    }
}
