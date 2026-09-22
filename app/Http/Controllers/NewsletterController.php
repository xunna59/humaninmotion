<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $ip = $request->ip();
        if (RateLimiter::tooManyAttempts('newsletter:' . $ip, 5)) {
            return back()->with('newsletter_error', 'Please try again later.');
        }
        RateLimiter::hit('newsletter:' . $ip, 3600);

        $subscriber = NewsletterSubscriber::updateOrCreate(
            ['email' => strtolower($data['email'])],
            [
                'is_subscribed' => true,
                'unsubscribed_at' => null,
                'subscribed_at' => now(),
                'source' => $request->input('source', 'footer'),
            ]
        );

        if (! $subscriber->token) {
            $subscriber->update(['token' => Str::random(64)]);
        }

        return back()->with('newsletter_ok', 'Welcome to Human In Motion.');
    }
}