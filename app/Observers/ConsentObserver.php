<?php

namespace App\Observers;

use App\Enums\ConsentStatus;
use App\Models\Consent;
use App\Services\AutomaticEmailService;

class ConsentObserver
{
    public function __construct(
        private readonly AutomaticEmailService $automaticEmailService
    ) {
    }

    public function created(Consent $consent): void
    {
        if ($consent->status === ConsentStatus::ACTIVE) {
            $this->automaticEmailService->sendKvkkConsent($consent);
        }
    }

    public function updated(Consent $consent): void
    {
        if ($consent->wasChanged('status') && $consent->status === ConsentStatus::ACTIVE) {
            $this->automaticEmailService->sendKvkkConsent($consent);
        }
    }
}
