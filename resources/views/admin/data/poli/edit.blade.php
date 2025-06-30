<x-admin-layout>
    <x-slot name="title">
        Edit Harga Poli
    </x-slot>

    <h2 class="text-3xl font-bold text-sky-800 mb-6"><a href="{{ route('admin.polis.index') }}"> Data Poli</a> > Edit Harga</h2>

    @include('admin.includes.alert')

    @if (isset($error))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <strong class="font-bold">Error!</strong>
        <span class="block sm:inline">{{ $error }}</span>
    </div>
    @elseif (!empty($pricePoli))
    <div class="bg-white p-6 rounded-lg shadow-md border">
        <form action="{{ route('admin.polis.update', $pricePoli->id_poli) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div class="flex items-center">
                <label class="w-48 font-medium text-gray-700">Nama Poli</label>
                <span class="mr-4">:</span>
                <input type="text" value="{{ $apiPoli['nama_poli'] ?? 'Memuat...' }}" readonly class="flex-1 p-2 border-gray-300 rounded-md shadow-sm bg-gray-100 cursor-not-allowed">
            </div>

            <div class="flex items-center">
                <label for="price" class="w-48 font-medium text-gray-700">Harga Layanan (Rp)</label>
                <span class="mr-4">:</span>
                <input type="number" name="price" id="price" value="{{ old('price', $pricePoli->price) }}" required class="flex-1 p-2 border-gray-300 rounded-md shadow-sm focus:ring-sky-500 focus:border-sky-500">
                @error('price') <span class="text-red-500 text-sm ml-2">{{ $message }}</span> @enderror
            </div>

            <div class="flex items-center">
                <label for="desc" class="w-48 font-medium text-gray-700">Deskripsi (Opsional)</label>
                <span class="mr-4">:</span>
                <input type="text" name="desc" id="desc" value="{{ old('desc', $pricePoli->desc) }}" class="flex-1 p-2 border-gray-300 rounded-md shadow-sm focus:ring-sky-500 focus:border-sky-500">
                @error('desc') <span class="text-red-500 text-sm ml-2">{{ $message }}</span> @enderror
            </div>

            <div class="flex justify-end pt-4">
                <a href="{{ route('admin.polis.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">Batal</a>
                <button type="submit" class="ml-3 px-4 py-2 bg-[#76A9C5] hover:bg-[#6A98B4] text-white font-bold rounded-lg">Update Harga</button>
            </div>
        </form>
    </div>
    @endif
</x-admin-layout>
