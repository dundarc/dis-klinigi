<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

try {
    // Check if columns exist
    $columns = Schema::getColumnListing('consents');

    if (!in_array('snapshot', $columns)) {
        Schema::table('consents', function ($table) {
            $table->json('snapshot')->nullable()->after('user_agent');
        });
        echo "Added snapshot column\n";
    } else {
        echo "snapshot column already exists\n";
    }

    if (!in_array('hash', $columns)) {
        Schema::table('consents', function ($table) {
            $table->string('hash')->nullable()->after('snapshot');
        });
        echo "Added hash column\n";
    } else {
        echo "hash column already exists\n";
    }

    echo "Done\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}