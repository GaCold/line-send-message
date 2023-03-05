<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;

class LineController extends Controller
{
    public function login()
    {
        return Socialite::driver('line')->with([
            'prompt'                    => 'select_account',
            'bot_prompt'                => 'aggressive',
            'friendship_status_changed' => 'true',
        ])->redirect();
    }

    public function callBack(): \Illuminate\Http\JsonResponse
    {
        $snsInfo = Socialite::driver('line')->stateless()->user();

        return response()->json(
            $snsInfo);
    }

    public function sendMessage(Request $request)
    {
        $body = [
            'to'       => $request->input('uid'),
            'messages' => [
                [
                    'type' => 'text',
                    'text' => 'okie ban e',
                ],
            ],
        ];
        return Http::withToken(config('services.line.channel_token'))
            ->withBody(json_encode($body), 'application/json')
            ->post(config('services.line.push_message_uri'));
    }
}
