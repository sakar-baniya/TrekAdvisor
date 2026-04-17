<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'TrekAdvisor') }} - Admin Access</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <style>
            body { font-family: 'Inter', sans-serif; }
            .input-field:focus {
                box-shadow: 0 0 0 3px rgba(15, 23, 42, 0.08);
            }
        </style>
    </head>
    <body class="antialiased text-slate-900 bg-slate-100 min-h-screen flex items-center justify-center p-4">
        
        <div class="w-full max-w-md">
            
            <!-- Logo + Brand -->
            <div class="flex flex-col items-center mb-8">
                <img src="{{ asset('images/ui/trekadvisorLOGO.png') }}" 
                     class="w-16 h-16 rounded-2xl bg-slate-900 p-2 shadow-lg shadow-slate-900/10 mb-4" 
                     alt="TrekAdvisor" />
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">TrekAdvisor</h1>
                <p class="text-sm text-slate-500 mt-1">Operations & Platform Management</p>
            </div>

            <!-- Card -->
            <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/60 p-8">
                
                <!-- Header -->
                <div class="mb-6">
                    <h2 class="text-xl font-bold text-slate-900">Admin Access</h2>
                    <p class="text-sm text-slate-500 mt-1">Please authenticate to access the dashboard</p>
                </div>

                <!-- Session Status -->
                @if (session('status'))
                    <div class="mb-5 p-3.5 bg-emerald-50 border border-emerald-200 rounded-xl flex items-start gap-3">
                        <i class="fas fa-check-circle text-emerald-500 mt-0.5"></i>
                        <p class="text-sm text-emerald-700">{{ session('status') }}</p>
                    </div>
                @endif

                <!-- Validation Errors Header (Generic) -->
                @if ($errors->any())
                    <div class="mb-5 p-3.5 bg-red-50 border border-red-200 rounded-xl flex items-start gap-3">
                        <i class="fas fa-exclamation-circle text-red-500 mt-0.5"></i>
                        <p class="text-sm text-red-700">Invalid administrator credentials provided.</p>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.login.store') }}" class="space-y-5">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">Admin Email</label>
                        <div class="relative">
                            <i class="fas fa-user-shield absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                            <input id="email" 
                                   type="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   autofocus 
                                   autocomplete="username" 
                                   class="input-field w-full bg-slate-50 border border-slate-200 rounded-xl py-3 pl-11 pr-4 text-sm text-slate-900 placeholder-slate-400 focus:bg-white focus:border-slate-900 focus:outline-none transition-all"
                                   placeholder="admin@trekadvisor.com" />
                        </div>
                        @error('email') 
                            <p class="text-xs text-red-600 mt-1.5 flex items-center gap-1">
                                <i class="fas fa-info-circle"></i> {{ $message }}
                            </p> 
                        @enderror
                    </div>

                    <!-- Password -->
                    <div x-data="{ show: false }">
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-sm font-medium text-slate-700">Security Key</label>
                            @if (Route::has('password.request'))
                                <a class="text-xs font-medium text-slate-400 hover:text-slate-900 transition-colors" href="{{ route('password.request') }}">
                                    Forgot?
                                </a>
                            @endif
                        </div>
                        <div class="relative">
                            <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                            <input id="password" 
                                   :type="show ? 'text' : 'password'" 
                                   name="password" 
                                   required 
                                   autocomplete="current-password" 
                                   class="input-field w-full bg-slate-50 border border-slate-200 rounded-xl py-3 pl-11 pr-12 text-sm text-slate-900 placeholder-slate-400 focus:bg-white focus:border-slate-900 focus:outline-none transition-all"
                                   placeholder="Enter password" />
                            <button type="button" 
                                    @click="show = !show" 
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-700 transition-colors p-2">
                                <i class="fas text-sm" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                        @error('password') 
                            <p class="text-xs text-red-600 mt-1.5 flex items-center gap-1">
                                <i class="fas fa-info-circle"></i> {{ $message }}
                            </p> 
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full py-3.5 bg-slate-900 text-white rounded-xl font-semibold text-sm hover:bg-slate-800 transition-all shadow-lg shadow-slate-900/10 active:scale-[0.98] flex items-center justify-center gap-2">
                        Authenticate Access
                        <i class="fas fa-shield-alt text-xs"></i>
                    </button>
                </form>

                <!-- Footer -->
                <p class="text-center text-sm text-slate-500 mt-6 pt-4 border-t border-slate-50">
                    Not an admin?
                    <a href="{{ route('login') }}" class="font-semibold text-slate-900 hover:underline ml-1">
                        Customer sign in
                    </a>
                </p>
            </div>

            <!-- Back to home -->
            <div class="text-center mt-6">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-slate-900 transition-colors">
                    <i class="fas fa-arrow-left text-xs"></i>
                    Return to site
                </a>
            </div>

        </div>

    </body>
</html>
