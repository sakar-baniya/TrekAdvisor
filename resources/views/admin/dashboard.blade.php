<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-6 text-gray-700 border-b pb-2">Control Center</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                        <!-- Trek Management -->
                        <a href="{{ route('admin.treks.index') }}" class="group block p-6 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md hover:border-blue-300 transition-all duration-200">
                            <div class="flex items-center space-x-4">
                                <div class="p-3 bg-blue-50 rounded-lg group-hover:bg-blue-100 transition-colors">
                                    <i class="fas fa-mountain text-2xl text-blue-600"></i>
                                </div>
                                <div>
                                    <h4 class="text-lg font-bold text-gray-800">Trek Management</h4>
                                    <p class="text-sm text-gray-500">Add, edit, and organize trekking routes</p>
                                </div>
                            </div>
                        </a>

                        <!-- User Management -->
                        <div class="group block p-6 bg-white border border-gray-200 rounded-lg shadow-sm opacity-75 cursor-not-allowed">
                            <div class="flex items-center space-x-4">
                                <div class="p-3 bg-gray-50 rounded-lg">
                                    <i class="fas fa-users text-2xl text-gray-600"></i>
                                </div>
                                <div>
                                    <h4 class="text-lg font-bold text-gray-800">User Control</h4>
                                    <p class="text-sm text-gray-500">Manage staff and customer accounts</p>
                                </div>
                            </div>
                        </div>

                        <!-- Finance & Analytics -->
                        <div class="group block p-6 bg-white border border-gray-200 rounded-lg shadow-sm opacity-75 cursor-not-allowed">
                            <div class="flex items-center space-x-4">
                                <div class="p-3 bg-gray-50 rounded-lg">
                                    <i class="fas fa-chart-line text-2xl text-gray-600"></i>
                                </div>
                                <div>
                                    <h4 class="text-lg font-bold text-gray-800">Finances</h4>
                                    <p class="text-sm text-gray-500">View bookings and revenue reports</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h3 class="text-lg font-semibold mb-4 text-gray-700 border-b pb-2">Quick Stats</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="p-4 bg-gray-50 rounded-lg border border-gray-100">
                            <span class="block text-gray-500 text-xs uppercase font-bold tracking-wider">Total Treks</span>
                            <span class="text-2xl font-black text-gray-800">{{ \App\Models\Trek::count() }}</span>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-lg border border-gray-100">
                            <span class="block text-gray-500 text-xs uppercase font-bold tracking-wider">Active Bookings</span>
                            <span class="text-2xl font-black text-gray-800">{{ \App\Models\TrekBooking::count() }}</span>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-lg border border-gray-100">
                            <span class="block text-gray-500 text-xs uppercase font-bold tracking-wider">Total Revenue</span>
                            <span class="text-2xl font-black text-gray-800">${{ number_format(\App\Models\Payment::sum('amount')) }}</span>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-lg border border-gray-100">
                            <span class="block text-gray-500 text-xs uppercase font-bold tracking-wider">Pending Gear</span>
                            <span class="text-2xl font-black text-gray-800">{{ \App\Models\GearRental::where('status', 'Pending')->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
