<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class ExistsInPendaftaranApi implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $apiBaseUrl = 'https://ti054a01.agussbn.my.id/api';

        try {
            $response = Http::timeout(20)->get($apiBaseUrl . '/pendaftaran');

            if ($response->failed()) {
                $fail('Tidak dapat memverifikasi no registrasi saat ini.');
                return;
            }

            $allRegistrationsData = data_get($response->json(), 'data', []);
            $allRegistrationNumbers = collect($allRegistrationsData)->pluck('no_registrasi');


            if (!$allRegistrationNumbers->contains($value)) {
                $fail("No registrasi '{$value}' tidak valid atau tidak ditemukan di sistem pendaftaran.");
            }
        } catch (\Exception $e) {
            $fail('Gagal terhubung ke server pendaftaran untuk validasi.');
        }
    }
}
