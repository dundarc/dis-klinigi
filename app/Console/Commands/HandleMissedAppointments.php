<?php

namespace App\Console\Commands;

use App\Enums\AppointmentStatus;
use App\Models\TreatmentPlanItem;
use Illuminate\Console\Command;

class HandleMissedAppointments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointments:handle-missed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Find treatment plan items linked to no-show appointments and mark them as unplanned.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Searching for treatment items in no-show appointments...');

        $count = TreatmentPlanItem::whereHas('appointment', function ($query) {
            $query->where('status', AppointmentStatus::NO_SHOW);
        })->update(['appointment_id' => null]);

        if ($count > 0) {
            $this->info("Successfully marked {$count} treatment item(s) as unplanned.");
        } else {
            $this->info('No items found to update.');
        }

        return 0;
    }
}