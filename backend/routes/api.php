<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use LINE\LINEBot;
use LINE\LINEBot\Constant\HTTPHeader;
use LINE\LINEBot\Event\MessageEvent\TextMessage;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;

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

$httpClient = new CurlHTTPClient($_ENV['LINE_CHANNEL_ACCESS_TOKEN']);
$bot = new LINEBot($httpClient, ['channelSecret' => $_ENV['LINE_CHANNEL_SECRET']]);

// ルート定義を修正 - /api/webhook に変更
Route::post('/webhook', function (Request $request) use ($bot) {
    // 必要最小限のログ記録に留める
    Log::debug('Webhook received');

    $signature = $request->header(HTTPHeader::LINE_SIGNATURE);
    if (empty($signature)) {
        return abort(400);
    }

    try {
        // 署名検証
        $events = $bot->parseEventRequest($request->getContent(), $signature);

        // 先に応答を返す (重要)
        response('OK', 200)->send();

        // 応答を返した後でイベント処理 (タイムアウトを避けるため)
        foreach ($events as $event) {
            if ($event instanceof TextMessage) {
                $bot->replyText($event->getReplyToken(), $event->getText());
            }
            // 他のイベントタイプの処理を追加
        }

        // 応答は既に送信済みなのでここでは何も返さない
        exit;

    } catch (\Exception $e) {
        Log::error('Webhook error: ' . $e->getMessage());
        return abort(500);
    }
});