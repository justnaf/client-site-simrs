<x-admin-layout>
    <x-slot name="title">
        Manajemen Obat
    </x-slot>

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold text-sky-800">Daftar Obat</h2>
        <a href="#" class="px-4 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700">
            + Tambah Obat Baru
        </a>
    </div>

    <form action="{{ url()->current() }}" method="GET" class="mb-4">
        <div class="flex items-center">
            <input name="search" type="text" placeholder="Cari nama atau kode obat..." class="w-full md:w-1/3 px-4 py-2 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-sky-500" value="{{ request('search') }}">
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
                    <th class="p-3 text-left">Kode Obat</th>
                    <th class="p-3 text-left">Nama Obat</th>
                    <th class="p-3 text-left">Satuan</th>
                    <th class="p-3 text-right">Harga</th>
                    <th class="p-3 text-center">Stok</th>
                    <th class="p-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($dataObat as $obat)
                <tr class="odd:bg-white even:bg-gray-100 ">
                    <td class="p-3">{{ $obat['id_obat'] ?? 'N/A' }}</td>
                    <td class="p-3 font-medium">{{ $obat['nama_obat'] ?? 'N/A' }}</td>
                    <td class="p-3">{{ $obat['bentuk_satuan'] ?? 'N/A' }}</td>
                    <td class="p-3 text-right">Rp {{ number_format($obat['harga_jual'] ?? 0, 0, ',', '.') }}</td>
                    <td class="p-3 text-center">{{ $obat['stok'] ?? 0 }}</td>
                    <td class="p-3 text-center">
                        <div class="flex justify-center items-center space-x-2">
                            <a href="#" class="text-sky-500 hover:text-sky-700 font-semibold">Edit</a>
                            <form action="#" method="POST" onsubmit="return confirm('Anda yakin?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 font-semibold">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center p-4 text-gray-500">
                        Data obat tidak ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $dataObat->withQueryString()->links() }}
    </div>

</x-admin-layout>
