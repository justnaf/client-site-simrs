<x-admin-layout>
    <x-slot name="title">
        Manajemen Pasien
    </x-slot>

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold text-sky-800">Daftar Pasien</h2>
        <a href="{{ route('admin.patients.create') }}" class="px-4 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700">
            + Tambah Pasien Baru
        </a>
    </div>

    <!-- 1. Tambahkan Form Pencarian -->
    <form action="{{ route('admin.patients.index') }}" method="GET" class="mb-4">
        <div class="flex items-center">
            <input name="search" type="text" placeholder="Cari No. RM, Nama Pasien, atau NIK..." class="w-full md:w-1/3 px-4 py-2 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-sky-500" value="{{ request('search') }}">
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
                    <th class="p-3 text-left">No. RM</th>
                    <th class="p-3 text-left">Nama Pasien</th>
                    <th class="p-3 text-left">NIK</th>
                    <th class="p-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                {{-- 2. Ganti nama variabel loop menjadi $patients --}}
                @forelse ($patients as $patient)
                <tr class="odd:bg-white even:bg-gray-100 ">
                    <td class="p-3">{{ $patient['rm'] }}</td>
                    <td class="p-3">{{ $patient['nama_pasien'] }}</td>
                    <td class="p-3">{{ $patient['nik'] }}</td>
                    <td class="p-3 text-center">
                        <div class="flex justify-center items-center space-x-2">
                            <a href="{{ route('admin.patients.edit', $patient['rm']) }}" class="text-sky-500 hover:text-sky-700 font-semibold">
                                Edit
                            </a>
                            {{-- 3. Pastikan parameter untuk destroy sudah benar --}}
                            <form action="{{ route('admin.patients.destroy', $patient['rm'] ) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus pasien ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 font-semibold">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center p-4 text-gray-500">
                        Data pasien tidak ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- 4. Tambahkan Link Pagination -->
    <div class="mt-4">
        {{ $patients->withQueryString()->links() }}
    </div>

</x-admin-layout>
