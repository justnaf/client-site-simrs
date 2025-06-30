<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction; // Import model Transaction
use Illuminate\View\View;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    private $apiBaseUrl = 'https://ti054a01.agussbn.my.id/api';

    public function index()
    {
        // --- Kalkulasi untuk Kartu Dashboard ---

        // 1. Total Pemasukan: Ambil jumlah dari kolom 'paid_amount' di tabel transactions LOKAL
        $totalRevenue = Transaction::sum('paid_amount');


        // 2. Transaksi Hari Ini: Hitung jumlah record di tabel transactions LOKAL yang dibuat hari ini
        $todayTransactionsCount = Transaction::whereDate('created_at', today())->count();


        // 3. Pasien Hari Ini: Hitung dari API pendaftaran berdasarkan 'tgl_kunjungan'
        $todayPatientsCount = 0; // Nilai default jika API gagal
        try {
            $response = Http::timeout(15)->get($this->apiBaseUrl . '/pendaftaran');
            if ($response->successful()) {
                $allRegistrations = collect(data_get($response->json(), 'data', []));
                $todayDateString = today()->toDateString(); // Format: YYYY-MM-DD

                $todayPatientsCount = $allRegistrations->filter(function ($item) use ($todayDateString) {
                    // Cek jika tgl_kunjungan dimulai dengan tanggal hari ini
                    return str_starts_with($item['tgl_kunjungan'] ?? '', $todayDateString);
                })->count();
            }
        } catch (\Exception $e) {
            Log::error('Gagal mengambil data pendaftaran untuk dashboard: ' . $e->getMessage());
            // Biarkan $todayPatientsCount tetap 0 jika terjadi error
        }

        // Kirim semua data yang sudah dihitung ke view
        return view('admin.dashboard', [
            'totalRevenue' => $totalRevenue,
            'todayTransactionsCount' => $todayTransactionsCount,
            'todayPatientsCount' => $todayPatientsCount,
        ]);
    }

    /**
     * Menampilkan daftar riwayat transaksi dengan pencarian dan pagination.
     */
    public function transaction(Request $request): View
    {
        // 1. Mulai query dari model Transaction
        // Eager load relasi 'paymentType' untuk efisiensi saat memanggil accessor 'nomor_invoice'
        $query = Transaction::with('registration.paymentType');

        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            // Pencarian sekarang bisa lebih canggih dengan whereHas
            $query->where(function ($q) use ($searchTerm) {
                $q->where('no_registrasi', 'like', "%{$searchTerm}%")
                    ->orWhere('unicode', 'like', "%{$searchTerm}%")
                    ->orWhereHas('registration', function ($subq) use ($searchTerm) {
                        // Mencari di dalam relasi (contoh: mencari berdasarkan kode payment type)
                        $subq->whereHas('paymentType', function ($subq2) use ($searchTerm) {
                            $subq2->where('code', 'like', "%{$searchTerm}%");
                        });
                    });
            });
        }

        $transactions = $query->latest()->paginate(15);

        return view('admin.transaksi.index', compact('transactions'));
    }
}
