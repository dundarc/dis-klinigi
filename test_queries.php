<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== VERİTABANI İLİŞKİ TESTLERİ ===\n\n";

echo "1. Hastası olmayan randevu var mı?\n";
$count = \App\Models\Appointment::whereDoesntHave('patient')->count();
echo "Adet: $count\n";
if ($count > 0) {
    $items = \App\Models\Appointment::whereDoesntHave('patient')->take(3)->get();
    foreach ($items as $item) {
        echo "- ID: {$item->id}, Patient ID: {$item->patient_id}\n";
    }
}
echo "\n";

echo "2. Completed randevusu olup encounter olmayan var mı?\n";
$count = \App\Models\Appointment::where('status', \App\Enums\AppointmentStatus::COMPLETED)
    ->whereDoesntHave('encounter')
    ->count();
echo "Adet: $count\n";
if ($count > 0) {
    $items = \App\Models\Appointment::where('status', \App\Enums\AppointmentStatus::COMPLETED)
        ->whereDoesntHave('encounter')
        ->take(3)->get();
    foreach ($items as $item) {
        echo "- ID: {$item->id}, Status: {$item->status->value}\n";
    }
}
echo "\n";

echo "3. Done treatment plan item'ı olup encounter kaydı olmayan var mı?\n";
$count = \App\Models\TreatmentPlanItem::where('status', \App\Enums\TreatmentPlanItemStatus::DONE)
    ->whereDoesntHave('encounters')
    ->count();
echo "Adet: $count\n";
if ($count > 0) {
    $items = \App\Models\TreatmentPlanItem::where('status', \App\Enums\TreatmentPlanItemStatus::DONE)
        ->whereDoesntHave('encounters')
        ->take(3)->get();
    foreach ($items as $item) {
        echo "- ID: {$item->id}, Status: {$item->status->value}\n";
    }
}
echo "\n";

echo "4. Encounter kaydı olup plan item'ı ile bağlanmamış olan var mı?\n";
$count = \App\Models\Encounter::whereDoesntHave('treatmentPlanItems')->count();
echo "Adet: $count\n";
if ($count > 0) {
    $items = \App\Models\Encounter::whereDoesntHave('treatmentPlanItems')->take(3)->get();
    foreach ($items as $item) {
        echo "- ID: {$item->id}, Status: {$item->status->value}\n";
    }
}
echo "\n";

echo "5. Hasta → tedavi planı ilişkileri tutarlı mı?\n";
$count = \App\Models\TreatmentPlan::whereDoesntHave('patient')->count();
echo "Tutarsız tedavi planı adeti: $count\n";
if ($count > 0) {
    $items = \App\Models\TreatmentPlan::whereDoesntHave('patient')->take(3)->get();
    foreach ($items as $item) {
        echo "- ID: {$item->id}, Patient ID: {$item->patient_id}\n";
    }
}
echo "\n";

echo "6. Plan → plan item ilişkileri tutarlı mı?\n";
$count = \App\Models\TreatmentPlanItem::whereDoesntHave('treatmentPlan')->count();
echo "Tutarsız plan item adeti: $count\n";
if ($count > 0) {
    $items = \App\Models\TreatmentPlanItem::whereDoesntHave('treatmentPlan')->take(3)->get();
    foreach ($items as $item) {
        echo "- ID: {$item->id}, Treatment Plan ID: {$item->treatment_plan_id}\n";
    }
}
echo "\n";

echo "=== GENEL İSTATİSTİKLER ===\n";
echo "Toplam Hasta: " . \App\Models\Patient::count() . "\n";
echo "Toplam Randevu: " . \App\Models\Appointment::count() . "\n";
echo "Toplam Tedavi Planı: " . \App\Models\TreatmentPlan::count() . "\n";
echo "Toplam Encounter: " . \App\Models\Encounter::count() . "\n";
echo "Toplam Treatment Plan Item: " . \App\Models\TreatmentPlanItem::count() . "\n";