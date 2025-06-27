<x-auth-layout>
    <div class="bg-white shadow-2xl rounded-xl overflow-hidden max-w-4xl w-[90%] flex">
        <div class="w-1/2 hidden md:block">
            <img src="https://ti054a03.agussbn.my.id/img/rumahsakit.jpg" alt="Hospital" class="h-full w-full object-cover" />
        </div>
        <div class="w-1/2 px-3 py-6 flex flex-col justify-center">
            <form action="{{route('login')}}" method="POST">
                @csrf
                @method('POST')
                <h2 class="text-2xl font-bold text-[#76A9C5] mb-6 text-center">Selamat Datang di SIMRS</h2>
                <div class="mb-2">
                    <label for="login" class="block text-sm font-medium text-gray-700">Username atau Email</label>
                    <input type="text" name="login" id="login" required class="mt-1 block w-full px-4 py-2 border border-slate-300 rounded-lg shadow-sm " />
                    @error('login')
                    <p class=" text-red-500 text-xs mt-1">{{$message}}</p>
                    @enderror
                </div>
                <div class="mb-2">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" id="password" required class="mt-1 block w-full px-4 py-2 border border-slate-300 rounded-lg shadow-sm " />
                    @error('login')
                    <p class=" text-red-500 text-xs mt-1">{{$message}}</p>
                    @enderror
                </div>
                <button type="submit" class="w-full px-4 py-2 bg-[#76A9C5] text-white rounded-full hover:bg-[#5a8ca7] transition font-medium">
                    Masuk
                </button>
            </form>
            <p class="text-center mt-4 text-sm">
                Belum punya akun?
                <a href="#" class="text-[#76A9C5] hover:underline">Daftar</a>
            </p>
        </div>
    </div>
</x-auth-layout>
