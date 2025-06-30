<x-admin-layout>
    <x-slot name="title">
        Jenis Pembayaran
    </x-slot>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold text-sky-800">Jenis Pembayaran</h2>
        <a href="{{ route('admin.payment.type.create') }}" class="px-4 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700">
            + Tambah Jenis Pembayaran
        </a>
    </div>

    <div>

        @include('admin.includes.alert')
        <div class="overflow-x-auto max-w-7xl px-1 py-4">
            <table class="w-full bg-white border border-gray-300 rounded-xl overflow-hidden shadow-md">
                <thead class="bg-[#76A9C5] text-white">
                    <tr>
                        <th class="py-2 px-4 text-center">Kode Pembayaran</th>
                        <th class="py-2 px-4 text-center">Jenis Pembayaran</th>
                        <th class="py-2 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($datas as $data)
                    <tr class="odd:bg-white even:bg-gray-100 hover:bg-blue-100">
                        <td class="px-3 py-4">{{ $data->code }}</td>
                        <td class="px-3 py-4">{{ $data->name }}</td>
                        <td class="px-3 py-4 text-center">
                            <a href="{{ route('admin.payment.type.edit', $data->id) }}" class="text-sky-500 hover:text-sky-700 font-semibold">
                                Edit
                            </a>
                            <form action="{{route('admin.payment.type.destroy', $data->id)}}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-500 hover:text-red-700" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr class="bg-white">
                        <td class="px-3 py-4 text-center text-gray-500" colspan="3">
                            Data Belum Tersedia
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>
