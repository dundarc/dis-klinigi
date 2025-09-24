<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Support\Facades\Artisan;

class SystemSettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key')->all();
        $userCounts = User::query()
            ->select('role', DB::raw('count(*) as total'))
            ->groupBy('role')
            ->pluck('total', 'role');

        return view('system.index', compact('settings', 'userCounts'));
    }

    public function details()
    {
        $settings = Setting::all()->pluck('value', 'key')->all();
        return view('system.details', compact('settings'));
    }

    public function updateDetails(Request $request)
    {
        $data = $request->except('_token');

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return redirect()->route('system.details')->with('success', 'Klinik detayları başarıyla güncellendi.');
    }

    public function users()
    {
        $users = User::all();
        return view('system.users.index', compact('users'));
    }

    public function createUser()
    {
        $roles = UserRole::cases();
        return view('system.users.create', compact('roles'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', new Enum(UserRole::class)],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('system.users.index')->with('success', 'Kullanıcı başarıyla oluşturuldu.');
    }

    public function editUser(User $user)
    {
        $roles = UserRole::cases();
        return view('system.users.edit', compact('user', 'roles'));
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', new Enum(UserRole::class)],
            'is_active' => ['boolean'],
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'is_active' => $request->has('is_active'),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('system.users.index')->with('success', 'Kullanıcı başarıyla güncellendi.');
    }

    public function destroyUser(User $user)
    {
        $user->delete();
        return redirect()->route('system.users.index')->with('success', 'Kullanıcı başarıyla silindi.');
    }

    public function backup()
    {
        return view('system.backup');
    }

    public function createBackup()
    {
        Artisan::call('backup:run');
        return redirect()->route('system.backup')->with('success', 'Veritabanı yedeklemesi başarıyla oluşturuldu.');
    }

    public function restoreBackup()
    {
        Artisan::call('backup:restore');
        return redirect()->route('system.backup')->with('success', 'Veritabanı başarıyla geri yüklendi.');
    }

    public function deleteData()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $tables = DB::select('SHOW TABLES');
        $dbName = DB::getDatabaseName();
        $tablesInDb = "Tables_in_$dbName";

        foreach($tables as $table){
            if ($table->$tablesInDb != 'users' && $table->$tablesInDb != 'migrations' && $table->$tablesInDb != 'password_reset_tokens' && $table->$tablesInDb != 'personal_access_tokens' && $table->$tablesInDb != 'sessions') {
                DB::table($table->$tablesInDb)->truncate();
            }
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        return redirect()->route('system.backup')->with('success', 'Veritabanı başarıyla sıfırlandı.');
    }
}
