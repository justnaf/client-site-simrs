<x-admin-layout>
    <x-slot name="title">
        Data Poli
    </x-slot>

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold text-sky-800">Daftar Harga Layanan Poli</h2>
        <a href="{{ route('admin.polis.create') }}" class="px-4 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700">
            + Tambah/Edit Harga
        </a>
    </div>

    <form action="{{ route('admin.polis.index') }}" method="GET" class="mb-4">
        <div class="flex items-center">
            <input name="search" type="text" placeholder="Cari nama poli atau deskripsi..." class="w-full md:w-1/3 px-4 py-2 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-sky-500" value="{{ request('search') }}">
            <button type="submit" class="px-4 py-2 bg-sky-600 text-white rounded-r-lg hover:bg-sky-700">
                Cari
            </button>
        </div>
    </form>

    @include('admin.includes.alert')

    <div class="overflow-x-auto max-w-7xl px-1 py-4">
        <table class="w-full bg-white border border-gray-300 rounded-xl overflow-hidden shadow-md">
            <thead class="bg-[#76A9C5] text-white">
                <tr>
                    <th class="p-3 text-left">ID Poli</th>
                    <th class="p-3 text-left">Nama Poli</th>
                    <th class="p-3 text-left">Deskripsi Harga</th>
                    <th class="p-3 text-right">Harga</th>
                    <th class="p-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($polis as $poli)
                <tr class="odd:bg-white even:bg-gray-100">
                    <td class="p-3">{{ $poli['id_poli'] }}</td>
                    <td class="p-3 font-medium">{{ $poli['nama_poli'] }}</td>
                    <td class="p-3 text-gray-600">{{ $poli['desc'] }}</td>
                    <td class="p-3 text-right font-semibold">
                        {{-- Tampilkan harga jika ada, jika tidak tampilkan strip --}}
                        @if (!is_null($poli['price']))
                        Rp {{ number_format($poli['price'], 0, ',', '.') }}
                        @else
                        -
                        @endif
                    </td>
                    <td class="p-3 text-center">
                        {{-- Tombol ini akan mengarah ke form edit untuk HARGA, bukan untuk data poli dari API --}}
                        <a href="{{ route('admin.polis.edit', $poli['id_poli']) }}" class="text-sky-500 hover:text-sky-700 font-semibold">
                            Edit Harga
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center p-4 text-gray-500">
                        Data poli tidak ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $polis->withQueryString()->links() }}
    </div>

</x-admin-layout>
