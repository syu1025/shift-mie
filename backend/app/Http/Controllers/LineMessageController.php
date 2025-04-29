<?php

namespace App\Http\Controllers;

use App\Models\LineMessage;
use Illuminate\Http\Request;

class LineMessageController extends Controller
{
    public function index()
    {
        // メッセージを新しい順に取得
        $messages = LineMessage::orderBy('created_at', 'desc')->get();

        return view('line-messages.index', compact('messages'));
    }
}
