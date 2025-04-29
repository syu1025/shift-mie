<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessLineMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $eventData;

    public function __construct($eventData)
    {
        $this->eventData = $eventData;
    }

    public function handle()
    {
        Log::info('Processing LINE message', ['eventData' => $this->eventData]);

        // ここでメッセージの保存などの処理を行う
        // 例: データベースに保存するなど

        return true;
    }
}
