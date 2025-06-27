<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class PatientController extends Controller
{
    // Definisikan base URL API untuk kemudahan maintenance
    private $apiBaseUrl = 'https://ti054a01.agussbn.my.id/api';

    /**
     * Menampilkan daftar semua pasien dari API.
     */
    public function index(Request $request): View
    {
        try {
            $response = Http::timeout(15)->get($this->apiBaseUrl . '/pasien');

            if ($response->failed()) {
                throw new \Exception("Gagal mengambil data pasien dari API. Status: " . $response->status());
            }
            $allPatients = collect(data_get($response->json(), 'data', []));

            $filteredPatients = $allPatients->when($request->filled('search'), function ($collection) use ($request) {
                $searchTerm = strtolower($request->input('search'));
                return $collection->filter(function ($item) use ($searchTerm) {
                    return str_contains(strtolower($item['nama_pasien'] ?? ''), $searchTerm) ||
                        str_contains((string)($item['rm'] ?? ''), $searchTerm) ||
                        str_contains((string)($item['nik'] ?? ''), $searchTerm);
                });
            });

            $perPage = 10;
            $currentPage = Paginator::resolveCurrentPage('page');
            $currentPageItems = $filteredPatients->slice(($currentPage - 1) * $perPage, $perPage)->values();

            $patients = new LengthAwarePaginator(
                $currentPageItems,
                $filteredPatients->count(),
                $perPage,
                $currentPage,
                ['path' => Paginator::resolveCurrentPath()]
            );

            return view('admin.patients.index', [
                'patients' => $patients,
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal memproses data pasien: ' . $e->getMessage());
            return view('admin.patients.index', [
                'patients' => new LengthAwarePaginator([], 0, 10),
            ])->with('error', 'Gagal mengambil atau memproses data pasien dari server.');
        }
    }

    /**
     * Menampilkan form untuk membuat pasien baru.
     */
    public function create(): View
    {
        // Method ini hanya bertugas menampilkan view form
        return view('admin.patients.create');
    }

    /**
     * Menyimpan data pasien baru melalui API.
     */
    public function store(Request $request)
    {
        // Validasi input dari form
        $validatedData = $request->validate([
            'rm' => 'required|numeric',
            'nik' => 'required|numeric|digits:16',
            'nama_pasien' => 'required|string|max:255',
            'tgl_lahir' => 'required|date',
            'agama' => 'required|string',
            'kabupaten' => 'required|string',
            'pekerjaan' => 'required|string',
            'jns_kelamin' => 'required|in:pria,perempuan',
            'alamat' => 'required|string',
            'no_hp_pasien' => 'required|string',
            'email_pasien' => 'required|email',
            'gol_darah' => 'required|string',
        ]);

        try {
            $response = Http::timeout(30)->post($this->apiBaseUrl . '/pasien', $validatedData);

            if ($response->successful()) {
                return redirect()->route('admin.patients.index')
                    ->with('success', 'Data pasien baru berhasil disimpan.');
            } else {
                $errorMessage = data_get($response->json(), 'message', 'Gagal menyimpan data ke server API.');
                return redirect()->back()
                    ->with('error', $errorMessage)
                    ->withInput();
            }
        } catch (\Exception $e) {
            Log::error('Koneksi untuk store pasien gagal: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Tidak dapat terhubung ke server API.')
                ->withInput();
        }
    }

    /**
     * Menampilkan form untuk mengedit pasien baru.
     */
    public function edit($patientId)
    {
        $patient = [];
        $error = null;

        try {
            $response = Http::timeout(15)->get($this->apiBaseUrl . '/pasien/' . $patientId);

            if ($response->successful()) {
                $patient = data_get($response->json(), 'data', []);
                if (empty($patient)) {
                    $error = "Data pasien dengan ID {$patientId} tidak ditemukan.";
                }
            } else {
                $error = "Gagal mengambil detail pasien. Status: " . $response->status();
                Log::error($error, ['response' => $response->body()]);
            }
        } catch (\Exception $e) {
            $error = "Tidak dapat terhubung ke server API.";
            Log::error('Koneksi untuk edit pasien gagal: ' . $e->getMessage());
        }

        return view('admin.patients.edit', compact('patient', 'error'));
    }
    /**
     * Mengupdate data pasien yang sudah ada melalui API.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|int  $patientId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $patientId)
    {
        $validatedData = $request->validate([
            'rm' => 'required|numeric',
            'nik' => 'required|numeric|digits:16',
            'nama_pasien' => 'required|string|max:255',
            'tgl_lahir' => 'required|date',
            'agama' => 'required|string',
            'kabupaten' => 'required|string',
            'pekerjaan' => 'required|string',
            'jns_kelamin' => 'required|in:pria,perempuan',
            'alamat' => 'required|string',
            'no_hp_pasien' => 'required|string',
            'email_pasien' => 'required|email',
            'gol_darah' => 'required|string',
        ]);

        try {
            $apiUrl = $this->apiBaseUrl . '/pasien/' . $patientId;
            $response = Http::timeout(15)->put($apiUrl, $validatedData);

            if ($response->ok()) {
                return redirect()->route('admin.patients.index')
                    ->with('success', 'Data pasien berhasil diperbarui.');
            } else {
                $errorMessage = data_get($response->json(), 'message', 'Gagal memperbarui data di server API.');

                return redirect()->back()
                    ->with('error', $errorMessage)
                    ->withInput();
            }
        } catch (\Exception $e) {
            // 4. Tangani jika koneksi ke server API gagal total
            Log::error('Koneksi untuk update pasien gagal: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Tidak dapat terhubung ke server API. Mohon coba lagi nanti.')
                ->withInput();
        }
    }
    /**
     * Menghapus data pasien melalui API.
     */
    public function destroy($patientId)
    {
        try {
            $response = Http::timeout(15)->delete($this->apiBaseUrl . '/pasien/' . $patientId);

            if ($response->ok()) {
                return redirect()->route('admin.patients.index')
                    ->with('success', 'Data pasien berhasil dihapus.');
            } else {
                $errorMessage = data_get($response->json(), 'message', 'Gagal menghapus data dari server API.');
                return redirect()->route('admin.patients.index')
                    ->with('error', $errorMessage);
            }
        } catch (\Exception $e) {
            Log::error('Koneksi untuk delete pasien gagal: ' . $e->getMessage());
            return redirect()->route('admin.patients.index')
                ->with('error', 'Tidak dapat terhubung ke server API.');
        }
    }

    // Anda bisa menambahkan method show, edit, update dengan logika yang serupa
}
