<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\BillingService; // Import service Anda
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Transaction;

class BillingController extends Controller
{
    protected $billingService;

    public function __construct(BillingService $billingService)
    {
        $this->billingService = $billingService;
    }

    // Menampilkan halaman input invoice
    public function showSearchForm()
    {
        return view('admin.billing.search');
    }

    public function showBillingDetails(Request $request)
    {
        $request->validate(['no_invoice' => 'required|string']);
        try {
            $billingData = $this->billingService->calculateBillByInvoice($request->no_invoice);
            return view('admin.billing.detail', ['billing' => $billingData]);
        } catch (\Exception $e) {
            Log::error("Gagal membuat tagihan untuk invoice {$request->no_invoice}: " . $e->getMessage());
            return redirect()->route('admin.billing.search')->with('error', $e->getMessage());
        }
    }
    /**
     * Memproses form pembayaran yang disubmit.
     */
    public function processPayment(Request $request, Transaction $transaction)
    {
        $request->validate([
            'paid_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:255',
        ]);

        try {
            // Panggil service untuk melakukan semua pekerjaan berat
            $this->billingService->processPayment(
                $transaction->id,
                (float) $request->paid_amount,
                $request->notes
            );

            return redirect()->route('admin.billing.search')
                ->with('success', 'Pembayaran berhasil diproses!');
        } catch (\Exception $e) {
            Log::error("Gagal memproses pembayaran untuk transaksi #{$transaction->id}: " . $e->getMessage());

            return redirect()->back()
                ->with('error', "Gagal memproses pembayaran: " . $e->getMessage());
        }
    }
}
