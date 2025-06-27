<x-admin-layout>
    <x-slot name="title">
        Jenis Pembayaran
    </x-slot>
    <h2 class="text-3xl font-bold text-sky-800 mb-6"><a href="{{route('admin.payment.type.index')}}"> Jenis Pembayaran</a> > Tambah Data</h2>

    <div>
        <div class="overflow-x-auto max-w-7xl py-4 px-1">
            <div class="bg-gray-100 p-6 rounded-xl shadow-md border w-fit mb-6">
                <form action="{{route('admin.payment.type.store')}}" method="POST" enctype="multipart/form-data" class="space-y-3">
                    @csrf
                    @method('POST')
                    <div class="flex items-center">
                        <label class="w-40 font-medium" for="kode">Kode Pembayaran</label>
                        <span class="mr-2">:</span>
                        <input type="text" id="kode" name="code" class="border rounded-md px-3 py-1 w-56">
                    </div>
                    <div class="flex items-center">
                        <label class="w-40 font-medium" for="jenix">Jenis Pembayaran</label>
                        <span class="mr-2">:</span>
                        <input type="text" id="jenix" name="name" class="border rounded-md px-3 py-1 w-56">
                    </div>
                    <div class="flex items-center">
                        <label class="w-40 font-medium" for="ico">Icon Pembayaran (Tidak Wajib)</label>
                        <span class="mr-2">:</span>
                        <input type="file" id="icon" name="file_icon" class="border rounded-md px-3 py-1 w-56">
                    </div>
                    <button type="submit" class="bg-[#76A9C5] hover:bg-[#76A9C5]/50 text-white font-bold py-2 px-4 rounded">
                        Simpan
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
