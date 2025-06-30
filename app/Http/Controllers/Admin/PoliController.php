<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PricePoli; // 1. Import model lokal Anda
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class PoliController extends Controller
{
    private $apiBaseUrl = 'https://ti054a01.agussbn.my.id/api';

    /**
     * Menampilkan daftar poli dengan harga yang sudah digabungkan dari API dan DB lokal.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        try {
            // 2. Ambil data dari API dan DB Lokal
            $responsePoli = Http::timeout(15)->get($this->apiBaseUrl . '/poli');
            if ($responsePoli->failed()) {
                throw new \Exception("Gagal mengambil data poli dari API. Status: " . $responsePoli->status());
            }

            $apiPolis = collect(data_get($responsePoli->json(), 'data', []));

            // Buat 'lookup map' dari data harga lokal untuk pencarian cepat
            $priceLookup = PricePoli::all()->keyBy('id_poli');

            // 3. Gabungkan (enrich) data API dengan data harga lokal
            $enrichedPolis = $apiPolis->map(function ($apiPoli) use ($priceLookup) {
                // Cari data harga yang cocok berdasarkan id_poli
                $localPriceData = $priceLookup->get($apiPoli['id_poli']);

                // Tambahkan harga dan deskripsi ke array data poli dari API
                $apiPoli['price'] = $localPriceData ? $localPriceData->price : null; // Beri null jika tidak ada harga
                $apiPoli['desc'] = $localPriceData ? $localPriceData->desc : 'Harga belum diatur';

                return $apiPoli;
            });

            // 4. Terapkan filter pencarian pada data yang sudah digabungkan
            $filteredPolis = $enrichedPolis->when($request->filled('search'), function ($collection) use ($request) {
                $searchTerm = strtolower($request->input('search'));
                return $collection->filter(function ($item) use ($searchTerm) {
                    // Cari berdasarkan nama poli atau deskripsinya
                    return str_contains(strtolower($item['nama_poli'] ?? ''), $searchTerm) ||
                        str_contains(strtolower($item['desc'] ?? ''), $searchTerm);
                });
            });

            // 5. Buat Paginator secara manual
            $perPage = 10;
            $currentPage = Paginator::resolveCurrentPage('page');
            $currentPageItems = $filteredPolis->slice(($currentPage - 1) * $perPage, $perPage)->values();

            $polis = new LengthAwarePaginator(
                $currentPageItems,
                $filteredPolis->count(),
                $perPage,
                $currentPage,
                ['path' => Paginator::resolveCurrentPath()]
            );

            return view('admin.data.poli.index', [
                'polis' => $polis,
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal memproses data poli dan harga: ' . $e->getMessage());
            $polis = new LengthAwarePaginator([], 0, 10);
            return view('admin.data.poli.index', compact('polis'))
                ->with('error', 'Gagal mengambil atau memproses data dari server.');
        }
    }
    /**
     * Menampilkan form untuk menambahkan harga baru untuk sebuah poli.
     */
    public function create(): View
    {
        $polis = [];
        $error = null;

        try {
            // Ambil daftar poli dari API untuk mengisi dropdown
            $responsePoli = Http::timeout(15)->get($this->apiBaseUrl . '/poli');
            if ($responsePoli->failed()) {
                throw new \Exception("Gagal mengambil daftar poli dari API.");
            }
            $polis = data_get($responsePoli->json(), 'data', []);
        } catch (\Exception $e) {
            $error = 'Gagal memuat data pendukung dari server API.';
            Log::error($e->getMessage());
        }

        return view('admin.data.poli.create', compact('polis', 'error'));
    }

    /**
     * Menyimpan harga poli baru ke database lokal.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            // id_poli harus ada dan unik di tabel price_polis (satu poli hanya punya satu harga)
            'id_poli' => 'required|integer|unique:price_polis,id_poli',
            'price' => 'required|numeric|min:0',
            'desc' => 'nullable|string|max:255',
        ], [
            'id_poli.unique' => 'Harga untuk poli ini sudah ada. Silakan edit dari halaman daftar.'
        ]);

        PricePoli::create($validatedData);

        return redirect()->route('admin.polis.index')
            ->with('success', 'Harga untuk poli berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit harga poli.
     */
    public function edit($poliId): View
    {
        // Cari harga lokal berdasarkan id_poli
        $pricePoli = PricePoli::where('id_poli', $poliId)->firstOrFail();
        $apiPoli = [];
        $error = null;

        try {
            // Ambil detail nama poli dari API untuk ditampilkan
            $responsePoli = Http::timeout(15)->get($this->apiBaseUrl . '/poli/' . $poliId);
            if ($responsePoli->failed()) {
                throw new \Exception("Gagal mengambil detail poli dari API.");
            }
            $apiPoli = data_get($responsePoli->json(), 'data', []);
        } catch (\Exception $e) {
            $error = 'Gagal memuat detail poli dari server API.';
            Log::error($e->getMessage());
        }

        return view('admin.data.poli.edit', compact('pricePoli', 'apiPoli', 'error'));
    }

    /**
     * Mengupdate harga poli di database lokal.
     */
    public function update(Request $request, $poliId)
    {
        // Temukan record yang akan diupdate
        $pricePoli = PricePoli::where('id_poli', $poliId)->firstOrFail();

        $validatedData = $request->validate([
            'price' => 'required|numeric|min:0',
            'desc' => 'nullable|string|max:255',
        ]);

        $pricePoli->update($validatedData);

        return redirect()->route('admin.polis.index')
            ->with('success', 'Harga untuk poli berhasil diperbarui.');
    }
}
