<div
    x-show="open"
    @click.away="open = false"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 transform scale-95"
    x-transition:enter-end="opacity-100 transform scale-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100 transform scale-100"
    x-transition:leave-end="opacity-0 transform scale-95"
    class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-xl border border-gray-200 overflow-hidden z-50"
    x-cloak
>
    <!-- Header user info -->
    <div class="px-4 py-3 border-b">
        <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</p>
        <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
    </div>

    <!-- Role: customer -->
    @role('customer')
        <a href="#" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-100">Pesanan Saya</a>
        <a href="{{ url('dashboard/my-profile') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-100">
            Akun Saya
        </a>
    @endrole

    <!-- Role: super_admin -->
    @role('super_admin')
        <a href="{{ url('dashboard') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-100">Dashboard</a>
        <a href="{{ url('dashboard/my-profile') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-100">
            Akun Saya
        </a>
    @endrole

    <!-- Logout -->
    <div class="border-t border-gray-100"></div>
    <form method="POST" action="{{ filament()->getLogoutUrl() }}">
        @csrf
        <button type="submit" class="block w-full text-left px-4 py-3 text-sm text-red-600 hover:bg-gray-100">
            Logout
        </button>
    </form>
</div>
