<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use App\Http\Requests\UpdatePatientNotesRequest;
use App\Models\Patient;
use App\Models\Treatment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PatientController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('viewAny', Patient::class);
        $user = auth()->user();
        $search = $request->input('search');
        $query = Patient::query();

        if ($user->role === UserRole::DENTIST) {
            $query->whereHas('appointments', fn ($q) => $q->where('dentist_id', $user->id));
        }

        $query->when($search, function ($q, $search) {
            $q->where(fn($subQuery) => $subQuery->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('phone_primary', 'like', "%{$search}%")
                ->orWhere('national_id', 'like', "%{$search}%"));
        });

        $patients = $query->latest()->paginate(20)->withQueryString();
        return view('patients.index', compact('patients', 'search'));
    }

    public function create()
    {
        $this->authorize('create', Patient::class);
        return view('patients.create');
    }

    public function store(StorePatientRequest $request)
    {
        // 1. Formdan gelen verileri doğrula.
        $validatedData = $request->validated();

        // 2. 'consent_kvkk' bir veritabanı sütunu olmadığı için, diziden kaldır.
        //    Bu alan sadece checkbox'ın işaretli olduğunu doğrulamak için vardı.
        unset($validatedData['consent_kvkk']);

        // 3. Yeni bir Patient nesnesi oluştur ve doğrulanmış verileri ata.
        $patient = new Patient($validatedData);

        // 4. İşaret kutucuklarını (checkbox) ayrıca ve açıkça işle.
        //    $request->boolean() metodu, '1', 'on', 'true' gibi değerleri doğru bir şekilde boolean'a çevirir.
        $patient->has_private_insurance = $request->boolean('has_private_insurance');
        // 'accepted' kuralı sayesinde buraya geldiğimizde 'consent_kvkk' her zaman işaretlidir.
        $patient->consent_kvkk_at = now();

        // 5. Hazırlanan hastayı veritabanına kaydet.
        $patient->save();

        return redirect()->route('patients.index')->with('success', 'Hasta başarıyla eklendi.');
    }

    public function show(Patient $patient)
    {
        $this->authorize('view', $patient);
        $patient->load([
            'treatments' => fn($q) => $q->with(['dentist', 'treatment'])->latest('performed_at'),
            'invoices' => fn($q) => $q->with('items')->latest('issue_date'),
            'files' => fn($q) => $q->with('uploader')->latest()
        ]);
        $treatmentsList = Treatment::orderBy('name')->get();
        $uninvoicedTreatments = $patient->treatments()->whereDoesntHave('invoiceItem')->where('status', 'done')->with('treatment')->get();
        return view('patients.show', compact('patient', 'treatmentsList', 'uninvoicedTreatments'));
    }

    public function edit(Patient $patient)
    {
        $this->authorize('update', $patient);
        return view('patients.edit', compact('patient'));
    }

    /**
     * Ana hasta bilgilerini günceller (edit.blade.php formundan gelir).
     */
    public function update(UpdatePatientRequest $request, Patient $patient)
    {
        // 1. Formdan gelen verileri doğrula.
        $validatedData = $request->validated();

        // 2. Doğrulanmış verileri modele tek tek ata. Bu, $fillable dizisinden bağımsız çalışır.
        $patient->fill($validatedData);

        // 3. İşaret kutucuklarını (checkbox) ayrıca ve açıkça işle. Bu en sağlam yöntemdir.
        $patient->has_private_insurance = $request->boolean('has_private_insurance');
        
        // 4. KVKK onay tarihini yönet.
        if ($request->boolean('consent_kvkk')) {
            // Eğer kutucuk işaretliyse ve daha önceden onay tarihi yoksa, şimdiki zamanı ata.
            // Eğer zaten varsa, o tarihi koru. Onay bir kez verilir.
            $patient->consent_kvkk_at = $patient->consent_kvkk_at ?? now();
        } else {
            // Eğer kutucuk işaretli değilse, onayı kaldır (null yap).
            $patient->consent_kvkk_at = null;
        }

        // 5. Değişiklikleri veritabanına kaydet.
        $patient->save();

        return redirect()->route('patients.show', $patient)->with('success', 'Hasta bilgileri başarıyla güncellendi.');
    }

    /**
     * Sadece hastanın notlarını günceller (show.blade.php formundan gelir).
     */
    public function updateNotes(UpdatePatientNotesRequest $request, Patient $patient)
    {
        $patient->update($request->validated());
        return redirect()->route('patients.show', $patient)->with('success', 'Hasta notları başarıyla güncellendi.');
    }

    public function destroy(Patient $patient)
    {
        $this->authorize('delete', $patient);
        $patient->delete();
        return redirect()->route('patients.index')->with('success', 'Hasta başarıyla arşivlendi.');
    }
}

