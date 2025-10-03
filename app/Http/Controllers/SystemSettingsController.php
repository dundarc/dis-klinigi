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
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Illuminate\Support\Facades\File as FileFacade;

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
        try {
            $timestamp = now()->format('d_m_Y_H_i');
            $fileName = "{$timestamp}_YEDEK_manuel.sql";
            $filePath = storage_path("app/backups/{$fileName}");

            // backups klasörünü oluştur
            FileFacade::ensureDirectoryExists(storage_path('app/backups'));

            // Veritabanı bağlantı bilgilerini al
            $dbConfig = config('database.connections.mysql');
            $host = $dbConfig['host'];
            $database = $dbConfig['database'];
            $username = $dbConfig['username'];
            $password = $dbConfig['password'];

            // PHP ile SQL dump oluştur
            $pdo = new \PDO(
                "mysql:host={$host};dbname={$database};charset=utf8",
                $username,
                $password,
                [\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"]
            );

            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            // Tüm tabloları al (users, migrations, password_reset_tokens, personal_access_tokens, sessions hariç)
            $stmt = $pdo->query("SHOW TABLES");
            $tables = $stmt->fetchAll(\PDO::FETCH_COLUMN);

            $excludedTables = ['users', 'migrations', 'password_reset_tokens', 'personal_access_tokens', 'sessions'];
            $tablesToBackup = array_diff($tables, $excludedTables);

            $sql = "-- Dental Clinic Database Backup\n";
            $sql .= "-- Created: " . now()->format('Y-m-d H:i:s') . "\n";
            $sql .= "-- Database: {$database}\n\n";

            $sql .= "SET FOREIGN_KEY_CHECKS = 0;\n\n";

            foreach ($tablesToBackup as $table) {
                // Tablo yapısını al
                $stmt = $pdo->query("SHOW CREATE TABLE `{$table}`");
                $createTable = $stmt->fetch(\PDO::FETCH_ASSOC);
                $sql .= "-- Table structure for `{$table}`\n";
                $sql .= $createTable['Create Table'] . ";\n\n";

                // Tablo verilerini al
                $stmt = $pdo->query("SELECT * FROM `{$table}`");
                $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                if (!empty($rows)) {
                    $sql .= "-- Data for `{$table}`\n";
                    foreach ($rows as $row) {
                        $columns = array_keys($row);
                        $values = array_map(function ($value) use ($pdo) {
                            if ($value === null) {
                                return 'NULL';
                            }
                            return $pdo->quote($value);
                        }, array_values($row));

                        $sql .= "INSERT INTO `{$table}` (`" . implode('`, `', $columns) . "`) VALUES (" . implode(', ', $values) . ");\n";
                    }
                    $sql .= "\n";
                }
            }

            $sql .= "SET FOREIGN_KEY_CHECKS = 1;\n";

            // Dosyaya yaz
            FileFacade::put($filePath, $sql);

            // Eski yedekleri temizle (en fazla 5 yedek tut)
            $this->cleanupOldBackups();

            return redirect()->route('system.backup')->with([
                'success' => 'Veritabanı yedeklemesi başarıyla oluşturuldu.',
                'backup_file' => $fileName,
                'backup_path' => $filePath
            ]);

        } catch (\Exception $e) {
            return redirect()->route('system.backup')->with('error', 'Yedek oluşturma hatası: ' . $e->getMessage());
        }
    }

    private function cleanupOldBackups()
    {
        $backupDir = storage_path('app/backups');
        if (!FileFacade::exists($backupDir)) {
            return;
        }

        $files = collect(FileFacade::files($backupDir))
            ->sortByDesc(function ($file) {
                return $file->getMTime();
            });

        // İlk 5 yedeği tut, geri kalanları sil
        $filesToDelete = $files->skip(5);
        foreach ($filesToDelete as $file) {
            FileFacade::delete($file->getPathname());
        }
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

    public function destroyData()
    {
        // Silinecek tabloları hesapla
        $tables = DB::select('SHOW TABLES');
        $dbName = DB::getDatabaseName();
        $tablesInDb = "Tables_in_$dbName";

        $tablesToDelete = [];
        $recordCounts = [];

        foreach($tables as $table){
            $tableName = $table->$tablesInDb;
            if ($tableName != 'users' && $tableName != 'migrations' && $tableName != 'password_reset_tokens' && $tableName != 'personal_access_tokens' && $tableName != 'sessions') {
                $tablesToDelete[] = $tableName;
                $recordCounts[$tableName] = DB::table($tableName)->count();
            }
        }

        return view('system.backup.destroy-data', compact('tablesToDelete', 'recordCounts'));
    }

    public function destroyDataConfirm()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $tables = DB::select('SHOW TABLES');
        $dbName = DB::getDatabaseName();
        $tablesInDb = "Tables_in_$dbName";

        $deletedTables = [];
        foreach($tables as $table){
            $tableName = $table->$tablesInDb;
            if ($tableName != 'users' && $tableName != 'migrations' && $tableName != 'password_reset_tokens' && $tableName != 'personal_access_tokens' && $tableName != 'sessions') {
                $recordCount = DB::table($tableName)->count();
                DB::table($tableName)->truncate();
                $deletedTables[] = ['name' => $tableName, 'records' => $recordCount];
            }
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        return redirect()->route('system.backup')->with([
            'success' => 'Veritabanı başarıyla sıfırlandı.',
            'deleted_tables' => $deletedTables
        ]);
    }

    public function uploadBackup(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|mimes:sql|max:51200', // 50MB max
        ]);

        $file = $request->file('backup_file');
        $fileName = time() . '_uploaded_' . $file->getClientOriginalName();

        // Upload dosyasını storage/app/uploads klasörüne kaydet
        $path = $file->storeAs('uploads', $fileName, 'local');

        return redirect()->route('system.backup')->with([
            'success' => 'Yedek dosyası başarıyla yüklendi.',
            'uploaded_file' => $fileName,
            'uploaded_path' => storage_path('app/' . $path)
        ]);
    }

    public function restoreFromFile(Request $request)
    {
        $request->validate([
            'restore_file' => 'required|string',
        ]);

        $fileName = $request->restore_file;

        // Önce uploads klasöründe ara, sonra backups klasöründe
        $filePath = storage_path('app/uploads/' . $fileName);
        if (!FileFacade::exists($filePath)) {
            $filePath = storage_path('app/backups/' . $fileName);
            if (!FileFacade::exists($filePath)) {
                return redirect()->route('system.backup')->with('error', 'Yedek dosyası bulunamadı.');
            }
        }

        try {
            // SQL dosyasını oku
            $sql = FileFacade::get($filePath);

            // Dosyanın SQL olup olmadığını kontrol et
            if (empty($sql) || !preg_match('/^--.*Database Backup/i', $sql)) {
                return redirect()->route('system.backup')->with('error', 'Geçersiz yedek dosyası. Dosya SQL formatında değil.');
            }

            // PDO ile transaction başlat
            $pdo = DB::getPdo();
            $pdo->beginTransaction();

            // Önce tabloları truncate et (sistem tabloları hariç)
            $stmt = $pdo->query("SHOW TABLES");
            $tables = $stmt->fetchAll(\PDO::FETCH_COLUMN);

            $excludedTables = ['users', 'migrations', 'password_reset_tokens', 'personal_access_tokens', 'sessions'];

            foreach ($tables as $table) {
                if (!in_array($table, $excludedTables)) {
                    $pdo->exec("TRUNCATE TABLE `{$table}`");
                }
            }

            // Foreign key kontrolünü devre dışı bırak
            $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");

            // SQL'i satırlara ayır ve sadece geçerli SQL komutlarını işle
            $lines = explode("\n", $sql);
            $currentStatement = '';
            $inStatement = false;

            foreach ($lines as $line) {
                $line = trim($line);

                // Yorum satırlarını atla
                if (preg_match('/^--/', $line) || empty($line)) {
                    continue;
                }

                // SET komutlarını işle
                if (preg_match('/^SET/', $line)) {
                    try {
                        $pdo->exec($line);
                    } catch (\Exception $e) {
                        // SET komutları hatalı olabilir, devam et
                    }
                    continue;
                }

                $currentStatement .= $line . ' ';

                // Statement tamamlandı mı kontrol et (; ile biten)
                if (substr($line, -1) === ';') {
                    $statement = trim($currentStatement);

                    // Boş statement'ları atla
                    if (empty($statement) || $statement === ';') {
                        $currentStatement = '';
                        continue;
                    }

                    try {
                        $pdo->exec($statement);
                    } catch (\Exception $e) {
                        // Eğer duplicate key hatası ise, devam et
                        if (!str_contains($e->getMessage(), 'Duplicate entry') &&
                            !str_contains($e->getMessage(), 'Integrity constraint violation') &&
                            !str_contains($e->getMessage(), 'foreign key constraint')) {
                            // Kritik hata ise logla ama devam et
                            \Log::warning('SQL Restore statement failed: ' . $statement . ' - Error: ' . $e->getMessage());
                        }
                        // Diğer hatalarda devam et
                    }

                    $currentStatement = '';
                }
            }

            // Foreign key kontrolünü tekrar etkinleştir
            $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

            $pdo->commit();

            return redirect()->route('system.backup')->with('success', 'Veritabanı başarıyla geri yüklendi.');
        } catch (\Exception $e) {
            // Hata durumunda rollback yap
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            return redirect()->route('system.backup')->with('error', 'Geri yükleme sırasında hata oluştu: ' . $e->getMessage());
        }
    }

    public function downloadBackup($filename)
    {
        $filePath = storage_path('app/backups/' . $filename);

        if (!FileFacade::exists($filePath)) {
            abort(404, 'Dosya bulunamadı.');
        }

        return response()->download($filePath);
    }

    public function getAvailableBackups()
    {
        $files = [];

        // backups klasöründeki dosyaları al
        $backupDir = storage_path('app/backups');
        if (FileFacade::exists($backupDir)) {
            $backupFiles = collect(FileFacade::files($backupDir))
                ->filter(function ($file) {
                    return $file->getExtension() === 'sql';
                })
                ->map(function ($file) {
                    return [
                        'name' => $file->getFilename(),
                        'size' => $file->getSize(),
                        'created' => $file->getMTime(),
                        'path' => $file->getPathname(),
                        'type' => 'backup'
                    ];
                });
            $files = array_merge($files, $backupFiles->all());
        }

        // uploads klasöründeki dosyaları al
        $uploadDir = storage_path('app/uploads');
        if (FileFacade::exists($uploadDir)) {
            $uploadFiles = collect(FileFacade::files($uploadDir))
                ->filter(function ($file) {
                    return $file->getExtension() === 'sql';
                })
                ->map(function ($file) {
                    return [
                        'name' => $file->getFilename(),
                        'size' => $file->getSize(),
                        'created' => $file->getMTime(),
                        'path' => $file->getPathname(),
                        'type' => 'upload'
                    ];
                });
            $files = array_merge($files, $uploadFiles->all());
        }

        // Tarihe göre sırala (en yeni üstte)
        $files = collect($files)->sortByDesc('created')->values()->all();

        return response()->json($files);
    }
}
