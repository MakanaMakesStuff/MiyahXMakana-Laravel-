<?php

use Illuminate\Support\Facades\Broadcast;

// This allows both of you to join the same "our-space" room
Broadcast::channel('our-space', function ($user) {
    // If you want to be extra secure, check the .env list again here:
    $allowed = array_map('trim', explode(',', config('app.allowed_emails')));
    
    if (in_array($user->email, $allowed)) {
        return ['id' => $user->id, 'name' => $user->name];
    }
    
    return false;
});