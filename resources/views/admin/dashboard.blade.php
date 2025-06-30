<x-admin-layout>
    <x-slot name="title">
        Dashboard
    </x-slot>
    <h1 class="text-3xl font-bold text-sky-800 mb-8">Selamat Datang, Kasir</h1>
    <!-- Rekap Card -->
    <h2 class="text-lg font-bold text-gray-700 mb-2">Aktivitas Hari Ini</h2>
    <div class="flex flex-wrap gap-4">
        <div class="flex justify-between items-center bg-white rounded-2xl shadow-md px-6 py-4 w-full sm:w-64">
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Pemasukan</p>
                {{-- Tampilkan data total pemasukan yang sudah diformat --}}
                <p class="text-2xl text-sky-800 font-bold mt-1">
                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                </p>
            </div>
            <div class="bg-gradient-to-br from-sky-100 to-sky-300 text-sky-700 rounded-xl p-3 flex items-center justify-center">
                <i data-lucide="piggy-bank" class="w-7 h-7"></i>
            </div>
        </div>

        <div class="flex justify-between items-center bg-white rounded-2xl shadow-md px-6 py-4 w-full sm:w-64">
            <div>
                <p class="text-sm text-gray-500 font-medium">Transaksi Hari Ini</p>
                {{-- Tampilkan data jumlah transaksi hari ini --}}
                <p class="text-3xl text-sky-800 font-bold mt-1">
                    {{ $todayTransactionsCount }}
                </p>
            </div>
            <div class="bg-gradient-to-br from-green-100 to-green-300 text-green-700 rounded-xl p-3 flex items-center justify-center">
                <i data-lucide="coins" class="w-7 h-7"></i>
            </div>
        </div>

        <div class="flex justify-between items-center bg-white rounded-2xl shadow-md px-6 py-4 w-full sm:w-64">
            <div>
                <p class="text-sm text-gray-500 font-medium">Pasien Hari Ini</p>
                {{-- Tampilkan data jumlah pasien hari ini --}}
                <p class="text-3xl text-sky-800 font-bold mt-1">
                    {{ $todayPatientsCount }}
                </p>
            </div>
            <div class="bg-gradient-to-br from-yellow-100 to-yellow-300 text-yellow-700 rounded-xl p-3 flex items-center justify-center">
                <i data-lucide="users" class="w-7 h-7"></i>
            </div>
        </div>
    </div>

    <!-- Card Kecil -->
    <div class="flex flex-row gap-10 flex-wrap mt-8">
        <div class="flex flex-col items-center bg-white rounded-2xl shadow-md p-4 w-28 mb-8">
            <div class="bg-gray-400 p-4 text-white rounded-xl mb-2">
                <i data-lucide="arrow-left-right"></i>
            </div>
            <p class="text-blue-700 text-sm text-center font-medium leading-tight">
                Transaksi Obat
            </p>
        </div>
        <div class="flex flex-col items-center bg-white rounded-2xl shadow-md p-4 w-28 mb-8">
            <div class="bg-gray-400 p-4 text-white rounded-xl mb-2">
                <i data-lucide="banknote"></i>
            </div>
            <p class="text-blue-700 text-sm text-center font-medium leading-tight">
                Transaksi Layanan
            </p>
        </div>
        <div class="flex flex-col items-center bg-white rounded-2xl shadow-md p-4 w-28 mb-8">
            <div class="bg-gray-400 p-4 text-white rounded-xl mb-2">
                <i data-lucide="pill"></i>
            </div>
            <p class="text-blue-700 text-sm text-center font-medium leading-tight">
                Daftar Obat
            </p>
        </div>
        <div class="flex flex-col items-center bg-white rounded-2xl shadow-md p-4 w-28 mb-8">
            <div class="bg-gray-400 p-4 rounded-xl text-white mb-2">
                <i data-lucide="notepad-text-dashed"></i>
            </div>
            <p class="text-blue-700 text-sm text-center font-medium leading-tight">
                Daftar Layanan
            </p>
        </div>
    </div>

    <!-- Table Obat -->
    <section class="w-2/3 text-left">
        <h2 class="text-lg font-bold text-gray-700 mb-2">Transaksi Hari Ini</h2>
        <div class="bg-white rounded-xl shadow overflow-x-auto">
            <table class="min-w-full text-sm text-left border-collapse">
                <thead class="bg-blue-100 text-blue-900">
                    <tr>
                        <th class="px-4 py-2">NO Regis</th>
                        <th class="px-4 py-2">RM</th>
                        <th class="px-4 py-2">Nama Pasien</th>
                        <th class="px-4 py-2">Jenis Kelamin</th>
                        <th class="px-4 py-2">Layanan/Obat</th>
                        <th class="px-4 py-2">Total</th>
                        <th class="px-4 py-2">Jam</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Isi akan diisi oleh JavaScript -->
                </tbody>
            </table>
        </div>
    </section>
</x-admin-layout>
