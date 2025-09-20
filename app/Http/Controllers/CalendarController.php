<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\User;
use App\Enums\UserRole;

class CalendarController extends Controller
{
    public function index()
    {
        // Hekim filtresi için tüm hekimleri view'e gönderelim
        $dentists = User::where('role', UserRole::DENTIST)->get();
        return view('calendar.index', compact('dentists'));
    }

    public function events(Request $request)
    {
        // Bu web rotası olduğu için Sanctum token'a gerek yok, session ile auth kontrolü yapılır.
        $user = auth()->user();

        $query = Appointment::query();

        // Hekim ise sadece kendi randevularını görebilir
        if ($user->role === UserRole::DENTIST) {
            $query->where('dentist_id', $user->id);
        }

        // Query string'den gelen filtreleri uygula
        if ($request->filled('dentist_id')) {
            $query->where('dentist_id', $request->dentist_id);
        }
        if ($request->filled('start') && $request->filled('end')) {
            $query->whereBetween('start_at', [$request->start, $request->end]);
        }

        $appointments = $query->get();

        // FullCalendar'ın anlayacağı formata dönüştür
        $events = $appointments->map(function ($appointment) {
            return [
                'id'    => $appointment->id,
                'title' => $appointment->patient->first_name . ' ' . $appointment->patient->last_name,
                'start' => $appointment->start_at->toIso8601String(),
                'end'   => $appointment->end_at->toIso8601String(),
                // Opsiyonel: Hekime göre renklendirme için
                // 'backgroundColor' => '#'.substr(md5($appointment->dentist_id), 0, 6),
                // 'borderColor' => '#'.substr(md5($appointment->dentist_id), 0, 6),
            ];
        });

        return response()->json($events);
    }
}