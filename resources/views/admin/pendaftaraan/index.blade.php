<x-admin-layout>
    <x-slot name="title">
        Pendaftaran
    </x-slot>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold text-sky-800">Pendaftaran</h2>
        <a href="{{ route('admin.registration.create') }}" class="px-4 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700">
            + Tambah Pendaftaran Baru
        </a>
    </div>
    <form action="{{ url()->current() }}" method="GET">
        <div class="mb-4 flex justify-between items-center">

            <input name="search" type="text" placeholder="Cari No. Regis, RM, atau Nama Pasien..." class="w-1/3 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500" value="{{ request('search') }}">

            <div>
                <label for="polis" class="mr-2">Daftar Poli :</label>
                <select name="poli" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500" id="polis" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    @foreach ($polis as $poli)
                    <option value="{{ $poli['id_poli'] }}" {{ request('poli') == $poli['id_poli'] ? 'selected' : '' }}>
                        {{ $poli['nama_poli'] }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>

    @include('admin.includes.alert')
    <div class="overflow-x-auto max-w-7xl px-1 py-4">
        <table class="w-full bg-white border border-gray-300 rounded-xl overflow-hidden shadow-md">
            <thead class="bg-[#76A9C5] text-white">
                <th class="py-2 px-4 text-center">No Invoice</th>
                <th class="py-2 px-4 text-center">Rekam Media</th>
                <th class="py-2 px-4 text-center">Nama Pasien</th>
                <th class="py-2 px-4 text-center">Nama Poli</th>
                <th class="py-2 px-4 text-center">Nama Dokter</th>
                <th class="py-2 px-4 text-center">Status</th>
                <th class="py-2 px-4 text-center">Aksi</th>
            </thead>
            <tbody>
                @forelse ($registrations as $data)
                <tr class="odd:bg-white even:bg-gray-100 hover:bg-blue-100">
                    <td class="px-3 py-4">{{ $data['nomor_invoice'] }}</td>
                    <td class="px-3 py-4">{{ $data['rm'] }}</td>
                    <td class="px-3 py-4">{{ $data['nama_pasien'] }}</td>
                    <td class="px-3 py-4">{{ $data['nama_poli'] }}</td>
                    <td class="px-3 py-4">{{ $data['nama_dokter'] }}</td>
                    <td class="px-3 py-4">{{ $data['status'] }}</td>
                    <td class="px-3 py-4 text-center">
                        <form action="{{ route('admin.registration.destroy', $data['no_registrasi']) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 font-semibold">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center p-4 text-gray-500">Data tidak ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $registrations->withQueryString()->links() }}
    </div>

</x-admin-layout>
