<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Enums\UserRole;
use App\Enums\AppointmentStatus;

class CalendarController extends Controller
{
    /**
     * Takvim sayfasını, filtreler için gerekli verilerle birlikte görüntüler.
     */
    public function index()
    {
        // Filtreler için hekim listesini al
        $dentists = User::where('role', UserRole::DENTIST)->orderBy('name')->get(['id', 'name']);
        
        // Filtreler için durum listesini al
        $statuses = AppointmentStatus::cases();

        // Modal'daki hasta seçimi için (şimdilik basit bir liste)
        $patients = \App\Models\Patient::orderBy('first_name')->get(['id', 'first_name', 'last_name']);

        return view('calendar.index', compact('dentists', 'statuses', 'patients'));
    }
}

