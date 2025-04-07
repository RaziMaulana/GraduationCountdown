<?php

namespace App\Http\Controllers\Graduation;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Countdown;

class ResultController extends Controller
{
    public function ResultIndex()
    {
        // Cek apakah waktu pengumuman sudah tiba
        $countdown = Countdown::first();

        if (!$countdown) {
            return $this->redirectToCountdown('Waktu pengumuman belum ditetapkan!');
        }

        $currentTime = time();
        $targetTime = $countdown->target_date;

        if ($currentTime < $targetTime) {
            $remaining = $targetTime - $currentTime;
            return $this->redirectToCountdown(
                'Pengumuman akan dibuka dalam: ' .
                $this->formatRemainingTime($remaining)
            );
        }

        $user = Auth::user();

        $displayStatus = $status ?? $user->status ?? 'Tidak Lulus';

        // Handle user photo
        $user = $this->processUserPhoto($user);

        return view('Graduation.results', [
            'status' => $displayStatus,
            'user' => $user
        ]);
    }

    private function redirectToCountdown($message)
    {
        return redirect()
            ->route('kelulusan.index')
            ->with('countdown_error', $message);
    }

    private function formatRemainingTime($seconds)
    {
        $days = floor($seconds / (60 * 60 * 24));
        $hours = floor(($seconds % (60 * 60 * 24)) / (60 * 60));
        $minutes = floor(($seconds % (60 * 60)) / 60);
        $seconds = $seconds % 60;

        return sprintf("%d hari, %02d jam, %02d menit, %02d detik",
                     $days, $hours, $minutes, $seconds);
    }

    private function processUserPhoto($user)
    {
        if ($user && $user->foto_diri) {
            if (Storage::disk('public')->exists($user->foto_diri)) {
                $user->foto_diri = asset('storage/' . $user->foto_diri);
            } elseif (file_exists(public_path($user->foto_diri))) {
                $user->foto_diri = asset($user->foto_diri);
            } elseif (!filter_var($user->foto_diri, FILTER_VALIDATE_URL)) {
                $user->foto_diri = null;
            }
        }
        return $user;
    }
}
