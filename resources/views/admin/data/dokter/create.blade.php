<x-admin-layout>
    <x-slot name="title">Tambah Harga Dokter</x-slot>
    <h2 class="text-3xl font-bold text-sky-800 mb-6"><a href="{{ route('admin.dokters.index') }}">Data Dokter</a> > Tambah Harga</h2>
    @if (isset($error))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">...</div>
    @else
    <div class="bg-white p-6 rounded-lg shadow-md border">
        <form action="{{ route('admin.dokters.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="flex items-center">
                <label for="id_dokter" class="w-48 font-medium text-gray-700">Pilih Dokter</label>
                <span class="mr-4">:</span>
                <select id="id_dokter" name="id_dokter" required class="flex-1 border-gray-300 rounded-md shadow-sm p-2">
                    <option value="">-- Pilih Dokter yang Akan Diberi Harga --</option>
                    @foreach ($doctors as $doctor)
                    <option value="{{ $doctor['id_dokter'] }}" {{ old('id_dokter') == $doctor['id_dokter'] ? 'selected' : '' }}>
                        {{ $doctor['nama_dokter'] }}
                    </option>
                    @endforeach
                </select>
                @error('id_dokter') <span class="text-red-500 text-sm ml-2">{{ $message }}</span> @enderror
            </div>
            <div class="flex items-center">
                <label for="price" class="w-48 font-medium text-gray-700">Harga Layanan (Rp)</label>
                <span class="mr-4">:</span>
                <input type="number" name="price" id="price" value="{{ old('price') }}" required placeholder="Contoh: 150000" class="flex-1 p-2 border-gray-300 rounded-md shadow-sm focus:ring-sky-500 focus:border-sky-500">
                @error('price') <span class="text-red-500 text-sm ml-2">{{ $message }}</span> @enderror
            </div>

            <div class="flex items-center">
                <label for="desc" class="w-48 font-medium text-gray-700">Deskripsi (Opsional)</label>
                <span class="mr-4">:</span>
                <input type="text" name="desc" id="desc" value="{{ old('desc') }}" placeholder="Contoh: Konsultasi + Tindakan Ringan" class="flex-1 p-2 border-gray-300 rounded-md shadow-sm focus:ring-sky-500 focus:border-sky-500">
                @error('desc') <span class="text-red-500 text-sm ml-2">{{ $message }}</span> @enderror
            </div>
            <div class="flex justify-end pt-4">
                <a href="{{ route('admin.dokters.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">Batal</a>
                <button type="submit" class="ml-3 px-4 py-2 bg-[#76A9C5] hover:bg-[#6A98B4] text-white font-bold rounded-lg">Simpan Harga</button>
            </div>
        </form>
    </div>
    @endif
</x-admin-layout>
