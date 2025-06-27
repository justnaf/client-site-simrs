<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
            'file_icon' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        $dataToStore = [
            'code' => $validatedData['code'],
            'name' => $validatedData['name'],
        ];

        if ($request->hasFile('file_icon')) {
            $file = $request->file('file_icon');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('icons', $fileName, 'public');
            $dataToStore['icon_path'] = $path;
        }
        PaymentType::create($dataToStore);

        return redirect()->route('admin.payment.type.index')->with('success', 'Jenis pendaftaran berhasil ditambahkan.');
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
