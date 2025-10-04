<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Checking for invalid emails in users table...\n";

$users = DB::table('users')->whereNotNull('email')->get();
$invalidUsers = [];
$validCount = 0;

foreach($users as $user) {
    $email = trim($user->email);
    $isValid = filter_var($email, FILTER_VALIDATE_EMAIL) !== false &&
               strpos($email, '@') !== false &&
               !preg_match('/\s/', $email) &&
               strlen($email) >= 5 &&
               strlen($email) <= 254;

    if (!$isValid) {
        $invalidUsers[] = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'is_name_like' => preg_match('/[a-zA-Z]/', $email) && !strpos($email, '@')
        ];
    } else {
        $validCount++;
    }
}

echo "Valid emails: $validCount\n";
echo "Invalid emails found: " . count($invalidUsers) . "\n";

if (count($invalidUsers) > 0) {
    echo "\nINVALID EMAILS:\n";
    foreach($invalidUsers as $user) {
        echo "ID: {$user['id']}, Name: {$user['name']}, Email: \"{$user['email']}\", Name-like: " . ($user['is_name_like'] ? 'YES' : 'NO') . "\n";
    }

    echo "\nFixing invalid emails...\n";
    foreach($invalidUsers as $user) {
        $newEmail = "user{$user['id']}@example.com";
        DB::table('users')->where('id', $user['id'])->update(['email' => $newEmail]);
        echo "Fixed user ID {$user['id']}: \"{$user['email']}\" -> \"$newEmail\"\n";
    }
} else {
    echo "No invalid emails found.\n";
}

echo "\nDone.\n";