<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class DrugController extends Controller
{
    private $apiBaseUrl = 'https://ti054a04.agussbn.my.id/api/admin';

    public function index(Request $request)
    {
        try {
            $response = Http::timeout(15)->get($this->apiBaseUrl . '/obat');

            if ($response->failed()) {
                throw new \Exception("Gagal mengambil data obat dari API. Status: " . $response->status());
            }

            $allObat = collect(data_get($response->json(), 'data', []));

            $filteredObat = $allObat->when($request->filled('search'), function ($collection) use ($request) {
                $searchTerm = strtolower($request->input('search'));
                return $collection->filter(function ($item) use ($searchTerm) {
                    return str_contains(strtolower($item['nama_obat'] ?? ''), $searchTerm) ||
                        str_contains(strtolower($item['kode_obat'] ?? ''), $searchTerm);
                });
            });

            $perPage = 10;
            $currentPage = Paginator::resolveCurrentPage('page');
            $currentPageItems = $filteredObat->slice(($currentPage - 1) * $perPage, $perPage)->values();

            $dataObat = new LengthAwarePaginator(
                $currentPageItems,
                $filteredObat->count(),
                $perPage,
                $currentPage,
                ['path' => Paginator::resolveCurrentPath()]
            );

            return view('admin.obat.index', [
                'dataObat' => $dataObat,
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal memproses data obat: ' . $e->getMessage());

            $dataObat = new LengthAwarePaginator([], 0, 15);

            return view('admin.obat.index', [
                'dataObat' => $dataObat,
            ])->with('error', 'Gagal mengambil atau memproses data obat dari server.');
        }
    }
}
