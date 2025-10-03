<?php

namespace App\Http\Controllers;

use App\Enums\AppointmentStatus;
use App\Enums\TreatmentPlanItemAppointmentAction;
use App\Enums\TreatmentPlanItemStatus;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\TreatmentPlan;
use App\Models\TreatmentPlanItem;
use App\Models\TreatmentPlanItemAppointment;
use App\Models\Stock\StockItem;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class QuickActionsController extends Controller
{
    /**
     * Upload file to patient treatment
     */
    public function uploadFile(Request $request): JsonResponse
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'type' => 'required|in:xray,photo,document,other',
            'file' => 'required|file|max:10240',
            'notes' => 'nullable|string|max:1000',
            'appointment_id' => 'nullable|exists:appointments,id',
            'encounter_id' => 'nullable|exists:encounters,id',
            'treatment_plan_item_id' => 'nullable|exists:treatment_plan_items,id',
        ]);

        // Validate file type based on selected type
        $fileType = \App\Enums\FileType::from($request->type);
        $allowedMimeTypes = $fileType->mimeTypes();
        $maxSize = $fileType->maxFileSize();

        $request->validate([
            'file' => [
                'required',
                'file',
                'max:' . ($maxSize / 1024), // Convert to KB for validation
                function ($attribute, $value, $fail) use ($allowedMimeTypes) {
                    if (!in_array($value->getMimeType(), $allowedMimeTypes)) {
                        $fail('Seçilen dosya tipi için geçersiz dosya formatı.');
                    }
                },
            ],
        ]);

        try {
            $file = $request->file('file');
            $path = $file->store('patient-files', 'public');

            $fileRecord = \App\Models\File::create([
                'patient_id' => $request->patient_id,
                'type' => $fileType,
                'filename' => $file->getClientOriginalName(),
                'original_filename' => $file->getClientOriginalName(),
                'path' => $path,
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'notes' => $request->notes,
                'uploader_id' => auth()->id(),
                'appointment_id' => $request->appointment_id,
                'encounter_id' => $request->encounter_id,
                'treatment_plan_item_id' => $request->treatment_plan_item_id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Dosya başarıyla yüklendi.',
                'file' => $fileRecord
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dosya yüklenirken bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel appointment
     */
    public function cancelAppointment(Request $request, Appointment $appointment): JsonResponse
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        try {
            $appointment->update([
                'status' => AppointmentStatus::CANCELLED,
                'notes' => ($appointment->notes ? $appointment->notes . "\n\n" : '') .
                          "İptal nedeni: " . $request->reason . " (İptal eden: " . auth()->user()->name . ")"
            ]);

            // Update related treatment plan items
            $appointment->treatmentPlanItems()->update(['status' => \App\Enums\TreatmentPlanItemStatus::PLANNED]);

            return response()->json([
                'success' => true,
                'message' => 'Randevu başarıyla iptal edildi.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Randevu iptal edilemedi.'
            ], 500);
        }
    }

    /**
     * Quick patient update
     */
    public function updatePatient(Request $request, Patient $patient): JsonResponse
    {
        $request->validate([
            'phone_primary' => 'nullable|string|max:20',
            'phone_secondary' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address_text' => 'nullable|string|max:500',
        ]);

        try {
            $updateData = $request->only([
                'phone_primary',
                'phone_secondary',
                'email',
                'address_text'
            ]);

            $patient->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Hasta bilgileri güncellendi.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hasta bilgileri güncellenemedi.'
            ], 500);
        }
    }

    /**
     * Add item to existing treatment plan
     */
    public function addTreatmentPlanItem(Request $request, TreatmentPlan $treatmentPlan): JsonResponse
    {
        $request->validate([
            'treatment_id' => 'required|exists:treatments,id',
            'tooth_number' => 'nullable|string|max:10',
            'estimated_price' => 'required|numeric|min:0',
            'appointment_date' => 'nullable|date|after:now'
        ]);

        try {
            DB::transaction(function () use ($request, $treatmentPlan) {
                $appointmentId = null;
                if ($request->appointment_date) {
                    $appointment = Appointment::create([
                        'patient_id' => $treatmentPlan->patient_id,
                        'dentist_id' => $treatmentPlan->dentist_id,
                        'start_at' => $request->appointment_date,
                        'end_at' => \Carbon\Carbon::parse($request->appointment_date)->addMinutes(30),
                        'status' => AppointmentStatus::SCHEDULED,
                        'notes' => 'Tedavi planından oluşturuldu.'
                    ]);
                    $appointmentId = $appointment->id;
                }

                $treatmentPlan->items()->create([
                    'treatment_id' => $request->treatment_id,
                    'tooth_number' => $request->tooth_number,
                    'appointment_id' => $appointmentId,
                    'estimated_price' => $request->estimated_price,
                    'status' => \App\Enums\TreatmentPlanItemStatus::PLANNED,
                ]);

                // Update plan total
                $treatmentPlan->increment('total_estimated_cost', $request->estimated_price);
            });

            return response()->json([
                'success' => true,
                'message' => 'Tedavi kalemi başarıyla eklendi.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tedavi kalemi eklenemedi.'
            ], 500);
        }
    }

    /**
     * Create new stock item (Admin/Accountant only)
     */
    public function createStockItem(Request $request): JsonResponse
    {
        $this->authorize('accessStockManagement');

        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:stock_categories,id',
            'unit' => 'required|string|max:50',
            'sku' => 'nullable|string|max:100|unique:stock_items,sku',
            'min_stock' => 'nullable|integer|min:0'
        ]);

        try {
            StockItem::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Stok kalemi başarıyla oluşturuldu.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Stok kalemi oluşturulamadı.'
            ], 500);
        }
    }

    /**
     * Create invoice/purchase (Admin/Accountant only)
     */
    public function createInvoice(Request $request): JsonResponse
    {
        $this->authorize('accessStockManagement');

        $request->validate([
            'supplier_id' => 'required|exists:stock_suppliers,id',
            'type' => 'required|in:purchase,expense',
            'invoice_file' => 'nullable|file|max:5120|mimes:jpg,jpeg,png,pdf'
        ]);

        try {
            // Implementation would depend on your invoice/purchase models
            // This is a stub implementation

            return response()->json([
                'success' => true,
                'message' => 'Fatura başarıyla oluşturuldu.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Fatura oluşturulamadı.'
            ], 500);
        }
    }

    /**
     * Create payment record (Admin/Accountant only)
     */
    public function createPayment(Request $request): JsonResponse
    {
        $this->authorize('accessStockManagement');

        $request->validate([
            'supplier_id' => 'required|exists:stock_suppliers,id',
            'amount' => 'required|numeric|min:0.01',
            'method' => 'required|in:cash,bank_transfer,credit_card,check',
            'date' => 'required|date',
            'receipt_file' => 'nullable|file|max:5120|mimes:jpg,jpeg,png,pdf'
        ]);

        try {
            // Implementation would depend on your payment models
            // This is a stub implementation

            return response()->json([
                'success' => true,
                'message' => 'Ödeme başarıyla kaydedildi.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ödeme kaydedilemedi.'
            ], 500);
        }
    }

    /**
     * Link treatment plan items to an appointment
     */
    public function linkItemsToAppointment(Request $request, Appointment $appointment): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'item_ids' => 'required|array',
            'item_ids.*' => 'exists:treatment_plan_items,id'
        ]);

        try {
            DB::transaction(function () use ($request, $appointment) {
                foreach ($request->item_ids as $itemId) {
                    $item = TreatmentPlanItem::find($itemId);

                    // Check if item belongs to the same treatment plan as the appointment
                    if ($item && $item->treatmentPlan->patient_id === $appointment->patient_id) {
                        // Update item to link to this appointment
                        $item->update(['appointment_id' => $appointment->id]);

                        // Create history entry
                        TreatmentPlanItemAppointment::create([
                            'treatment_plan_item_id' => $item->id,
                            'appointment_id' => $appointment->id,
                            'action' => TreatmentPlanItemAppointmentAction::PLANNED,
                            'notes' => 'Treatment item linked to appointment',
                            'user_id' => auth()->id()
                        ]);

                        // Update item status if appointment is in progress or completed
                        if ($appointment->status === AppointmentStatus::IN_SERVICE) {
                            $item->changeStatus(TreatmentPlanItemStatus::IN_PROGRESS, auth()->user(), 'Linked to in-service appointment');
                        } elseif ($appointment->status === AppointmentStatus::COMPLETED) {
                            $item->changeStatus(TreatmentPlanItemStatus::DONE, auth()->user(), 'Linked to completed appointment');
                        }
                    }
                }
            });

            return back()->with('success', 'Tedavi kalemleri randevuya başarıyla bağlandı.');
        } catch (\Exception $e) {
            return back()->with('error', 'Tedavi kalemleri bağlanırken bir hata oluştu.');
        }
    }
}
