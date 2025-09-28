<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Patient;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Encounter;
use App\Models\TreatmentPlan;
use App\Enums\UserRole;
use App\Enums\AppointmentStatus;
use Carbon\Carbon;

class ClinicDataSeeder extends Seeder
{
    public function run(): void
    {
        // Skip if appointments already exist
        if (\App\Models\Appointment::count() > 0) {
            $this->command->info('Appointments already exist, skipping ClinicDataSeeder');
            return;
        }

        // Sadece hekim rolündeki kullanıcıları al
        $dentists = User::where('role', UserRole::DENTIST)->get();

        if ($dentists->isEmpty()) {
            $this->command->warn('Hiç hekim bulunamadı, randevu ve acil durum oluşturma atlanıyor.');
            return;
        }

        // Mevcut hastalardan rastgele seç
        $patients = Patient::all();

        if ($patients->isEmpty()) {
            $this->command->warn('Hiç hasta bulunamadı, randevu oluşturma atlanıyor.');
            return;
        }

        // Bugünkü randevular oluştur (bekleme odası için)
        $todayAppointments = [
            [
                'patient' => $patients->random(),
                'dentist' => $dentists->random(),
                'start_at' => Carbon::today()->setHour(9)->setMinute(0),
                'end_at' => Carbon::today()->setHour(9)->setMinute(30),
                'status' => AppointmentStatus::SCHEDULED,
                'room' => 'Oda 1',
                'notes' => 'Düzenli kontrol',
            ],
            [
                'patient' => $patients->random(),
                'dentist' => $dentists->random(),
                'start_at' => Carbon::today()->setHour(9)->setMinute(30),
                'end_at' => Carbon::today()->setHour(10)->setMinute(0),
                'status' => AppointmentStatus::CHECKED_IN,
                'room' => 'Oda 1',
                'notes' => 'Kompozit dolgu',
            ],
            [
                'patient' => $patients->random(),
                'dentist' => $dentists->random(),
                'start_at' => Carbon::today()->setHour(10)->setMinute(0),
                'end_at' => Carbon::today()->setHour(10)->setMinute(30),
                'status' => AppointmentStatus::IN_SERVICE,
                'room' => 'Oda 2',
                'notes' => 'Kanal tedavisi',
            ],
            [
                'patient' => $patients->random(),
                'dentist' => $dentists->random(),
                'start_at' => Carbon::today()->setHour(10)->setMinute(30),
                'end_at' => Carbon::today()->setHour(11)->setMinute(0),
                'status' => AppointmentStatus::SCHEDULED,
                'room' => 'Oda 2',
                'notes' => 'Diş çekimi',
            ],
            [
                'patient' => $patients->random(),
                'dentist' => $dentists->random(),
                'start_at' => Carbon::today()->setHour(11)->setMinute(0),
                'end_at' => Carbon::today()->setHour(11)->setMinute(30),
                'status' => AppointmentStatus::SCHEDULED,
                'room' => 'Oda 1',
                'notes' => 'İmplant kontrolü',
            ],
        ];

        foreach ($todayAppointments as $appointmentData) {
            $appointment = new Appointment([
                'patient_id' => $appointmentData['patient']->id,
                'dentist_id' => $appointmentData['dentist']->id,
                'start_at' => $appointmentData['start_at'],
                'end_at' => $appointmentData['end_at'],
                'status' => $appointmentData['status'],
                'room' => $appointmentData['room'],
                'notes' => $appointmentData['notes'],
            ]);

            // Treatment plan varsa bağla
            $treatmentPlans = TreatmentPlan::where('patient_id', $appointmentData['patient']->id)->get();
            if ($treatmentPlans->isNotEmpty()) {
                $appointment->treatment_plan_id = $treatmentPlans->random()->id;
            }

            $appointment->save();

            // Check-in edilmiş randevular için queue number ekle
            if ($appointment->status === AppointmentStatus::CHECKED_IN) {
                $appointment->update(['queue_number' => rand(1, 10)]);
            }
        }

        // Gelecek tarihli randevular oluştur
        for ($i = 1; $i <= 7; $i++) {
            $futureDate = Carbon::today()->addDays($i);
            $numAppointments = rand(3, 8);

            for ($j = 0; $j < $numAppointments; $j++) {
                $startHour = 9 + $j; // 9:00'dan başlayarak
                $startTime = Carbon::createFromTime($startHour, 0, 0, 'Europe/Istanbul');
                $endTime = $startTime->copy()->addMinutes(30);

                Appointment::create([
                    'patient_id' => $patients->random()->id,
                    'dentist_id' => $dentists->random()->id,
                    'start_at' => $futureDate->setTime($startHour, 0),
                    'end_at' => $futureDate->setTime($startHour, 30),
                    'status' => AppointmentStatus::SCHEDULED,
                    'room' => 'Oda ' . rand(1, 3),
                    'notes' => collect(['Kontrol', 'Dolgu', 'Temizlik', 'Kanal', 'Çekim', 'İmplant'])->random(),
                ]);
            }
        }

        // Acil vakalar oluştur (bekleme odası için)
        $emergencyEncounters = [
            [
                'patient' => $patients->random(),
                'priority' => 'red',
                'chief_complaint' => 'Şiddetli diş ağrısı',
                'notes' => 'Acil müdahale gerekli',
            ],
            [
                'patient' => $patients->random(),
                'priority' => 'red',
                'chief_complaint' => 'Diş kırılması',
                'notes' => 'Spor sırasında meydana geldi',
            ],
            [
                'patient' => $patients->random(),
                'priority' => 'yellow',
                'chief_complaint' => 'Dolgu düştü',
                'notes' => 'Geçici çözüm gerekli',
            ],
        ];

        foreach ($emergencyEncounters as $encounterData) {
            Encounter::create([
                'patient_id' => $encounterData['patient']->id,
                'type' => 'emergency',
                'triage_level' => $encounterData['priority'],
                'status' => 'waiting',
                'notes' => $encounterData['chief_complaint'] . ' - ' . $encounterData['notes'],
                'arrived_at' => now()->subMinutes(rand(5, 60)),
            ]);
        }

        // Walk-in vakalar oluştur
        $walkInEncounters = [
            [
                'patient' => $patients->random(),
                'priority' => 'green',
                'chief_complaint' => 'Diş kontrolü',
                'notes' => 'Randevusuz geldi',
            ],
            [
                'patient' => $patients->random(),
                'priority' => 'green',
                'chief_complaint' => 'Diş temizliği',
                'notes' => 'Düzenli bakım için',
            ],
        ];

        foreach ($walkInEncounters as $encounterData) {
            Encounter::create([
                'patient_id' => $encounterData['patient']->id,
                'type' => 'walk_in',
                'triage_level' => 'green',
                'status' => 'waiting',
                'notes' => $encounterData['chief_complaint'] . ' - ' . $encounterData['notes'],
                'arrived_at' => now()->subMinutes(rand(10, 45)),
            ]);
        }

        // Tamamlanmış vakalar oluştur (bugün için geçmiş)
        $completedEncounters = [
            [
                'patient' => $patients->random(),
                'priority' => 'green',
                'chief_complaint' => 'Kompozit dolgu',
                'notes' => 'Başarıyla tamamlandı',
            ],
            [
                'patient' => $patients->random(),
                'priority' => 'yellow',
                'chief_complaint' => 'Diş çekimi',
                'notes' => 'Komplikasyonsuz tamamlandı',
            ],
        ];

        foreach ($completedEncounters as $encounterData) {
            Encounter::create([
                'patient_id' => $encounterData['patient']->id,
                'type' => 'scheduled',
                'triage_level' => 'green',
                'status' => 'done',
                'notes' => $encounterData['chief_complaint'] . ' - ' . $encounterData['notes'],
                'arrived_at' => now()->subHours(rand(1, 4)),
                'started_at' => now()->subHours(rand(1, 4))->addMinutes(rand(5, 30)),
                'ended_at' => now()->subMinutes(rand(5, 60)),
            ]);
        }
    }
}