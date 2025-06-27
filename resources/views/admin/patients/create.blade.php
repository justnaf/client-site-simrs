<x-admin-layout>
    <x-slot name="title">
        Tambah Pasien Baru
    </x-slot>

    <h2 class="text-3xl font-bold text-sky-800 mb-6">Form Tambah Pasien Baru</h2>

    {{-- Alert untuk menampilkan error validasi umum atau error dari session --}}
    @include('admin.includes.alert')

    <div class="bg-white p-6 rounded-lg shadow-md">
        <form action="{{ route('admin.patients.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                <div>
                    <label for="nama_pasien" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                    <input type="text" name="nama_pasien" id="nama_pasien" value="{{ old('nama_pasien') }}" required class="p-2 mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-sky-500 focus:border-sky-500">
                    @error('nama_pasien') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="nik" class="block text-sm font-medium text-gray-700">NIK</label>
                    <input type="text" name="nik" id="nik" value="{{ old('nik') }}" required pattern="\d{16}" title="NIK harus terdiri dari 16 angka" class="p-2 mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-sky-500 focus:border-sky-500">
                    @error('nik') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="tgl_lahir" class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                    <input type="date" name="tgl_lahir" id="tgl_lahir" value="{{ old('tgl_lahir') }}" required class="p-2 mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-sky-500 focus:border-sky-500">
                    @error('tgl_lahir') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="jns_kelamin" class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                    <select name="jns_kelamin" id="jns_kelamin" required class="p-2 mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-sky-500 focus:border-sky-500">
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="Pria" {{ old('jns_kelamin', $patient['jns_kelamin'] ?? '') == 'pria' ? 'selected' : '' }}>Pria</option>
                        <option value="Perempuan" {{ old('jns_kelamin', $patient['jns_kelamin'] ?? '') == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jns_kelamin') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="agama" class="block text-sm font-medium text-gray-700">Agama</label>
                    <input type="text" name="agama" id="agama" value="{{ old('agama') }}" required class="p-2 mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-sky-500 focus:border-sky-500">
                    @error('agama') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="gol_darah" class="block text-sm font-medium text-gray-700">Golongan Darah</label>
                    <select name="gol_darah" id="gol_darah" required class="p-2 mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-sky-500 focus:border-sky-500">
                        <option value="">Pilih Golongan Darah</option>
                        <option value="A" {{ old('gol_darah') == 'A' ? 'selected' : '' }}>A</option>
                        <option value="B" {{ old('gol_darah') == 'B' ? 'selected' : '' }}>B</option>
                        <option value="AB" {{ old('gol_darah') == 'AB' ? 'selected' : '' }}>AB</option>
                        <option value="O" {{ old('gol_darah') == 'O' ? 'selected' : '' }}>O</option>
                    </select>
                    @error('gol_darah') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="pekerjaan" class="block text-sm font-medium text-gray-700">Pekerjaan</label>
                    <input type="text" name="pekerjaan" id="pekerjaan" value="{{ old('pekerjaan') }}" required class="p-2 mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-sky-500 focus:border-sky-500">
                    @error('pekerjaan') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="md:col-span-2">
                    <hr class="my-4">
                </div>

                <div>
                    <label for="email_pasien" class="block text-sm font-medium text-gray-700">Alamat Email</label>
                    <input type="email" name="email_pasien" id="email_pasien" value="{{ old('email_pasien') }}" required class="p-2 mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-sky-500 focus:border-sky-500">
                    @error('email_pasien') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="no_hp_pasien" class="block text-sm font-medium text-gray-700">No. Handphone</label>
                    <input type="tel" name="no_hp_pasien" id="no_hp_pasien" value="{{ old('no_hp_pasien') }}" required class="p-2 mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-sky-500 focus:border-sky-500">
                    @error('no_hp_pasien') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="kabupaten" class="block text-sm font-medium text-gray-700">Kabupaten/Kota</label>
                    <input type="text" name="kabupaten" id="kabupaten" value="{{ old('kabupaten') }}" required class="p-2 mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-sky-500 focus:border-sky-500">
                    @error('kabupaten') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat Lengkap</label>
                    <textarea name="alamat" id="alamat" rows="3" required class="p-2 mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-sky-500 focus:border-sky-500">{{ old('alamat') }}</textarea>
                    @error('alamat') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

            </div>

            <div class="mt-8 flex justify-end">
                <a href="{{ route('admin.patients.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700 ml-4">
                    Simpan Data Pasien
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
