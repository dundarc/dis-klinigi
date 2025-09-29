<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTreatmentPlanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Or add specific authorization logic
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            // Plan temel bilgileri
            'dentist_id' => 'required|exists:users,id',
            'notes' => 'nullable|string|max:1000',
            'status' => 'required|in:draft,active,completed,cancelled',

            // Mevcut tedavi kalemleri - sadece dolu olanları validate et
            'items' => 'sometimes|array',
            'items.*.id' => 'nullable|exists:treatment_plan_items,id',
            'items.*.treatment_id' => 'required_with:items.*|exists:treatments,id',
            'items.*.tooth_number' => 'nullable|string|max:50|regex:/^[0-9,\-\s]*$/',
            'items.*.appointment_date' => 'nullable|date',
            'items.*.estimated_price' => 'required_with:items.*|numeric|min:0|max:999999.99',
            'items.*.status' => 'required_with:items.*|in:planned,in_progress,done,cancelled,no_show,invoiced',

            // Yeni tedavi kalemleri - sadece dolu olanları validate et
            'new_items' => 'sometimes|array',
            'new_items.*.treatment_id' => 'required_with:new_items.*|exists:treatments,id',
            'new_items.*.tooth_number' => 'nullable|string|max:50|regex:/^[0-9,\-\s]*$/',
            'new_items.*.appointment_date' => 'nullable|date',
            'new_items.*.estimated_price' => 'required_with:new_items.*|numeric|min:0|max:999999.99',
            'new_items.*.status' => 'required_with:new_items.*|in:planned,in_progress,done,cancelled,no_show,invoiced',

            // Silinen öğeler
            'deleted_items' => 'sometimes|array',
            'deleted_items.*' => 'exists:treatment_plan_items,id',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Custom validation: Sadece dolu olan ve düzenlenebilir item'ları kontrol et
            $dentistId = $this->input('dentist_id');

            // Items kontrolü - sadece düzenlenebilir olanları
            if ($this->has('items')) {
                foreach ($this->input('items') as $index => $itemData) {
                    // Sadece dolu item'ları kontrol et
                    if (!empty($itemData['treatment_id']) && !empty($itemData['estimated_price'])) {

                        // Tamamlanan öğeler düzenlenemez kontrolü - sadece düzenlenebilir olanlar için
                        if (!empty($itemData['id'])) {
                            $item = \App\Models\TreatmentPlanItem::find($itemData['id']);
                            if ($item && $item->status === \App\Enums\TreatmentPlanItemStatus::DONE) {
                                // Tamamlanan öğeler için hata verme - zaten disabled halde
                                continue; // Bu item için diğer kontrolleri atla
                            }
                        }

                        // Randevu tarihi gelecekte olmalı (sadece yeni randevular için)
                        if (!empty($itemData['appointment_date'])) {
                            $appointmentDate = \Carbon\Carbon::parse($itemData['appointment_date']);
                            if ($appointmentDate->isPast() && empty($itemData['id'])) {
                                // Yeni randevu geçmiş tarihli olamaz
                                $validator->errors()->add("items.{$index}.appointment_date", 'Yeni randevu tarihi gelecekte olmalıdır.');
                            }
                        }

                        // Randevu çakışma kontrolü
                        if ($dentistId && !empty($itemData['appointment_date'])) {
                            $startAt = $itemData['appointment_date'];
                            $endAt = \Carbon\Carbon::parse($startAt)->addMinutes(30)->format('Y-m-d H:i:s');

                            // Mevcut randevuyu ignore et (eğer varsa)
                            $ignoreId = null;
                            if (!empty($itemData['id'])) {
                                $existingItem = \App\Models\TreatmentPlanItem::find($itemData['id']);
                                $ignoreId = $existingItem ? $existingItem->appointment_id : null;
                            }

                            $service = app(\App\Services\TreatmentPlanService::class);
                            if ($service->checkAppointmentConflict($dentistId, $startAt, $endAt, $ignoreId)) {
                                $validator->errors()->add("items.{$index}.appointment_date", 'Bu tarih ve saatte doktorun başka randevusu var.');
                            }
                        }
                    }
                }
            }

            // New items kontrolü
            if ($this->has('new_items')) {
                foreach ($this->input('new_items') as $index => $itemData) {
                    // Sadece dolu item'ları kontrol et
                    if (!empty($itemData['treatment_id']) && !empty($itemData['estimated_price'])) {

                        // Randevu tarihi gelecekte olmalı
                        if (!empty($itemData['appointment_date'])) {
                            $appointmentDate = \Carbon\Carbon::parse($itemData['appointment_date']);
                            if ($appointmentDate->isPast()) {
                                $validator->errors()->add("new_items.{$index}.appointment_date", 'Randevu tarihi gelecekte olmalıdır.');
                            }
                        }

                        // Randevu çakışma kontrolü
                        if ($dentistId && !empty($itemData['appointment_date'])) {
                            $startAt = $itemData['appointment_date'];
                            $endAt = \Carbon\Carbon::parse($startAt)->addMinutes(30)->format('Y-m-d H:i:s');

                            $service = app(\App\Services\TreatmentPlanService::class);
                            if ($service->checkAppointmentConflict($dentistId, $startAt, $endAt)) {
                                $validator->errors()->add("new_items.{$index}.appointment_date", 'Bu tarih ve saatte doktorun başka randevusu var.');
                            }
                        }
                    }
                }
            }

            // Silinen öğeler kontrolü - tamamlanan öğeler zaten silinemez (UI'da disabled)
            if ($this->has('deleted_items')) {
                foreach ($this->input('deleted_items') as $itemId) {
                    $item = \App\Models\TreatmentPlanItem::find($itemId);
                    if ($item && $item->status === \App\Enums\TreatmentPlanItemStatus::DONE) {
                        // Bu durumda hata verme - zaten UI'da engellenmiş
                        continue;
                    }
                }
            }
        });
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'dentist_id.required' => 'Sorumlu diş hekimi seçimi zorunludur.',
            'dentist_id.exists' => 'Seçilen diş hekimi bulunamadı.',
            'status.required' => 'Tedavi planı durumu zorunludur.',
            'status.in' => 'Geçersiz tedavi planı durumu.',
            'items.*.treatment_id.required_with' => 'Tedavi türü seçimi zorunludur.',
            'items.*.treatment_id.exists' => 'Seçilen tedavi türü bulunamadı.',
            'items.*.tooth_number.regex' => 'Diş numarası sadece rakam, virgül ve tire içerebilir.',
            'items.*.estimated_price.required_with' => 'Tahmini fiyat zorunludur.',
            'items.*.estimated_price.numeric' => 'Tahmini fiyat sayısal değer olmalıdır.',
            'items.*.estimated_price.min' => 'Tahmini fiyat 0 veya daha büyük olmalıdır.',
            'items.*.estimated_price.max' => 'Tahmini fiyat çok yüksek.',
            'items.*.status.required_with' => 'Tedavi kalemi durumu zorunludur.',
            'items.*.status.in' => 'Geçersiz tedavi kalemi durumu.',
            'new_items.*.treatment_id.required_with' => 'Yeni tedavi kalemi için tedavi türü seçimi zorunludur.',
            'new_items.*.estimated_price.required_with' => 'Yeni tedavi kalemi için fiyat zorunludur.',
            'new_items.*.estimated_price.numeric' => 'Yeni tedavi kalemi için fiyat sayısal değer olmalıdır.',
            'new_items.*.estimated_price.min' => 'Yeni tedavi kalemi için fiyat 0 veya daha büyük olmalıdır.',
            'new_items.*.status.required_with' => 'Yeni tedavi kalemi için durum zorunludur.',
            'new_items.*.status.in' => 'Geçersiz yeni tedavi kalemi durumu.',
            'deleted_items.*.exists' => 'Silinmeye çalışılan tedavi kalemi bulunamadı.',
        ];
    }

    /**
     * Get custom attribute names.
     */
    public function attributes(): array
    {
        return [
            'dentist_id' => 'sorumlu diş hekimi',
            'status' => 'tedavi planı durumu',
            'items.*.treatment_id' => 'tedavi türü',
            'items.*.tooth_number' => 'diş numarası',
            'items.*.appointment_date' => 'randevu tarihi',
            'items.*.estimated_price' => 'tahmini fiyat',
            'items.*.status' => 'durum',
            'new_items.*.treatment_id' => 'tedavi türü',
            'new_items.*.tooth_number' => 'diş numarası',
            'new_items.*.appointment_date' => 'randevu tarihi',
            'new_items.*.estimated_price' => 'tahmini fiyat',
            'new_items.*.status' => 'durum',
            'deleted_items.*' => 'silinen öğe',
        ];
    }
}