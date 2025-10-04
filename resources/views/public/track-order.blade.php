@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-10 max-w-3xl">
        <h1 class="text-2xl font-bold mb-6">Lacak Pesanan</h1>

        @if (session('error'))
            <div class="bg-red-50 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="bg-green-50 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow p-6 border border-gray-200">
            <form method="POST" action="{{ route('public.track.search') }}" class="space-y-4">
                @csrf
                <label class="block text-sm font-medium text-gray-700">Nomor Pesanan</label>
                <input
                    type="text"
                    name="order_number"
                    value="{{ old('order_number', request('order')) }}"
                    placeholder="Contoh: DP-20251002-001"
                    class="block w-full rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-500"
                    required
                />
                @error('order_number')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror

                <button
                    type="submit"
                    class="inline-flex items-center px-4 py-2 bg-orange-600 text-white rounded hover:bg-orange-700"
                >
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M12.9 14.32a8 8 0 111.414-1.414l4.387 4.387-1.414 1.414-4.387-4.387zM14 8a6 6 0 11-12 0 6 6 0 0112 0z"
                        />
                    </svg>
                    Lacak
                </button>
            </form>
        </div>
    </div>
@endsection
