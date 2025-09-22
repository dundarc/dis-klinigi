<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\User;
use App\Enums\UserRole;
use App\Http\Requests\UpdateClinicDetailsRequest;

class SystemSettingsController extends Controller
{
    /**
     * Sistem ayarları ana sayfasını gösterir.
     */
    public function index()
    {
        $clinicDetails = Setting::where('key', 'clinic_details')->first()?->value ?? [];

        $userCounts = User::query()
            ->select('role', \DB::raw('count(*) as total'))
            ->groupBy('role')
            ->pluck('total', 'role');

        return view('system.index', compact('clinicDetails', 'userCounts'));
    }

    /**
     * Klinik detaylarını düzenleme formunu gösterir.
     */
    public function details()
    {
        $clinicDetails = Setting::where('key', 'clinic_details')->first()?->value ?? [];
        return view('system.details', compact('clinicDetails'));
    }

    /**
     * Klinik detaylarını günceller.
     */
    public function updateDetails(UpdateClinicDetailsRequest $request)
    {
        Setting::updateOrCreate(
            ['key' => 'clinic_details'],
            ['value' => $request->validated()]
        );

        return redirect()->route('system.index')->with('success', 'Klinik bilgileri başarıyla güncellendi.');
    }

    /**
     * Sistemdeki tüm kullanıcıları listeler.
     */
    public function users()
    {
        $users = User::orderBy('name')->paginate(15);
        return view('system.users.index', compact('users'));
    }

    // TODO: Diğer metodlar sonraki adımlarda doldurulacak.
    public function createUser() { abort(501); }
    public function storeUser(Request $request) { abort(501); }
    public function editUser(User $user) { abort(501); }
    public function updateUser(Request $request, User $user) { abort(501); }
    public function destroyUser(User $user) { abort(501); }
    public function backup() { abort(501); }
    public function runBackup() { abort(501); }
    public function wipeData() { abort(501); }
}