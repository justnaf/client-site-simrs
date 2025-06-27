<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentType;
use Illuminate\Http\Request;
use App\Models\Registration;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class RegistrationController extends Controller
{
    private $apiBaseUrl = 'https://ti054a01.agussbn.my.id/api';

    public function index(Request $request): View
    {
        try {
            $responseUtama = Http::timeout(15)->get($this->apiBaseUrl . '/pendaftaran');
            $responsePoli = Http::timeout(15)->get($this->apiBaseUrl . '/poli');

            if ($responseUtama->failed() || $responsePoli->failed()) {
                throw new \Exception('Gagal mengambil data dari salah satu API.');
            }

            $apiRegistrations = collect(data_get($responseUtama->json(), 'data', []));
            $polis = data_get($responsePoli->json(), 'data', []);

            // 1. Ambil semua data registrasi LOKAL dan buat menjadi lookup map
            // Eager load 'paymentType' untuk performa optimal
            $localRegistrationsLookup = \App\Models\Registration::with('paymentType')
                ->get()
                ->keyBy('no_registrasi');

            // 2. Gabungkan data API dengan data lokal untuk mendapatkan 'nomor_invoice'
            $enrichedRegistrations = $apiRegistrations->map(function ($apiItem) use ($localRegistrationsLookup) {
                // Cari data lokal yang cocok berdasarkan no_registrasi
                $localMatch = $localRegistrationsLookup->get($apiItem['no_registrasi']);

                // Tambahkan nomor_invoice ke data API
                // Jika tidak ada data lokal, beri nilai fallback
                $apiItem['nomor_invoice'] = $localMatch ? $localMatch->nomor_invoice : 'N/A - Lokal';

                return $apiItem;
            });


            // --- FILTER & PAGINATION PADA DATA YANG SUDAH DIGABUNGKAN ---

            // 3. Terapkan filter pada data yang sudah diperkaya
            $filteredRegistrations = $enrichedRegistrations->when($request->filled('search'), function ($collection) use ($request) {
                $searchTerm = strtolower($request->input('search'));
                return $collection->filter(function ($item) use ($searchTerm) {
                    // Tambahkan 'nomor_invoice' ke dalam kolom pencarian
                    return str_contains(strtolower($item['nama_pasien'] ?? ''), $searchTerm) ||
                        str_contains((string)($item['rm'] ?? ''), $searchTerm) ||
                        str_contains(strtolower($item['nomor_invoice'] ?? ''), $searchTerm); // Ganti no_registrasi dengan nomor_invoice
                });
            })->when($request->filled('poli'), function ($collection) use ($request) {
                return $collection->where('id_poli', $request->input('poli'));
            });

            // 4. Paginasi manual tetap sama, tapi sekarang pada data yang sudah digabung
            $perPage = 10;
            $currentPage = Paginator::resolveCurrentPage('page');
            $currentPageItems = $filteredRegistrations->slice(($currentPage - 1) * $perPage, $perPage)->values();

            $registrations = new LengthAwarePaginator(
                $currentPageItems,
                $filteredRegistrations->count(),
                $perPage,
                $currentPage,
                ['path' => Paginator::resolveCurrentPath()]
            );

            return view('admin.pendaftaraan.index', [
                'registrations' => $registrations,
                'polis' => $polis,
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal memproses data pendaftaran: ' . $e->getMessage());
            return view('admin.pendaftaraan.index', [
                'registrations' => new LengthAwarePaginator([], 0, 10),
                'polis' => [],
            ])->with('error', 'Gagal mengambil atau memproses data dari server.');
        }
    }

    public function create()
    {
        try {
            $responsePoli = Http::timeout(15)->get($this->apiBaseUrl . '/poli');
            if ($responsePoli->failed()) {
                Log::error('Gagal mengambil data Poli saat membuat pendaftaran.', ['status' => $responsePoli->status()]);
                return redirect()->route('admin.registration.index')
                    ->with('error', 'Gagal memuat data Poli untuk form pendaftaran.');
            }
            $polis = data_get($responsePoli->json(), 'data', []);
            $responsePatients = Http::timeout(15)->get($this->apiBaseUrl . '/pasien');
            if ($responsePatients->failed()) {
                Log::error('Gagal mengambil data Pasien saat membuat pendaftaran.', ['status' => $responsePatients->status()]);
                return redirect()->route('admin.registration.index')
                    ->with('error', 'Gagal memuat data Pasien untuk form pendaftaran.');
            }
            $patients = data_get($responsePatients->json(), 'data', []);
            $payments = PaymentType::all();
            return view('admin.pendaftaraan.create', [
                'polis' => $polis,
                'patients' => $patients,
                'payments' => $payments,
            ]);
        } catch (\Exception $e) {
            Log::error('Koneksi ke API gagal saat memuat form pendaftaran: ' . $e->getMessage());

            return redirect()->route('admin.registration.index')
                ->with('error', 'Tidak dapat terhubung ke server API. Mohon coba lagi nanti.');
        }
    }

    /**
     * Menyimpan pendaftaran baru ke API dan database lokal.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'rm' => 'required|integer',
            'id_poli' => 'required|integer',
            'payment_type_id' => 'required|integer|exists:payment_types,id',
            'tgl_kunjungan' => 'required|date',
        ]);

        try {
            $responseAllRegistrations = Http::timeout(20)->get($this->apiBaseUrl . '/pendaftaran');
            if ($responseAllRegistrations->failed()) {
                return redirect()->back()->with('error', 'Gagal memeriksa daftar antrian dari API.')->withInput();
            }
            $allRegistrations = collect(data_get($responseAllRegistrations->json(), 'data', []));

            $targetDate = $validatedData['tgl_kunjungan'];
            $targetPoliId = $validatedData['id_poli'];
            $registrationsTodayForPoli = $allRegistrations->filter(function ($item) use ($targetDate, $targetPoliId) {

                $dateMatch = str_starts_with($item['tgl_kunjungan'] ?? '', $targetDate);
                $poliMatch = ($item['id_poli'] ?? null) == $targetPoliId;

                return $dateMatch && $poliMatch;
            });
            $lastQueueNumber = $registrationsTodayForPoli->max('no_antrian');
            $newQueueNumber = ($lastQueueNumber ?? 0) + 1;

            $apiData = [
                'rm' => $validatedData['rm'],
                'id_poli' => $validatedData['id_poli'],
                'tgl_kunjungan' => $validatedData['tgl_kunjungan'],
                'status' => 0,
                'no_antrian' => $newQueueNumber,
            ];

            $responseRegistration = Http::post($this->apiBaseUrl . '/pendaftaran', $apiData);

            if ($responseRegistration->created()) {
                $responseData = $responseRegistration->json();
                $newNoRegistrasi = data_get($responseData, 'data.no_registrasi');

                if (!$newNoRegistrasi) {
                    Log::error('API pendaftaran sukses, tetapi tidak mengembalikan no_registrasi.', ['response' => $responseData]);
                    return redirect()->back()->with('error', 'Terjadi kesalahan sinkronisasi: No Registrasi tidak diterima dari API.')->withInput();
                }

                $unicode = 'OFF-' . strtoupper(Str::random(8));

                Registration::create([
                    'no_registrasi' => $newNoRegistrasi,
                    'unicode' => $unicode,
                    'payment_type_id' => $validatedData['payment_type_id']
                ]);

                return redirect()->route('admin.registration.index')
                    ->with('success', 'Pendaftaran berhasil. No Registrasi: ' . $newNoRegistrasi . '. No Antrian: ' . $newQueueNumber);
            } else {
                $errorMessage = data_get($responseRegistration->json(), 'message', 'Gagal menyimpan data pendaftaran ke server API.');
                return redirect()->back()->with('error', $errorMessage)->withInput();
            }
        } catch (\Exception $e) {
            Log::error('Koneksi untuk store pendaftaran gagal: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Tidak dapat terhubung ke server API. Mohon coba lagi nanti.')->withInput();
        }
    }


    public function destroy($register)
    {
        $apiUrl = $this->apiBaseUrl . "/pendaftaran/{$register}";


        try {
            $response = Http::timeout(30)->delete($apiUrl);
            if ($response->ok()) {
                $localRegistration = Registration::where('no_registrasi', $register)->first();
                if ($localRegistration) {
                    $localRegistration->delete();
                    Log::info("Data lokal dengan no_registrasi {$register} berhasil dihapus.");
                } else {
                    Log::warning("Data API dengan no_registrasi {$register} dihapus, namun tidak ditemukan padanannya di database lokal.");
                }
                $message = data_get($response->json(), 'message', 'Data berhasil dihapus dari server API dan lokal.');
                return redirect()->route('admin.registration.index')
                    ->with('success', $message);
            } else {
                $errorMessage = data_get($response->json(), 'message', 'Terjadi kesalahan pada server API.');
                Log::error("Gagal menghapus via API. Status: {$response->status()}", ['response' => $response->body()]);

                return redirect()->route('admin.registration.index')
                    ->with('error', "Gagal: {$errorMessage}");
            }
        } catch (\Exception $e) {
            Log::error('Koneksi ke API delete gagal: ' . $e->getMessage());

            return redirect()->route('admin.registration.index')
                ->with('error', 'Tidak dapat terhubung ke server API untuk menghapus data.');
        }
    }
}
