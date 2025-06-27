<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Registration;

class PaymentType extends Model
{
    protected $fillable = ['code', 'name', 'icon_path'];

    /**
     * Mendefinisikan relasi: Satu JenisPendaftaran bisa memiliki banyak Pendaftaran (Registration).
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }
}
