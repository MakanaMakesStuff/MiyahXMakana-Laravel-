<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use App\Models\User; // Added this
use App\Notifications\MessageSentNotification; // Added this
use Illuminate\Http\Request;
use Inertia\Inertia;

class AppController extends Controller
{
    public function index()
    {
        $envEmails = env('ALLOWED_EMAILS', '');
        $allowedEmails = array_map('trim', explode(',', $envEmails));

        if (!in_array(auth()->user()->email, $allowedEmails)) {
            return redirect()->route('home')->with('error', 'You are not authorized.');
        }

        $messages = Message::with('user')
            ->latest()
            ->take(50)
            ->get()
            ->reverse()
            ->values();

        return Inertia::render('app', [
            'messages' => $messages,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'body' => 'required|string|max:2000',
        ]);

        $message = $request->user()->messages()->create($validated);

        // 1. Live Chat Broadcast (Reverb)
        broadcast(new MessageSent($message->load('user')))->toOthers();

        // 2. Mobile Notification (Web Push)
        // We find the person who is NOT the current sender
        $partner = User::where('id', '!=', auth()->id())->first();

        if ($partner) {
            // This sends the "Postcard" to the Push Service
            $partner->notify(new MessageSentNotification($message));
        }

        return back();
    }
}