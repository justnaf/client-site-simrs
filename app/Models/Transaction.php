<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = ['no_registrasi', 'unicode', 'total_amount', 'paid_amount', 'change_amount', 'status', 'notes'];
    public function registration(): BelongsTo
    {
        // Karena kita tidak menggunakan konvensi 'registration_id' sebagai foreign key,
        // kita perlu menentukannya secara manual.
        // Format: belongsTo(ModelTujuan, foreign_key_di_tabel_ini, owner_key_di_tabel_tujuan)
        return $this->belongsTo(Registration::class, 'no_registrasi', 'no_registrasi');
    }
}
