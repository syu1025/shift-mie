<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ShiftController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'line_user_id' => 'required|string',
            'shifts' => 'required|array',
            'shifts.*.date' => 'required|date',
            'shifts.*.type' => 'required|in:time,lecture',
            'shifts.*.start_time' => 'required_if:shifts.*.type,time|nullable|string',
            'shifts.*.end_time' => 'required_if:shifts.*.type,time|nullable|string',
            'shifts.*.lectures' => 'required_if:shifts.*.type,lecture|nullable|array',
        ]);

        try {
            foreach ($validated['shifts'] as $shiftData) {
                Shift::create([
                    'line_user_id' => $validated['line_user_id'],
                    'shift_date' => $shiftData['date'],
                    'shift_type' => $shiftData['type'],
                    'start_time' => $shiftData['start_time'] ?? null,
                    'end_time' => $shiftData['end_time'] ?? null,
                    'lectures' => $shiftData['lectures'] ?? null,
                ]);
            }

            return response()->json(['message' => 'シフトが登録されました'], 201);
        } catch (\Exception $e) {
            Log::error('シフト登録エラー', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'シフトの登録に失敗しました'], 500);
        }
    }

    public function index(Request $request)
    {
        $validated = $request->validate([
            'line_user_id' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        $shifts = Shift::where('line_user_id', $validated['line_user_id'])
            ->whereBetween('shift_date', [$validated['start_date'], $validated['end_date']])
            ->get();

        return response()->json($shifts);
    }
}
