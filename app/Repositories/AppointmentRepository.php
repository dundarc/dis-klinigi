<?php

namespace App\Repositories;

use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class AppointmentRepository
{
    /**
     * Belirtilen tarih aralığında ve filtrelere göre randevuları getirir.
     *
     * @param Carbon $start
     * @param Carbon $end
     * @param array|null $dentistIds
     * @param array|null $statuses
     * @return Collection
     */
    public function getAppointmentsByDateRange(Carbon $start, Carbon $end, ?array $dentistIds, ?array $statuses): Collection
    {
        $query = Appointment::with(['patient', 'dentist'])
            ->where('start_at', '<', $end)
            ->where('end_at', '>', $start);

        if (!empty($dentistIds)) {
            $query->whereIn('dentist_id', $dentistIds);
        }

        if (!empty($statuses)) {
            $query->whereIn('status', $statuses);
        }

        return $query->get();
    }

    /**
     * Verilen randevu verileriyle yeni bir randevu oluşturur.
     *
     * @param array $data
     * @return Appointment
     */
    public function create(array $data): Appointment
    {
        return Appointment::create($data);
    }

    /**
     * Mevcut bir randevuyu günceller.
     *
     * @param Appointment $appointment
     * @param array $data
     * @return bool
     */
    public function update(Appointment $appointment, array $data): bool
    {
        return $appointment->update($data);
    }

    /**
     * Belirtilen hekim ve zaman aralığında çakışan bir randevu olup olmadığını kontrol eder.
     * Güncelleme sırasında kontrol edilen randevunun kendisini hariç tutar.
     *
     * @param int $dentistId
     * @param Carbon $start
     * @param Carbon $end
     * @param int|null $exceptId
     * @return bool
     */
    public function hasConflict(int $dentistId, Carbon $start, Carbon $end, ?int $exceptId = null): bool
    {
        $query = Appointment::where('dentist_id', $dentistId)
            ->where('start_at', '<', $end)
            ->where('end_at', '>', $start);

        if ($exceptId) {
            $query->where('id', '!=', $exceptId);
        }

        return $query->exists();
    }
    
    /**
     * Bir randevuyu siler.
     *
     * @param Appointment $appointment
     * @return bool|null
     */
    public function delete(Appointment $appointment): ?bool
    {
        return $appointment->delete();
    }
}