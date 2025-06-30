<x-admin-layout>
    <x-slot name="title">
        Riwayat Transaksi
    </x-slot>

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold text-sky-800">Riwayat Seluruh Transaksi</h2>
        <a href="{{ route('admin.billing.search') }}" class="px-4 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700">
            Cari & Proses Tagihan
        </a>
    </div>

    <form action="{{ route('admin.transaction.index') }}" method="GET" class="mb-4">
        <div class="flex items-center">
            <input name="search" type="text" placeholder="Cari No. Invoice atau No. Registrasi..." class="w-full md:w-1/3 px-4 py-2 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-sky-500" value="{{ request('search') }}">
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
                    <th class="p-3 text-left">No. Invoice</th>
                    <th class="p-3 text-right">Total Tagihan</th>
                    <th class="p-3 text-right">Total Dibayar</th>
                    <th class="p-3 text-center">Status Pembayaran</th>
                    <th class="p-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transactions as $transaction)
                <tr class="odd:bg-white even:bg-gray-100">
                    <td class="p-3 font-mono font-semibold">{{ optional($transaction->registration)->nomor_invoice ?? 'N/A'  }}</td>
                    <td class="p-3 text-right">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                    <td class="p-3 text-right">Rp {{ number_format($transaction->paid_amount, 0, ',', '.') }}</td>
                    <td class="p-3 text-center">
                        {{-- Badge Status dengan warna kondisional --}}
                        @if ($transaction->status == 'Lunas')
                        <span class="px-3 py-1 text-xs font-semibold text-green-800 bg-green-200 rounded-full">LUNAS</span>
                        @elseif ($transaction->status == 'Dibayar Sebagian')
                        <span class="px-3 py-1 text-xs font-semibold text-yellow-800 bg-yellow-200 rounded-full">DIBAYAR SEBAGIAN</span>
                        @else
                        <span class="px-3 py-1 text-xs font-semibold text-red-800 bg-red-200 rounded-full">BELUM LUNAS</span>
                        @endif
                    </td>
                    <td class="p-3 text-center">
                        <a href="{{ route('admin.billing.show', ['no_invoice' => $transaction->nomor_invoice]) }}" class="text-sky-500 hover:text-sky-700 font-semibold">
                            Lihat Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center p-4 text-gray-500">
                        Tidak ada riwayat transaksi yang ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $transactions->withQueryString()->links() }}
    </div>

</x-admin-layout>
