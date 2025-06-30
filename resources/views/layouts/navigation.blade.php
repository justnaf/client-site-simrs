<aside class="w-64 bg-[#76A9C5] text-white flex flex-col py-10 px-4 rounded-r-3xl">
    <nav class="space-y-4 font-semibold">
        <a href="{{ route('admin.dashboard') }}" @class([ 'flex items-center space-x-2 py-2 px-4 rounded-lg text-white transition-colors' , 'bg-sky-700'=> request()->routeIs('admin.dashboard'),
            'hover:bg-sky-700' => !request()->routeIs('admin.dashboard')
            ])>
            <i data-lucide="layout-dashboard"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('admin.payment.type.index') }}" @class([ 'flex items-center space-x-2 py-2 px-4 rounded-lg text-white transition-colors' , 'bg-sky-700'=> request()->routeIs('admin.payment.type.*'),
            'hover:bg-sky-700' => !request()->routeIs('admin.payment.type.*')
            ])>
            <i data-lucide="wallet"></i>
            <span>Jenis Payment</span>
        </a>
        <a href="{{ route('admin.patients.index') }}" @class([ 'flex items-center space-x-2 py-2 px-4 rounded-lg text-white transition-colors' , 'bg-sky-700'=> request()->routeIs('admin.patients.*'),
            'hover:bg-sky-700' => !request()->routeIs('admin.patients.*')
            ])>
            <i data-lucide="briefcase-medical"></i>
            <span>Pasien</span>
        </a>
        <a href="{{ route('admin.registration.index') }}" @class([ 'flex items-center space-x-2 py-2 px-4 rounded-lg text-white transition-colors' , 'bg-sky-700'=> request()->routeIs('admin.registration.*'),
            'hover:bg-sky-700' => !request()->routeIs('admin.registration.*')
            ])>
            <i data-lucide="notepad-text-dashed"></i>
            <span>Pendaftaran</span>
        </a>
        <a href="{{ route('admin.billing.search') }}" @class([ 'flex items-center space-x-2 py-2 px-4 rounded-lg text-white transition-colors' , 'bg-sky-700'=> request()->routeIs('admin.billing.*'),
            'hover:bg-sky-700' => !request()->routeIs('admin.billing.*')
            ])>
            <i data-lucide="receipt-text"></i>
            <span>Transaksi</span>
        </a>
        <a href="{{ route('admin.drug.index')  }}" @class([ 'flex items-center space-x-2 py-2 px-4 rounded-lg text-white transition-colors' , 'bg-sky-700'=> request()->routeIs('admin.drug.*'),
            'hover:bg-sky-700' => !request()->routeIs('admin.drug.*')
            ])>
            <i data-lucide="pill"></i>
            <span>Data Obat</span>
        </a>
        <a href="{{ route('admin.polis.index')  }}" @class([ 'flex items-center space-x-2 py-2 px-4 rounded-lg text-white transition-colors' , 'bg-sky-700'=> request()->routeIs('admin.polis.*'),
            'hover:bg-sky-700' => !request()->routeIs('admin.polis.*')
            ])>
            <i data-lucide="square-activity"></i>
            <span>Data Poli</span>
        </a>
        <a href="{{ route('admin.dokters.index')  }}" @class([ 'flex items-center space-x-2 py-2 px-4 rounded-lg text-white transition-colors' , 'bg-sky-700'=> request()->routeIs('admin.dokters.*'),
            'hover:bg-sky-700' => !request()->routeIs('admin.dokters.*')
            ])>
            <i data-lucide="user-round"></i>
            <span>Data Dokter</span>
        </a>
        <a href="{{ route('admin.transaction.index')  }}" @class([ 'flex items-center space-x-2 py-2 px-4 rounded-lg text-white transition-colors' , 'bg-sky-700'=> request()->routeIs('admin.transaction.*'),
            'hover:bg-sky-700' => !request()->routeIs('admin.transaction.*')
            ])>
            <i data-lucide="hand-coins"></i>
            <span>Laporan Transaksi</span>
        </a>
        <a href="{{ url('/kelolaakun') }}" class="flex items-center space-x-2 py-2 px-4 text-white hover:bg-sky-700 rounded-lg">
            <i data-lucide="settings"></i>
            <span>Kelola Akun</span>
        </a>
    </nav>
    <div class="mt-auto px-4">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <input type="submit" value="Logout" class="flex items-center space-x-2 py-2 px-4 text-white bg-red-500 rounded-lg hover:bg-red-600">
        </form>
    </div>
</aside>
