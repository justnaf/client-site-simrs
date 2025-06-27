<x-admin-layout>
    <x-slot name="title">
        Pendaftaran Baru
    </x-slot>
    <h2 class="text-3xl font-bold text-sky-800 mb-6"><a href="{{ route('admin.registration.index') }}">Pendaftaran</a> > Tambah Data</h2>

    {{-- Alert untuk menampilkan error validasi atau session --}}
    @include('admin.includes.alert')

    {{-- Card Form --}}
    <div class="bg-white p-6 rounded-lg shadow-md border">
        <form action="{{ route('admin.registration.store') }}" method="POST" class="space-y-4">
            @csrf

            {{-- Dropdown Pasien --}}
            <div class="flex items-center">
                <label for="rm" class="w-48 font-medium text-gray-700">Pilih Pasien</label>
                <span class="mr-4">:</span>
                <select id="rm" name="rm" required class="flex-1 border rounded-md shadow-sm  p-2">
                    <option value="">-- Nama Pasien (No. RM) --</option>
                    @foreach ($patients as $patient)
                    <option value="{{ $patient['rm'] }}" {{ old('patient_rm') == $patient['rm'] ? 'selected' : '' }}>
                        {{ $patient['nama_pasien'] }} (RM: {{ $patient['rm'] }})
                    </option>
                    @endforeach
                </select>
                @error('rm') <span class="text-red-500 text-sm ml-2">{{ $message }}</span> @enderror
            </div>

            {{-- Dropdown Poli --}}
            <div class="flex items-center">
                <label for="id_poli" class="w-48 font-medium text-gray-700">Pilih Poli Tujuan</label>
                <span class="mr-4">:</span>
                <select id="id_poli" name="id_poli" required class="flex-1 border rounded-md shadow-sm  p-2">
                    <option value="">-- Pilih Poli --</option>
                    @foreach ($polis as $poli)
                    <option value="{{ $poli['id_poli'] }}" {{ old('id_poli') == $poli['id_poli'] ? 'selected' : '' }}>
                        {{ $poli['nama_poli'] }}
                    </option>
                    @endforeach
                </select>
                @error('id_poli') <span class="text-red-500 text-sm ml-2">{{ $message }}</span> @enderror
            </div>

            {{-- Dropdown Jenis Pendaftaran / Pembayaran --}}
            <div class="flex items-center">
                <label for="payment_type_id" class="w-48 font-medium text-gray-700">Jenis Pendaftaran</label>
                <span class="mr-4">:</span>
                <select id="payment_type_id" name="payment_type_id" required class="flex-1 border rounded-md shadow-sm p-2">
                    <option value="">-- Pilih Jenis --</option>
                    @foreach ($payments as $payment)
                    <option value="{{ $payment->id }}" {{ old('payment_type_id') == $payment->id ? 'selected' : '' }}>
                        {{ $payment->name }}
                    </option>
                    @endforeach
                </select>
                @error('payment_type_id') <span class="text-red-500 text-sm ml-2">{{ $message }}</span> @enderror
            </div>

            <div class="flex items-center">
                <label for="tgl_kunjungan" class="w-48 font-medium text-gray-700">Tanggal Kunjungan</label>
                <span class="mr-4">:</span>
                <input type="date" id="tgl_kunjungan" name="tgl_kunjungan" value="{{ old('tgl_kunjungan', date('Y-m-d')) }}" required class="flex-1 border rounded-md shadow-sm p-2">
                @error('tgl_kunjungan') <span class="text-red-500 text-sm ml-2">{{ $message }}</span> @enderror
            </div>

            <div class="flex justify-end pt-4">
                <a href="{{ route('admin.registration.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
                    Batal
                </a>
                <button type="submit" class="ml-3 px-4 py-2 bg-[#76A9C5] hover:bg-[#6A98B4] text-white font-bold rounded-lg">
                    Daftarkan Pasien
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
