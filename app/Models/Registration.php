<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PaymentType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Registration extends Model
{
    protected $fillable = ['no_registrasi', 'unicode', 'payment_type_id'];

    /**
     * Menambahkan atribut virtual 'nomor_invoice' ke model.
     * Atribut ini akan otomatis ditambahkan saat model diubah menjadi array atau JSON.
     *
     * @var array
     */
    protected $appends = ['nomor_invoice'];

    /**
     * Mendefinisikan relasi: Satu JenisPendaftaran bisa memiliki banyak Pendaftaran (Registration).
     */
    public function paymentType(): BelongsTo
    {
        return $this->belongsTo(PaymentType::class);
    }

    /**
     * Accessor untuk atribut 'nomor_invoice'.
     * Method ini akan otomatis terpanggil ketika kita mengakses $registration->nomor_invoice.
     *
     * @return string
     */
    public function getNomorInvoiceAttribute(): string
    {
        // Mengambil kode dari relasi, dengan fallback 'KODE' jika relasi tidak ada/gagal dimuat
        $code = optional($this->paymentType)->code ?? 'KODE';

        return "{$code}-{$this->unicode}";
    }
}
