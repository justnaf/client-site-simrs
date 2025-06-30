<x-admin-layout>
    <x-slot name="title">Detail Tagihan</x-slot>

    @php
    $transaction = $billing['localTransaction'];
    $pendaftaran = $billing['pendaftaran'];
    @endphp

    <h2 class="text-3xl font-bold text-sky-800 mb-6">Detail Tagihan</h2>

    @include('admin.includes.alert')

    <div class="bg-white p-6 rounded-lg shadow-md border max-w-4xl mx-auto">
        <div class="grid grid-cols-2 gap-4 border-b pb-4 mb-4">
            <div>
                <h3 class="font-bold text-gray-800">No. Invoice: {{ $transaction->nomor_invoice }}</h3>
                <p class="text-sm text-gray-600">No. Registrasi: {{ $pendaftaran['no_registrasi'] }}</p>
                <p class="text-sm text-gray-600">Tgl. Kunjungan: {{ \Carbon\Carbon::parse($pendaftaran['tgl_kunjungan'])->isoFormat('D MMMM YYYY') }}</p>
            </div>
            <div class="text-right">
                <p class="font-semibold">{{ $pendaftaran['nama_pasien'] }}</p>
                <p class="text-sm text-gray-600">RM: {{ $pendaftaran['rm'] }}</p>
            </div>
        </div>

        <div class="mb-6">
            <h4 class="font-semibold text-lg inline-block mr-3 text-gray-700">Status:</h4>
            @if ($transaction->status == 'Lunas')
            <span class="px-3 py-1 text-sm font-semibold text-green-800 bg-green-200 rounded-full">LUNAS</span>
            @elseif ($transaction->status == 'Dibayar Sebagian')
            <span class="px-3 py-1 text-sm font-semibold text-yellow-800 bg-yellow-200 rounded-full">DIBAYAR SEBAGIAN</span>
            @else
            <span class="px-3 py-1 text-sm font-semibold text-red-800 bg-red-200 rounded-full">BELUM LUNAS</span>
            @endif
        </div>

        <div>
            <h4 class="font-semibold text-lg mb-2 text-gray-700">Rincian Tagihan</h4>
            <table class="w-full text-sm">
                <tbody>
                    @if(count($billing['items_layanan']) > 0)
                    <tr>
                        <td class="py-2 font-medium text-gray-800" colspan="2">Layanan</td>
                    </tr>
                    @endif
                    @foreach($billing['items_layanan'] as $item)
                    <tr class="border-b">
                        <td class="py-1 pl-4">{{ $item['name'] }}</td>
                        <td class="py-1 text-right">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                    @if(count($billing['items_obat']) > 0)
                    <tr>
                        <td class="py-2 font-medium pt-4 text-gray-800" colspan="2">Obat (E-Resep)</td>
                    </tr>
                    @endif
                    @foreach($billing['items_obat'] as $item)
                    <tr class="border-b">
                        <td class="py-1 pl-4">{{ $item['name'] }} ({{ $item['quantity'] }}x)</td>
                        <td class="py-1 text-right">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6 pt-4 border-t-2 border-dashed">
            <div class="flex justify-between text-gray-700">
                <p>Subtotal</p>
                <p>Rp {{ number_format($billing['subtotal'], 0, ',', '.') }}</p>
            </div>
            @if($billing['discount']['amount'] > 0)
            <div class="flex justify-between text-green-600">
                <p>Diskon ({{ $billing['discount']['percent'] }}%)</p>
                <p>- Rp {{ number_format($billing['discount']['amount'], 0, ',', '.') }}</p>
            </div>
            @endif
            <div class="flex justify-between text-2xl font-bold text-sky-800 mt-2">
                <p>Grand Total</p>
                <p>Rp {{ number_format($billing['grand_total'], 0, ',', '.') }}</p>
            </div>

            @if($transaction->total_amount > $transaction->paid_amount)
            <div class="flex justify-between text-lg font-semibold text-red-600 mt-1">
                <p>Sisa Tagihan</p>
                <p>Rp {{ number_format($transaction->total_amount - $transaction->paid_amount, 0, ',', '.') }}</p>
            </div>
            @endif
            @if($transaction->change_amount > 0)
            <div class="flex justify-between text-lg font-semibold text-blue-600 mt-1">
                <p>Kembalian</p>
                <p>Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</p>
            </div>
            @endif
        </div>

        @if ($transaction->status != 'Lunas')
        <div class="mt-8 pt-6 border-t">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Proses Pembayaran</h3>
            <form action="{{ route('admin.billing.pay', $transaction->id) }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="paid_amount" class="block text-sm font-medium text-gray-700">Jumlah Dibayar (Rp)</label>
                        <input type="number" name="paid_amount" id="paid_amount" required placeholder="Masukkan jumlah uang" class="mt-1 block w-full md:w-1/2 border-gray-300 rounded-md shadow-sm p-2 text-lg focus:ring-sky-500 focus:border-sky-500">
                    </div>
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700">Catatan (Opsional)</label>
                        <textarea name="notes" id="notes" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 focus:ring-sky-500 focus:border-sky-500" placeholder="Contoh: Pembayaran tunai"></textarea>
                    </div>
                    <div class="text-right">
                        <a href="{{ route('admin.billing.search') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">Kembali</a>
                        <button type="submit" class="px-6 py-2 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 ml-4">Proses Bayar</button>
                    </div>
                </div>
            </form>
        </div>
        @endif
    </div>
</x-admin-layout>
