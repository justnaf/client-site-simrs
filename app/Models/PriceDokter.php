<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceDokter extends Model
{
    protected $fillable = ['id_dokter', 'price', 'desc'];
}
