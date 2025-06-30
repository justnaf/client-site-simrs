<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PriceDokter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class DokterController extends Controller
{
    private $apiBaseUrl = 'https://ti054a01.agussbn.my.id/api';

    /**
     * Menampilkan daftar dokter dari API yang digabungkan dengan harga dari DB lokal.
     */
    public function index(Request $request): View
    {
        try {
            $responseDokter = Http::timeout(15)->get($this->apiBaseUrl . '/dokter');
            if ($responseDokter->failed()) {
                throw new \Exception("Gagal mengambil data dokter dari API. Status: " . $responseDokter->status());
            }

            $apiDokters = collect(data_get($responseDokter->json(), 'data', []));
            $priceLookup = PriceDokter::all()->keyBy('id_dokter');

            $enrichedDokters = $apiDokters->map(function ($apiDokter) use ($priceLookup) {
                $localPriceData = $priceLookup->get($apiDokter['id_dokter']);
                $apiDokter['price'] = $localPriceData ? $localPriceData->price : null;
                $apiDokter['desc'] = $localPriceData ? $localPriceData->desc : 'Harga belum diatur';
                return $apiDokter;
            });

            $filteredDokters = $enrichedDokters->when($request->filled('search'), function ($collection) use ($request) {
                $searchTerm = strtolower($request->input('search'));
                return $collection->filter(function ($item) use ($searchTerm) {
                    return str_contains(strtolower($item['nama_dokter'] ?? ''), $searchTerm) ||
                        str_contains(strtolower($item['desc'] ?? ''), $searchTerm);
                });
            });

            $perPage = 10;
            $currentPage = Paginator::resolveCurrentPage('page');
            $currentPageItems = $filteredDokters->slice(($currentPage - 1) * $perPage, $perPage)->values();

            $doctors = new LengthAwarePaginator(
                $currentPageItems,
                $filteredDokters->count(),
                $perPage,
                $currentPage,
                ['path' => Paginator::resolveCurrentPath()]
            );

            return view('admin.data.dokter.index', ['doctors' => $doctors]);
        } catch (\Exception $e) {
            Log::error('Gagal memproses data dokter dan harga: ' . $e->getMessage());
            $doctors = new LengthAwarePaginator([], 0, 10);
            return view('admin.data.dokter.index', compact('doctors'))
                ->with('error', 'Gagal mengambil atau memproses data dari server.');
        }
    }

    /**
     * Menampilkan form untuk menambahkan harga baru untuk seorang dokter.
     */
    public function create()
    {
        try {
            $responseDokter = Http::timeout(15)->get($this->apiBaseUrl . '/dokter');
            if ($responseDokter->failed()) {
                throw new \Exception("Gagal mengambil daftar dokter dari API.");
            }
            $doctors = data_get($responseDokter->json(), 'data', []);
            return view('admin.data.dokter.create', compact('doctors'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->route('admin.dokters.index')
                ->with('error', 'Gagal memuat data dokter untuk form.');
        }
    }

    /**
     * Menyimpan harga dokter baru ke database lokal.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_dokter' => 'required|integer|unique:price_dokters,id_dokter',
            'price' => 'required|numeric|min:0',
            'desc' => 'nullable|string|max:255',
        ], ['id_dokter.unique' => 'Harga untuk dokter ini sudah ada. Silakan edit.']);

        PriceDokter::create($validatedData);

        return redirect()->route('admin.dokters.index')
            ->with('success', 'Harga untuk dokter berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit harga dokter.
     */
    public function edit($dokterId)
    {
        try {
            $priceDokter = PriceDokter::where('id_dokter', $dokterId)->firstOrFail();

            $responseDokter = Http::timeout(15)->get($this->apiBaseUrl . '/dokter/' . $dokterId);
            if ($responseDokter->failed()) {
                throw new \Exception("Gagal mengambil detail dokter dari API.");
            }
            $apiDokter = data_get($responseDokter->json(), 'data', []);

            return view('admin.data.dokter.edit', compact('priceDokter', 'apiDokter'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->route('admin.dokters.index')
                ->with('error', 'Gagal memuat data untuk form edit.');
        }
    }

    /**
     * Mengupdate harga dokter di database lokal.
     */
    public function update(Request $request, $dokterId)
    {
        $priceDokter = PriceDokter::where('id_dokter', $dokterId)->firstOrFail();
        $validatedData = $request->validate([
            'price' => 'required|numeric|min:0',
            'desc' => 'nullable|string|max:255',
        ]);
        $priceDokter->update($validatedData);
        return redirect()->route('admin.dokters.index')
            ->with('success', 'Harga untuk dokter berhasil diperbarui.');
    }
}
