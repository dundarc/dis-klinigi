<?php
namespace App\Http\Controllers;
use App\Models\PatientTreatment;
use App\Models\Patient;
use Illuminate\Http\Request;
use App\Enums\UserRole;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // 1. Ekle
use App\Models\Treatment; // Tedavi modelini ekleyin
use App\Models\User; // Kullanıcı modelini ekleyin
use App\Models\PatientXray;
use Illuminate\Support\Facades\Auth;

use App\Models\Invoice; // Yeni ekleyin

use App\Http\Requests\StorePatientTreatmentRequest; // Yeni bir request sınıfı kullanacağız

class PatientController extends Controller
{
        use AuthorizesRequests; // 2. Ekle
    public function index()
    {
        
        $this->authorize('viewAny', Patient::class);

        $user = auth()->user();
        
        if ($user->role === UserRole::DENTIST) {
            // Hekim, sadece kendisine randevusu olan hastaları görür
            $patients = Patient::whereHas('appointments', function ($query) use ($user) {
                $query->where('dentist_id', $user->id);
            })->latest()->paginate(20);
        } else {
            // Admin ve Resepsiyonist tüm hastaları görür
            $patients = Patient::latest()->paginate(20);
        }

        return view('patients.index', compact('patients'));
    }

    public function show(Patient $patient)
    {
        $this->authorize('view', $patient);
        $patient->load(['treatments.dentist', 'invoices', 'prescriptions.dentist', 'files.uploader']);
        return view('patients.show', compact('patient'));
    }

     public function createTreatment(Patient $patient)
    {
    $this->authorize('create', [PatientTreatment::class, $patient]);

        $treatments = Treatment::orderBy('name')->get();
        $dentists = User::where('role', UserRole::DENTIST)->get();

        return view('patients.treatments.create', compact('patient', 'treatments', 'dentists'));
    }

      public function storeTreatment(StorePatientTreatmentRequest $request, Patient $patient)
    {
        $this->authorize('create', [PatientTreatment::class, $patient]);
        
        // Veritabanı işlemi başlat
        DB::beginTransaction();

        try {
            // 1. Tedavi kaydını oluştur
            $treatment = $patient->treatments()->create([
                'treatment_id' => $request->treatment_id,
                'dentist_id' => $request->dentist_id,
                'performed_at' => $request->performed_at,
                'notes' => $request->notes,
            ]);

            // 2. Fatura miktarını kontrol et ve fatura oluştur
            if ($request->filled('invoice_amount')) {
                Invoice::create([
                    'patient_id' => $patient->id,
                    'patient_treatment_id' => $treatment->id,
                    'amount' => $request->invoice_amount,
                    'status' => 'pending',
                ]);
            }
            
            // 3. Röntgen görselini kontrol et ve kaydet
            if ($request->hasFile('xray_image')) {
                $imagePath = $request->file('xray_image')->store('public/xrays');

                PatientXray::create([
                    'patient_id' => $patient->id,
                    'uploader_id' => Auth::id(),
                    'name' => $request->file('xray_image')->getClientOriginalName(),
                    'path' => $imagePath,
                    'notes' => null,
                ]);
            }

            DB::commit(); // İşlemler başarılı, değişiklikleri onayla

            return redirect()->route('patients.show', $patient)->with('status', 'Tedavi, fatura ve röntgen başarıyla eklendi.');

        } catch (\Exception $e) {
            DB::rollBack(); // Hata oluştu, işlemleri geri al
            return back()->withInput()->withErrors(['error' => 'İşlem sırasında bir hata oluştu. Lütfen tekrar deneyin.']);
        }
    }




        public function storeXRay(Request $request, Patient $patient)
    {
        // Yetkilendirme kontrolü (yetkilendirme politikasını daha sonra tanımlayacağız)
        // $this->authorize('create', [PatientXray::class, $patient]);

        // Dosya doğrulama
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ], [
            'image.required' => 'Lütfen bir görsel dosyası seçin.',
            'image.image' => 'Yüklenen dosya bir görsel olmalıdır.',
            'image.mimes' => 'Desteklenen dosya formatları: jpeg, png, jpg, gif, svg.',
            'image.max' => 'Dosya boyutu en fazla 2MB olabilir.',
        ]);

        // Dosyayı public/xrays dizinine kaydetme
        $imagePath = $request->file('image')->store('public/xrays');

        // Veritabanına yeni kaydı oluşturma
        PatientXray::create([
            'patient_id' => $patient->id,
            'uploader_id' => Auth::id(),
            'name' => $request->file('image')->getClientOriginalName(),
            'path' => $imagePath,
            'notes' => null, // Şimdilik not yok
        ]);

        return redirect()->route('patients.show', $patient)->with('status', 'Röntgen görseli başarıyla yüklendi.');
    }


    // Diğer metodları (create, store vb.) sonraki adımlarda ekleyeceğiz.
}