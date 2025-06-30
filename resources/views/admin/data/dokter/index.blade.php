<x-admin-layout>
    <x-slot name="title">Data Dokter</x-slot>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold text-sky-800">Daftar Harga Layanan Dokter</h2>
        <a href="{{ route('admin.dokters.create') }}" class="px-4 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700">+ Tambah/Edit Harga</a>
    </div>
    <form action="{{ route('admin.dokters.index') }}" method="GET" class="mb-4">
        {{-- ... Form pencarian ... --}}
    </form>
    @include('admin.includes.alert')
    <div class="overflow-x-auto max-w-7xl px-1 py-4">
        <table class="w-full bg-white border border-gray-300 rounded-xl overflow-hidden shadow-md">
            <thead class="bg-[#76A9C5] text-white">
                <tr>
                    <th class="p-3 text-left">ID Dokter</th>
                    <th class="p-3 text-left">Nama Dokter</th>
                    <th class="p-3 text-left">Deskripsi Harga</th>
                    <th class="p-3 text-right">Harga</th>
                    <th class="p-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($doctors as $doctor)
                <tr class="odd:bg-white even:bg-gray-100">
                    <td class="p-3">{{ $doctor['id_dokter'] }}</td>
                    <td class="p-3 font-medium">{{ $doctor['nama_dokter'] }}</td>
                    <td class="p-3 text-gray-600">{{ $doctor['desc'] }}</td>
                    <td class="p-3 text-right font-semibold">
                        @if (!is_null($doctor['price']))
                        Rp {{ number_format($doctor['price'], 0, ',', '.') }}
                        @else
                        -
                        @endif
                    </td>
                    <td class="p-3 text-center">
                        <a href="{{ route('admin.dokters.edit', $doctor['id_dokter']) }}" class="text-sky-500 hover:text-sky-700 font-semibold">Edit Harga</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center p-4 text-gray-500">Data tidak ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $doctors->withQueryString()->links() }}</div>
</x-admin-layout>
