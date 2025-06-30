<x-admin-layout>
    <x-slot name="title">
        Edit Jenis Pembayaran
    </x-slot>
    <h2 class="text-3xl font-bold text-sky-800 mb-6"><a href="{{route('admin.payment.type.index')}}">Jenis Pembayaran</a> > Edit Data</h2>

    @include('admin.includes.alert')

    <div class="bg-white p-6 rounded-lg shadow-md border w-full md:w-1/2">
        {{-- Action mengarah ke route update, dengan method PUT --}}
        <form action="{{ route('admin.payment.type.update', $paymentType->id) }}" method="POST" class="space-y-3">
            @csrf
            @method('PUT')
            <div class="flex items-center">
                <label class="w-40 font-medium" for="code">Kode</label>
                <span class="mr-2">:</span>
                {{-- value diisi dengan old() dan data yang ada --}}
                <input type="text" id="code" name="code" value="{{ old('code', $paymentType->code) }}" class="border rounded-md px-3 py-1 w-56">
                @error('code')<span class="text-red-500 text-sm ml-2">{{ $message }}</span>@enderror
            </div>
            <div class="flex items-center">
                <label class="w-40 font-medium" for="name">Nama Jenis</label>
                <span class="mr-2">:</span>
                <input type="text" id="name" name="name" value="{{ old('name', $paymentType->name) }}" class="border rounded-md px-3 py-1 w-56">
                @error('name')<span class="text-red-500 text-sm ml-2">{{ $message }}</span>@enderror
            </div>
            <div class="flex items-center">
                <label class="w-40 font-medium" for="discount">Diskon (%)</label>
                <span class="mr-2">:</span>
                <input type="number" id="discount" name="discount" value="{{ old('discount', $paymentType->discount) }}" step="0.01" min="0" max="100" class="border rounded-md px-3 py-1 w-56">
                @error('discount')<span class="text-red-500 text-sm ml-2">{{ $message }}</span>@enderror
            </div>
            <div class="pt-2">
                <button type="submit" class="bg-sky-600 hover:bg-sky-700 text-white font-bold py-2 px-4 rounded">
                    Update
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
