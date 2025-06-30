<?php
// app/Services/BillingService.php

namespace App\Services;

use App\Models\Registration;
use App\Models\PriceDokter;
use App\Models\PricePoli;
use App\Models\PriceAdditional;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Transaction;


class BillingService
{
    // Definisikan semua base URL API Anda di sini
    protected $apiBaseUrlV1 = 'https://ti054a01.agussbn.my.id/api';
    protected $apiBaseUrlV2 = 'https://ti054a02.agussbn.my.id/api';
    protected $apiBaseUrlV3 = 'https://ti054a04.agussbn.my.id/api/admin';

    /**
     * Menghitung rincian tagihan lengkap berdasarkan nomor invoice.
     * @param string $invoiceNumber
     * @return array
     */
    public function calculateBillByInvoice(string $invoiceNumber): array
    {
        // Temukan no_registrasi dari invoice
        $unicode = Str::after($invoiceNumber, '-');
        if (!$unicode) {
            throw new \Exception("Format nomor invoice tidak valid.");
        }
        $localRegistration = Registration::with('paymentType')->where('unicode', $unicode)->firstOrFail();
        $no_registrasi = $localRegistration->no_registrasi;


        // 1a. Ambil data pendaftaran dari API
        $pendaftaranResponse = Http::timeout(30)->get($this->apiBaseUrlV1 . '/pendaftaran');
        if ($pendaftaranResponse->failed()) throw new \Exception("Gagal mengambil data pendaftaran dari API.");
        $pendaftaran = collect(data_get($pendaftaranResponse->json(), 'data', []))->firstWhere('no_registrasi', $no_registrasi);
        if (!$pendaftaran) throw new \Exception("Detail pendaftaran untuk No. {$no_registrasi} tidak ditemukan di API.");

        // 1b. Ambil data e-resep dari API
        $resepResponse = Http::timeout(30)->get($this->apiBaseUrlV2 . '/e-resep');
        if ($resepResponse->failed()) throw new \Exception("Gagal mengambil data e-resep dari API.");
        $resepDetails = collect(data_get($resepResponse->json(), []))->where('no_registrasi', $no_registrasi)->pluck('details')->flatten(1);

        // 1c. Ambil data master obat dari API V3
        $obatResponse = Http::get($this->apiBaseUrlV3 . '/obat');
        if ($obatResponse->failed()) throw new \Exception("Gagal mengambil data master obat dari API.");
        $obatPriceLookup = collect(data_get($obatResponse->json(), 'data', []))->keyBy('id_obat');

        // 1d. Ambil data harga dari database LOKAL
        $hargaPoli = PricePoli::where('id_poli', $pendaftaran['id_poli'])->first();
        $hargaDokter = PriceDokter::where('id_dokter', $pendaftaran['id_dokter'])->first();
        $hargaTambahan = PriceAdditional::where('no_registrasi', $no_registrasi)->get();
        $discountPercentage = $localRegistration->paymentType->discount ?? 0;

        // --- LANGKAH 2: LAKUKAN KALKULASI ---

        $totalHargaObat = 0;
        $itemsObat = [];
        foreach ($resepDetails as $detailObat) {
            $obatInfo = $obatPriceLookup->get($detailObat['id_obat']);
            if ($obatInfo) {
                $hargaSatuan = (float) $obatInfo['harga_jual'];
                $subtotal = ($detailObat['jumlah'] ?? 1) * $hargaSatuan;
                $itemsObat[] = ['name' => $obatInfo['nama_obat'], 'quantity' => $detailObat['jumlah'], 'price' => $hargaSatuan, 'subtotal' => $subtotal];
                $totalHargaObat += $subtotal;
            }
        }

        $itemsLayanan = [];
        $totalHargaLayanan = 0;
        if ($hargaPoli) {
            $itemsLayanan[] = ['name' => $hargaPoli->desc ?: "Layanan {$pendaftaran['nama_poli']}", 'price' => $hargaPoli->price];
            $totalHargaLayanan += $hargaPoli->price;
        }
        if ($hargaDokter) {
            $itemsLayanan[] = ['name' => $hargaDokter->desc ?: "Jasa Konsultasi {$pendaftaran['nama_dokter']}", 'price' => $hargaDokter->price];
            $totalHargaLayanan += $hargaDokter->price;
        }
        foreach ($hargaTambahan as $item) {
            $itemsLayanan[] = ['name' => $item->desc, 'price' => $item->price];
            $totalHargaLayanan += $item->price;
        }

        $subTotal = $totalHargaObat + $totalHargaLayanan;
        $nilaiDiskon = ($subTotal * $discountPercentage) / 100;
        $grandTotal = $subTotal - $nilaiDiskon;

        // --- LANGKAH 3: KEMBALIKAN DATA TERSTRUKTUR ---
        return [
            'pendaftaran' => $pendaftaran,
            'localTransaction' => Transaction::firstOrCreate(
                ['no_registrasi' => $no_registrasi],
                ['unicode' => $localRegistration->unicode, 'total_amount' => $grandTotal]
            ),
            'items_obat' => $itemsObat,
            'items_layanan' => $itemsLayanan,
            'subtotal' => $subTotal,
            'discount' => ['percent' => $discountPercentage, 'amount' => $nilaiDiskon],
            'grand_total' => $grandTotal,
        ];
    }

    public function processPayment(int $transactionId, float $amountPaid, ?string $notes): Transaction
    {
        // Temukan transaksi yang ada atau gagal jika tidak ditemukan
        $transaction = Transaction::findOrFail($transactionId);

        // Akumulasi total yang sudah dibayar
        $newPaidAmount = $transaction->paid_amount + $amountPaid;

        $changeAmount = 0;
        $newStatus = $transaction->status;

        // Tentukan status baru dan hitung kembalian
        if ($newPaidAmount >= $transaction->total_amount) {
            // Jika pembayaran melebihi atau sama dengan total tagihan
            $newStatus = 'Lunas';
            $changeAmount = $newPaidAmount - $transaction->total_amount;
        } elseif ($newPaidAmount > 0) {
            // Jika sudah ada pembayaran tapi belum lunas
            $newStatus = 'Dibayar Sebagian';
        }

        // Gabungkan catatan baru dengan yang lama
        $newNotes = $transaction->notes;
        if ($notes) {
            $newNotes .= "\n" . "[Pembayaran " . now()->toDateTimeString() . "]: " . $notes;
        }

        // Update record transaksi di database
        $transaction->update([
            'paid_amount' => $newPaidAmount,
            'change_amount' => $changeAmount,
            'status' => $newStatus,
            'notes' => trim($newNotes),
        ]);

        return $transaction;
    }
}
