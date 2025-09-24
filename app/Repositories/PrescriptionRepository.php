<?php

namespace App\Repositories;

use App\Models\Prescription;
use Illuminate\Database\Eloquent\Collection;

class PrescriptionRepository
{
    public function find(int $id, array $relations = []): ?Prescription
    {
        return Prescription::with($relations)->find($id);
    }

    public function create(array $attributes, array $relations = []): Prescription
    {
        $prescription = Prescription::create($attributes);

        return $prescription->load($relations);
    }

    public function update(Prescription $prescription, array $attributes, array $relations = []): Prescription
    {
        $prescription->update($attributes);

        if (! empty($relations)) {
            $prescription->load($relations);
        }

        return $prescription;
    }

    public function delete(Prescription $prescription): ?bool
    {
        return $prescription->delete();
    }
}