<?php

namespace App\Observers;

use App\Enums\EncounterType;
use App\Models\Encounter;
use App\Services\AutomaticEmailService;

class EncounterObserver
{
    public function __construct(
        private readonly AutomaticEmailService $automaticEmailService
    ) {
    }

    public function created(Encounter $encounter): void
    {
        if ($encounter->type === EncounterType::EMERGENCY) {
            $this->automaticEmailService->sendEmergencyPatient($encounter);
        }
    }
}
