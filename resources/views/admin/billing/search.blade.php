<x-admin-layout>
    <x-slot name="title">Cari Tagihan</x-slot>
    <h2 class="text-3xl font-bold text-sky-800 mb-6">Pembayaran & Tagihan</h2>

    @include('admin.includes.alert')

    <div class="bg-white p-6 rounded-lg shadow-md border max-w-md mx-auto">
        <form action="{{ route('admin.billing.show') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="no_invoice" class="block text-sm font-medium text-gray-700">Masukkan Nomor Invoice</label>
                <input type="text" name="no_invoice" id="no_invoice" value="{{ old('no_invoice') }}" required placeholder="Contoh: BPJS-ABC12345" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 text-lg focus:ring-sky-500 focus:border-sky-500">
                @error('no_invoice') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>
            <div class="text-right">
                <button type="submit" class="w-full px-4 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700 font-bold">
                    Cari Tagihan
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
