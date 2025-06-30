<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PaymentTypeController extends Controller
{
    public function index()
    {
        $datas = PaymentType::all();
        return view('admin.paymentType.index', [
            'datas' => $datas,
        ]);
    }

    public function create()
    {
        return view('admin.paymentType.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'code' => 'required|string|max:10|unique:payment_types,code',
            'name' => 'required|string|max:255',
            'discount' => 'nullable|string|max:255',
        ]);

        $dataToStore = [
            'code' => $validatedData['code'],
            'name' => $validatedData['name'],
            'discount' => $validatedData['discount'] ?? '0',
        ];

        PaymentType::create($dataToStore);

        return redirect()->route('admin.payment.type.index')->with('success', 'Jenis pendaftaran berhasil ditambahkan.');
    }

    public function edit(PaymentType $paymentType)
    {
        // Laravel's Route Model Binding akan otomatis mencari PaymentType berdasarkan ID dari URL.
        // Variabel $paymentType akan berisi data yang akan diedit.
        return view('admin.paymentType.edit', [
            'paymentType' => $paymentType
        ]);
    }

    /**
     * BARU: Mengupdate data di database.
     */
    public function update(Request $request, PaymentType $paymentType): RedirectResponse
    {
        // 2. Validasi data yang masuk
        $validatedData = $request->validate([
            // Rule 'unique' perlu mengabaikan record yang sedang diedit
            'code' => ['required', 'string', 'max:10', Rule::unique('payment_types')->ignore($paymentType->id)],
            'name' => 'required|string|max:255',
            'discount' => 'nullable|numeric|min:0|max:100',
        ]);

        // Karena file icon tidak ada di fillable Anda, kita hanya update ini
        $dataToUpdate = [
            'code' => $validatedData['code'],
            'name' => $validatedData['name'],
            'discount' => $validatedData['discount'] ?? 0,
        ];

        // 3. Update data di database
        $paymentType->update($dataToUpdate);

        // 4. Redirect kembali dengan pesan sukses
        return redirect()->route('admin.payment.type.index')->with('success', 'Jenis pembayaran berhasil diperbarui.');
    }
    public function destroy(PaymentType $payment): RedirectResponse
    {
        try {
            if ($payment->icon_path) {
                Storage::disk('public')->delete($payment->icon_path);
            }

            $payment->delete();

            return redirect()->route('admin.payment.type.index')->with('success', 'Data berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Gagal menghapus jenis pendaftaran: ' . $e->getMessage());
            return redirect()->route('admin.payment.type.index')->with('error', 'Terjadi kesalahan saat menghapus data');
        }
    }
}
