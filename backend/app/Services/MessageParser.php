<?php

namespace App\Services;

use Carbon\Carbon;

class MessageParser
{
    public static function parseDateTime($message)
    {
        // 日付と時間のパターンを定義
        $patterns = [
            // 4月30日15時～20時
            '/(\d{1,2})月(\d{1,2})日(\d{1,2})時～(\d{1,2})時/' => function($matches) {
                $month = $matches[1];
                $day = $matches[2];
                $startHour = $matches[3];
                $endHour = $matches[4];

                $year = Carbon::now()->year;
                $startTime = Carbon::create($year, $month, $day, $startHour);
                $endTime = Carbon::create($year, $month, $day, $endHour);

                return [
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'format' => '日付指定'
                ];
            },
            // 5/1のような形式
            '/(\d{1,2})\/(\d{1,2})/' => function($matches) {
                $month = $matches[1];
                $day = $matches[2];

                $year = Carbon::now()->year;
                $date = Carbon::create($year, $month, $day);

                return [
                    'start_time' => $date->copy()->setHour(0),
                    'end_time' => $date->copy()->setHour(23)->setMinute(59),
                    'format' => '日付のみ'
                ];
            },
            // 5講~7講のような形式
            '/(\d{1,2})講~(\d{1,2})講/' => function($matches) {
                $startPeriod = $matches[1];
                $endPeriod = $matches[2];

                // 講の時間を定義（例：1講は9:00開始）
                $periodTimes = [
                    4 => ['start' => '15:00', 'end' => '16:30'],
                    5 => ['start' => '16:40', 'end' => '18:10'],
                    6 => ['start' => '18:20', 'end' => '19:50'],
                    7 => ['start' => '20:00', 'end' => '21:30'],
                ];

                $today = Carbon::today();
                $startTime = $today->copy()->setHour($periodTimes[$startPeriod]['start']);
                $endTime = $today->copy()->setHour($periodTimes[$endPeriod]['end']);

                return [
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'format' => '講時指定'
                ];
            }
        ];

        foreach ($patterns as $pattern => $callback) {
            if (preg_match($pattern, $message, $matches)) {
                return $callback($matches);
            }
        }

        return null;
    }
}
