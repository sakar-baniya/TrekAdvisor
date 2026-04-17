<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'TrekAdvisor') }} - Register</title>

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
            
            <!-- Logo + Brand (Compact, horizontal) -->
            <div class="flex items-center justify-center gap-3 mb-5">
                <img src="{{ asset('images/ui/trekadvisorLOGO.png') }}" 
                     class="w-11 h-11 rounded-xl bg-slate-900 p-1.5 shadow-lg shadow-slate-900/10" 
                     alt="TrekAdvisor" />
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">TrekAdvisor</h1>
            </div>

            <!-- Card -->
            <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/60 p-7">
                
                <!-- Header -->
                <div class="mb-5">
                    <h2 class="text-xl font-bold text-slate-900">Create your account</h2>
                    <p class="text-sm text-slate-500 mt-0.5">Sign up to book treks and manage trips</p>
                </div>

                <!-- Validation Errors -->
                @if ($errors->any())
                    <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-xl flex items-start gap-2">
                        <i class="fas fa-exclamation-circle text-red-500 mt-0.5 text-sm"></i>
                        <p class="text-xs text-red-700">Please fix the errors below and try again.</p>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    <!-- Row 1: Name + Phone -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label for="name" class="block text-xs font-medium text-slate-700 mb-1">Full name</label>
                            <div class="relative">
                                <i class="fas fa-user absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" maxlength="80"
                                       class="input-field w-full bg-slate-50 border border-slate-200 rounded-lg py-2.5 pl-9 pr-3 text-sm text-slate-900 placeholder-slate-400 focus:bg-white focus:border-slate-900 focus:outline-none transition-all"
                                       placeholder="Suman Shrestha" />
                            </div>
                            @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-xs font-medium text-slate-700 mb-1">
                                Phone <span class="text-slate-400 font-normal">(optional)</span>
                            </label>
                            <div class="relative">
                                <i class="fas fa-phone absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" autocomplete="tel" inputmode="numeric" pattern="\d{10}" minlength="10" maxlength="10"
                                       class="input-field w-full bg-slate-50 border border-slate-200 rounded-lg py-2.5 pl-9 pr-3 text-sm text-slate-900 placeholder-slate-400 focus:bg-white focus:border-slate-900 focus:outline-none transition-all"
                                       placeholder="9800012345" />
                            </div>
                            @error('phone') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Email (full width) -->
                    <div>
                        <label for="email" class="block text-xs font-medium text-slate-700 mb-1">Email address</label>
                        <div class="relative">
                            <i class="fas fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" maxlength="255"
                                   class="input-field w-full bg-slate-50 border border-slate-200 rounded-lg py-2.5 pl-9 pr-3 text-sm text-slate-900 placeholder-slate-400 focus:bg-white focus:border-slate-900 focus:outline-none transition-all"
                                   placeholder="you@example.com" />
                        </div>
                        @error('email') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Row 2: Password + Confirm -->
                    <div class="grid grid-cols-2 gap-3">
                        <div x-data="{ show: false }">
                            <label for="password" class="block text-xs font-medium text-slate-700 mb-1">Password</label>
                            <div class="relative">
                                <i class="fas fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                <input id="password" :type="show ? 'text' : 'password'" name="password" required autocomplete="new-password" minlength="8"
                                       class="input-field w-full bg-slate-50 border border-slate-200 rounded-lg py-2.5 pl-9 pr-9 text-sm text-slate-900 placeholder-slate-400 focus:bg-white focus:border-slate-900 focus:outline-none transition-all"
                                       placeholder="Min. 8 chars" />
                                <button type="button" @click="show = !show" class="absolute right-2 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-700 p-1.5">
                                    <i class="fas text-xs" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                                </button>
                            </div>
                            @error('password') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div x-data="{ show: false }">
                            <label for="password_confirmation" class="block text-xs font-medium text-slate-700 mb-1">Confirm password</label>
                            <div class="relative">
                                <i class="fas fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                <input id="password_confirmation" :type="show ? 'text' : 'password'" name="password_confirmation" required autocomplete="new-password" minlength="8"
                                       class="input-field w-full bg-slate-50 border border-slate-200 rounded-lg py-2.5 pl-9 pr-9 text-sm text-slate-900 placeholder-slate-400 focus:bg-white focus:border-slate-900 focus:outline-none transition-all"
                                       placeholder="Repeat password" />
                                <button type="button" @click="show = !show" class="absolute right-2 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-700 p-1.5">
                                    <i class="fas text-xs" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                                </button>
                            </div>
                            @error('password_confirmation') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full py-3 bg-slate-900 text-white rounded-lg font-semibold text-sm hover:bg-slate-800 transition-all shadow-lg shadow-slate-900/10 active:scale-[0.98] flex items-center justify-center gap-2 mt-1">
                        Create account
                        <i class="fas fa-arrow-right text-xs"></i>
                    </button>
                </form>

                <!-- Footer (single line, separated by dot) -->
                <div class="mt-5 pt-4 border-t border-slate-100 text-center">
                    <p class="text-xs text-slate-500">
                        <a href="{{ route('login') }}" class="font-semibold text-slate-900 hover:underline">Sign in</a>
                        <span class="mx-2 text-slate-300">•</span>
                        <a href="{{ route('register.hotel') }}" class="font-semibold text-slate-900 hover:underline">Become a partner</a>
                        <span class="mx-2 text-slate-300">•</span>
                        <a href="{{ route('home') }}" class="font-medium text-slate-500 hover:text-slate-900">Home</a>
                    </p>
                </div>
            </div>

        </div>

    </body>
</html>