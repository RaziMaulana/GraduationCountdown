<?php

namespace App\Http\Controllers\Admin;

use App\Models\Countdown;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TimeSettingController extends Controller
{
    public function TimeSettingIndex()
    {
        if (!Auth::check() || !Auth::user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        $countdown = Countdown::first();
        return view('admin.TimeSetting', ['countdown' => $countdown]);
    }

    public function getCountdown()
    {
        $countdown = Countdown::first();

        if (!$countdown) {
            return response()->json([
                'error' => 'Countdown data not found'
            ], 404);
        }

        // Return timestamp dalam milidetik untuk JavaScript
        return response()->json([
            'targetDate' => $countdown->target_date * 1000 // Convert to milliseconds
        ]);
    }

    public function setCountdown(Request $request)
    {
        $request->validate([
            'targetDate' => 'required|date'
        ]);

        try {
            $countdown = Countdown::firstOrNew();

            // Konversi datetime string ke UNIX timestamp (dalam detik)
            $timestamp = strtotime($request->targetDate);

            if ($timestamp === false) {
                throw new \Exception('Invalid date format');
            }

            $countdown->target_date = $timestamp;
            $countdown->save();

            return response()->json([
                'success' => true,
                'timestamp' => $timestamp,
                'human_readable' => date('Y-m-d H:i:s', $timestamp)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
