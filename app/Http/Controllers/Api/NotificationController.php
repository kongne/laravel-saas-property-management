<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PushSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function subscribe(Request $request)
    {
        $data = $request->validate([
            'endpoint' => 'required|string',
            'keys.p256dh' => 'nullable|string',
            'keys.auth' => 'nullable|string',
            'content_encoding' => 'nullable|string',
        ]);

        $subscription = PushSubscription::updateOrCreate(
            ['endpoint' => $data['endpoint']],
            [
                'user_id' => Auth::id(),
                'public_key' => $data['keys']['p256dh'] ?? null,
                'auth_token' => $data['keys']['auth'] ?? null,
                'content_encoding' => $data['content_encoding'] ?? 'aesgcm',
                'user_agent' => $request->userAgent(),
            ]
        );

        return response()->json(['data' => $subscription], 201);
    }

    public function unsubscribe(Request $request)
    {
        $request->validate(['endpoint' => 'required|string']);

        PushSubscription::where('endpoint', $request->endpoint)->delete();

        return response()->json(null, 204);
    }

    public function subscriptions()
    {
        return response()->json([
            'data' => PushSubscription::where('user_id', Auth::id())->get(),
        ]);
    }
}
