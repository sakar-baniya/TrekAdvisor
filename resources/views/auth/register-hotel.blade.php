<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'TrekAdvisor') }} - Hotel Partner Registration</title>

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
        
        <div class="w-full max-w-xl">
            
            <!-- Logo + Brand -->
            <div class="flex flex-col items-center mb-6">
                <img src="{{ asset('images/ui/trekadvisorLOGO.png') }}" 
                     class="w-14 h-14 rounded-2xl bg-slate-900 p-2 shadow-lg shadow-slate-900/10 mb-3" 
                     alt="TrekAdvisor" />
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">TrekAdvisor</h1>
                <p class="text-xs text-slate-500 font-medium uppercase tracking-widest mt-1">Partnership Program</p>
            </div>

            <!-- Card -->
            <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/60 p-8 md:p-10">
                
                <!-- Header -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-slate-900">Register as a Hotel Partner</h2>
                    <p class="text-sm text-slate-500 mt-1">Grow your business by listing your property on TrekAdvisor.</p>
                </div>

                <!-- Session Status -->
                @if (session('status'))
                    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-sm text-emerald-700">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Validation Errors (General Alert) -->
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl flex items-start gap-3">
                        <i class="fas fa-exclamation-circle text-red-500 mt-0.5"></i>
                        <p class="text-sm text-red-700 font-medium">Please review the business details below and fix the errors.</p>
                    </div>
                @endif

                <form method="POST" action="{{ route('register.hotel') }}" class="space-y-6">
                    @csrf

                    <!-- Row 1: Owner Name + Phone -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="space-y-1.5">
                            <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Owner Name</label>
                            <div class="relative">
                                <i class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 text-sm"></i>
                                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                                       class="input-field w-full bg-slate-50 border border-slate-200 rounded-xl py-3 pl-11 pr-4 text-sm text-slate-900 placeholder-slate-400 focus:bg-white focus:border-slate-900 focus:outline-none transition-all"
                                       placeholder=" Rajesh Hamal" />
                            </div>
                            @error('name') <p class="text-[10px] font-black text-red-600 uppercase tracking-widest mt-1 italic">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-1.5">
                            <label for="phone" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Business Phone</label>
                            <div class="relative">
                                <i class="fas fa-phone absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 text-sm"></i>
                                <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" inputmode="numeric" pattern="\d{10}" minlength="10" maxlength="10"
                                       class="input-field w-full bg-slate-50 border border-slate-200 rounded-xl py-3 pl-11 pr-4 text-sm text-slate-900 placeholder-slate-400 focus:bg-white focus:border-slate-900 focus:outline-none transition-all"
                                       placeholder="9800012345" />
                            </div>
                            @error('phone') <p class="text-[10px] font-black text-red-600 uppercase tracking-widest mt-1 italic">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Row 2: Business Email + Property Name -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="space-y-1.5">
                            <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Business Email</label>
                            <div class="relative">
                                <i class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 text-sm"></i>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                                       class="input-field w-full bg-slate-50 border border-slate-200 rounded-xl py-3 pl-11 pr-4 text-sm text-slate-900 placeholder-slate-400 focus:bg-white focus:border-slate-900 focus:outline-none transition-all"
                                       placeholder="property@example.com" />
                            </div>
                            @error('email') <p class="text-[10px] font-black text-red-600 uppercase tracking-widest mt-1 italic">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-1.5">
                            <label for="hotel_name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Property Name</label>
                            <div class="relative">
                                <i class="fas fa-building absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 text-sm"></i>
                                <input id="hotel_name" type="text" name="hotel_name" value="{{ old('hotel_name') }}" required
                                       class="input-field w-full bg-slate-50 border border-slate-200 rounded-xl py-3 pl-11 pr-4 text-sm text-slate-900 placeholder-slate-400 focus:bg-white focus:border-slate-900 focus:outline-none transition-all"
                                       placeholder="Hotel Everest View" />
                            </div>
                            @error('hotel_name') <p class="text-[10px] font-black text-red-600 uppercase tracking-widest mt-1 italic">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Row 3: Passwords -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="space-y-1.5" x-data="{ show: false }">
                            <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Password</label>
                            <div class="relative">
                                <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 text-sm"></i>
                                <input id="password" :type="show ? 'text' : 'password'" name="password" required minlength="8"
                                       class="input-field w-full bg-slate-50 border border-slate-200 rounded-xl py-3 pl-11 pr-12 text-sm text-slate-900 placeholder-slate-400 focus:bg-white focus:border-slate-900 focus:outline-none transition-all"
                                       placeholder="Enter password" />
                                <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-300 hover:text-slate-700 p-2">
                                    <i class="fas text-[10px]" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                                </button>
                            </div>
                            @error('password') <p class="text-[10px] font-black text-red-600 uppercase tracking-widest mt-1 italic">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-1.5" x-data="{ show: false }">
                            <label for="password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Confirm</label>
                            <div class="relative">
                                <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 text-sm"></i>
                                <input id="password_confirmation" :type="show ? 'text' : 'password'" name="password_confirmation" required minlength="8"
                                       class="input-field w-full bg-slate-50 border border-slate-200 rounded-xl py-3 pl-11 pr-12 text-sm text-slate-900 placeholder-slate-400 focus:bg-white focus:border-slate-900 focus:outline-none transition-all"
                                       placeholder="Repeat password" />
                                <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-300 hover:text-slate-700 p-2">
                                    <i class="fas text-[10px]" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full py-4 bg-slate-900 text-white rounded-xl font-bold uppercase tracking-[0.2em] text-[10px] hover:bg-slate-800 transition-all shadow-lg shadow-slate-900/10 active:scale-[0.98] flex items-center justify-center gap-2 mt-4">
                        Apply for Partnership
                        <i class="fas fa-arrow-right text-[10px]"></i>
                    </button>
                </form>

                <!-- Footer Links -->
                <div class="mt-10 pt-8 border-t border-slate-50">
                    <div class="flex flex-col md:flex-row items-center justify-center gap-4 text-xs font-bold text-slate-400 uppercase tracking-widest text-center">
                        <p>Not a hotel owner? <a href="{{ route('register') }}" class="text-slate-900 hover:underline ml-1">Trekker Sign-up</a></p>
                        <span class="hidden md:inline text-slate-200">|</span>
                        <p>Have an account? <a href="{{ route('login') }}" class="text-slate-900 hover:underline ml-1">Sign in</a></p>
                    </div>
                </div>
            </div>

            <!-- Back to home -->
            <div class="text-center mt-8">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-slate-900 transition-colors">
                    <i class="fas fa-arrow-left text-[10px]"></i>
                    Back to TrekAdvisor
                </a>
            </div>
        </div>
    </body>
</html>
