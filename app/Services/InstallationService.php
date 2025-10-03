<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use App\Models\Setting;
use App\Enums\UserRole;

class InstallationService
{
    public function checkRequirements()
    {
        return [
            'php_version' => [
                'name' => 'PHP Version (>= 8.2)',
                'result' => version_compare(PHP_VERSION, '8.2.0', '>='),
                'current' => PHP_VERSION
            ],
            'pdo' => [
                'name' => 'PDO Extension',
                'result' => extension_loaded('pdo'),
                'current' => extension_loaded('pdo') ? 'Enabled' : 'Disabled'
            ],
            'mbstring' => [
                'name' => 'Mbstring Extension',
                'result' => extension_loaded('mbstring'),
                'current' => extension_loaded('mbstring') ? 'Enabled' : 'Disabled'
            ],
            'openssl' => [
                'name' => 'OpenSSL Extension',
                'result' => extension_loaded('openssl'),
                'current' => extension_loaded('openssl') ? 'Enabled' : 'Disabled'
            ],
            'fileinfo' => [
                'name' => 'Fileinfo Extension',
                'result' => extension_loaded('fileinfo'),
                'current' => extension_loaded('fileinfo') ? 'Enabled' : 'Disabled'
            ],
            'storage' => [
                'name' => 'Storage Directory Writable',
                'result' => is_writable(storage_path()),
                'current' => is_writable(storage_path()) ? 'Writable' : 'Not Writable'
            ],
            'bootstrap' => [
                'name' => 'Bootstrap Cache Directory Writable',
                'result' => is_writable(base_path('bootstrap/cache')),
                'current' => is_writable(base_path('bootstrap/cache')) ? 'Writable' : 'Not Writable'
            ],
        ];
    }

    public function setupDatabase($config)
    {
        // Test database connection
        try {
            $this->testDatabaseConnection($config);
        } catch (\Exception $e) {
            throw new \Exception('Database connection failed: ' . $e->getMessage());
        }

        // Update .env file
        $this->updateEnvironmentFile($config);

        // Run migrations
        try {
            Artisan::call('migrate:fresh', ['--force' => true]);
        } catch (\Exception $e) {
            throw new \Exception('Migration failed: ' . $e->getMessage());
        }
    }

    public function setupClinic($data)
    {
        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
    }

    public function createAdmin($data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'role' => UserRole::ADMIN
        ]);
    }

    public function markAsInstalled()
    {
        File::put(storage_path('installed'), 'Installation completed on ' . date('Y-m-d H:i:s'));
    }

    protected function testDatabaseConnection($config)
    {
        $connection = @mysqli_connect(
            $config['hostname'],
            $config['username'],
            $config['password'],
            $config['database']
        );

        if (!$connection) {
            throw new \Exception(mysqli_connect_error());
        }

        mysqli_close($connection);
    }

    protected function updateEnvironmentFile($config)
    {
        $path = base_path('.env');

        if (File::exists($path)) {
            $content = File::get($path);

            $content = preg_replace('/DB_HOST=.*/', 'DB_HOST=' . $config['hostname'], $content);
            $content = preg_replace('/DB_DATABASE=.*/', 'DB_DATABASE=' . $config['database'], $content);
            $content = preg_replace('/DB_USERNAME=.*/', 'DB_USERNAME=' . $config['username'], $content);
            $content = preg_replace('/DB_PASSWORD=.*/', 'DB_PASSWORD=' . $config['password'], $content);

            File::put($path, $content);
        }
    }
}