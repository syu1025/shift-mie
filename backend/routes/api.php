<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use LINE\Clients\MessagingApi\Api\MessagingApiApi;
use LINE\Clients\MessagingApi\Configuration;
use GuzzleHttp\Client;
use App\Models\LineMessage; // LineMessageモデルを追加

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// LINEボット設定を修正
Route::post('/webhook', function (Request $request) {
    Log::debug('Webhook received', ['request' => $request->all()]);

    try {
        $client = new Client();
        $config = new Configuration();
        $config->setAccessToken(env('LINE_CHANNEL_ACCESS_TOKEN'));
        $messagingApi = new MessagingApiApi(
            client: $client,
            config: $config,
        );

        $events = $request->input('events');
        if (empty($events)) {
            Log::warning('No events in webhook request');
            return response()->json(['message' => 'No events'], 200);
        }

        foreach ($events as $event) {
            if ($event['type'] !== 'message' || $event['message']['type'] !== 'text') {
                continue;
            }

            $replyToken = $event['replyToken'];
            $messageText = $event['message']['text'];

            if (empty($messageText)) {
                Log::warning('Empty message text received');
                continue;
            }

            $messagingApi->replyMessage(
                new \LINE\Clients\MessagingApi\Model\ReplyMessageRequest([
                    'replyToken' => $replyToken,
                    'messages' => [
                        [
                            'type' => 'text',
                            'text' => $messageText
                        ]
                    ]
                ])
            );
        }

        return response()->json(['message' => 'OK'], 200);
    } catch (\Exception $e) {
        Log::error('Webhook error: ' . $e->getMessage(), [
            'exception' => $e,
            'request' => $request->all()
        ]);
        return response()->json(['message' => 'Internal Server Error'], 500);
    }
});
