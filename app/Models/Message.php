<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $fillable = ['user_id', 'body', 'read_at'];

    // A message belongs to either you or Miyah
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}