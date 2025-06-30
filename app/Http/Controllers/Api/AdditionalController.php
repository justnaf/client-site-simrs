<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdditionalResource;
use App\Rules\ExistsInPendaftaranApi;
use App\Models\PriceAdditional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdditionalController extends Controller
{
    /**
     * Menampilkan daftar biaya tambahan, bisa difilter by no_registrasi.
     */
    public function index(Request $request)
    {
        $query = PriceAdditional::query();

        if ($request->filled('no_registrasi')) {
            $query->where('no_registrasi', $request->no_registrasi);
        }

        $prices = $query->latest()->paginate(15);

        return AdditionalResource::collection($prices);
    }

    /**
     * Menyimpan biaya tambahan baru.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'no_registrasi' => ['required', 'integer', new ExistsInPendaftaranApi],
            'price' => 'required|numeric|min:0',
            'desc' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $price = PriceAdditional::create($validator->validated());

        return (new AdditionalResource($price))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Menampilkan satu data biaya tambahan spesifik.
     */
    public function show(PriceAdditional $additional)
    {
        return new AdditionalResource($additional);
    }

    /**
     * Mengupdate data biaya tambahan.
     */
    public function update(Request $request, PriceAdditional $additional)
    {
        $validator = Validator::make($request->all(), [
            'price' => 'sometimes|required|numeric|min:0',
            'desc' => 'sometimes|nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $additional->update($validator->validated());

        return new AdditionalResource($additional);
    }

    /**
     * Menghapus data biaya tambahan.
     */
    public function destroy(PriceAdditional $additional)
    {
        $additional->delete();

        return response()->json(null, 204);
    }
}
