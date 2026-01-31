<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Customer Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">Welcome back, {{ auth()->user()->name }}!</h3>
                    <p>This is your personal journey dashboard. Here you'll find your trek bookings, hotel stays, and gear rentals.</p>
                    
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="p-4 bg-blue-50 border border-blue-200 rounded-xl">
                            <h4 class="font-bold text-blue-700">My Treks</h4>
                            <p class="text-sm text-blue-600">View your upcoming mountain adventures.</p>
                            <a href="{{ route('treks.index') }}" class="mt-2 inline-block text-sm font-semibold text-blue-800 underline">Explore more treks</a>
                        </div>
                        <div class="p-4 bg-green-50 border border-green-200 rounded-xl">
                            <h4 class="font-bold text-green-700">Hotel Bookings</h4>
                            <p class="text-sm text-green-600">Manage your stay in the Himalayas.</p>
                        </div>
                        <div class="p-4 bg-purple-50 border border-purple-200 rounded-xl">
                            <h4 class="font-bold text-purple-700">Gear Rentals</h4>
                            <p class="text-sm text-purple-600">Check your equipment list.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
