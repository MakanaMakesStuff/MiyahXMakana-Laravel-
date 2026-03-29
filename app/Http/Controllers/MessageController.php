<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Events\MessageSent;
use App\Notifications\MessageSentNotification;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MessageController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validate the incoming text
        $request->validate([
            'body' => 'required|string',
        ]);

        // 2. Save the message to the database
        $message = $request->user()->messages()->create([
            'body' => $request->body,
        ]);

        // 3. REVERB: Show it in the other tab instantly
        broadcast(new MessageSent($message))->toOthers();

        // 4. PUSH: Buzz the phone if they aren't looking at the screen
        // Find the partner (Miyah if you are Makana, or vice versa)
        $partner = User::where('id', '!=', auth()->id())->first();
        
        if ($partner) {
            $partner->notify(new MessageSentNotification($message));
        }

        return back(); // Inertia will refresh the page props automatically
    }
}