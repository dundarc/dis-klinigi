<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $invoice_id
 * @property int|null $patient_treatment_id
 * @property string $description
 * @property float $quantity
 * @property float $unit_price
 * @property float $vat_rate
 * @property float $discount_rate
 * @property float $line_total
 * @property-read \App\Models\Invoice $invoice
 * @property-read \App\Models\PatientTreatment|null $patientTreatment
 */
class InvoiceItem extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'invoice_id',
        'patient_treatment_id',
        'treatment_plan_item_id',
        'description',
        'quantity',
        'unit_price',
        'vat_rate',
        'discount_rate',
        'line_total',
    ];

    /**
     * Bu fatura kaleminin ait olduğu ana fatura.
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function treatmentPlanItem()
    {
        return $this->belongsTo(TreatmentPlanItem::class, 'treatment_plan_item_id');
    }

    /**
     * Bu fatura kaleminin ilişkili olduğu hasta tedavisi.
     * --- EKSİK OLAN İLİŞKİ BUYDU ---
     */
    public function patientTreatment()
    {
        return $this->belongsTo(PatientTreatment::class);
    }
}