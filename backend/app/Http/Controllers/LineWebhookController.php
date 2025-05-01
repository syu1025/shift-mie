<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use LINE\Clients\MessagingApi\Api\MessagingApiApi;
use LINE\Clients\MessagingApi\Configuration;
use GuzzleHttp\Client;
use App\Models\LineMessage;
use App\Services\MessageParser;

class LineWebhookController extends Controller
{
    public function message(Request $request) {
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
                $lineUserId = $event['source']['userId'];

                if (empty($messageText)) {
                    Log::warning('Empty message text received');
                    continue;
                }

                // ユーザープロファイルを取得
                $profile = $messagingApi->getProfile($lineUserId);
                $userName = $profile->getDisplayName();
                Log::info('User name: ' . $userName);

                // メッセージから日時情報を解析
                $dateTimeInfo = MessageParser::parseDateTime($messageText);

                // メッセージを保存
                $messageData = [
                    'line_user_id' => $lineUserId,
                    'user_name' => $userName,
                    'message' => $messageText
                ];

                if ($dateTimeInfo) {
                    $messageData['start_time'] = $dateTimeInfo['start_time'];
                    $messageData['end_time'] = $dateTimeInfo['end_time'];
                    $messageData['time_format'] = $dateTimeInfo['format'];
                }

                LineMessage::create($messageData);

                // 日時情報が解析できた場合は、その情報も含めて返信
                $replyMessage = $dateTimeInfo
                    ? "{$userName}さんの予定を登録しました。\n{$dateTimeInfo['start_time']->format('Y/m/d H:i')}～{$dateTimeInfo['end_time']->format('H:i')}"
                    : "{$userName}さん: {$messageText}";

                $messagingApi->replyMessage(
                    new \LINE\Clients\MessagingApi\Model\ReplyMessageRequest([
                        'replyToken' => $replyToken,
                        'messages' => [
                            [
                                'type' => 'text',
                                'text' => $replyMessage
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
    }
}
