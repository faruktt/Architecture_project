@php
    $siteLogo = \App\Models\Setting::logoUrl();
    $siteLogoText = \App\Models\Setting::logoText();
    $showcaseProject = \App\Models\Project::where('status', 'approved')
        ->whereNotNull('featured_image')
        ->latest()
        ->skip(1)
        ->first()
        ?? \App\Models\Project::where('status', 'approved')->whereNotNull('featured_image')->first()
        ?? \App\Models\Project::whereNotNull('featured_image')->first();
    $activeCountries = \App\Models\Country::active()->orderBy('name')->get();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Author Registration | {{ $siteLogoText }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .site-cinzel { font-family: 'Cinzel', serif; letter-spacing: -0.04em; }
    </style>
</head>
<body class="min-h-screen bg-zinc-950 text-zinc-900 antialiased selection:bg-black selection:text-white relative flex flex-col justify-between p-4 sm:p-6">

    <!-- Real Website Project Background -->
    @if($showcaseProject && $showcaseProject->featured_image)
        <div class="fixed inset-0 z-0">
            <img src="{{ $showcaseProject->featured_image }}"
                 alt="{{ $showcaseProject->title }}"
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/60 to-black/75 backdrop-blur-[2px]"></div>
        </div>
    @else
        <div class="fixed inset-0 z-0 bg-zinc-950"></div>
    @endif

    <!-- Top Navigation -->
    <header class="relative z-10 w-full max-w-5xl mx-auto flex items-center justify-between py-2">
        <a href="{{ route('home') }}" class="flex items-center gap-2 text-white">
            @if($siteLogo)
                <img src="{{ $siteLogo }}" alt="{{ $siteLogoText }}" class="h-7 max-w-[130px] object-contain brightness-0 invert">
            @else
                <span class="text-xl font-black tracking-tight text-white site-cinzel lowercase">{{ $siteLogoText }}</span>
            @endif
            <span class="text-[9px] font-bold uppercase tracking-widest bg-white/20 text-white px-2 py-0.5 rounded backdrop-blur">Studio</span>
        </a>

        <a href="{{ route('home') }}" class="text-xs font-semibold text-white/80 hover:text-white transition-colors flex items-center gap-1.5 bg-black/30 hover:bg-black/50 px-3 py-1.5 rounded-full border border-white/10 backdrop-blur">
            <span>&larr;</span>
            <span>Back to Magazine</span>
        </a>
    </header>

    <!-- Centered Registration Card -->
    <main class="relative z-10 w-full max-w-lg mx-auto my-auto py-6">
        <div class="bg-white/95 backdrop-blur-xl border border-white/40 shadow-2xl rounded-2xl p-7 sm:p-9 space-y-5">

            <!-- Card Header -->
            <div class="text-center">
                <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-800 bg-emerald-50 border border-emerald-200 px-2.5 py-1 rounded-full inline-block mb-2">
                    Author & Architect Registration
                </span>
                <h1 class="text-2xl font-bold tracking-tight text-zinc-950">Create Profile</h1>
            </div>

            <!-- Error Alerts -->
            @if($errors->any())
                <div class="p-3 bg-red-50 border border-red-200 rounded-xl text-xs text-red-800 space-y-1">
                    <ul class="list-disc list-inside text-[11px] space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('author.register') }}" method="POST" class="space-y-3.5">
                @csrf

                <!-- Name & Title -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="name" class="block text-[11px] font-bold uppercase tracking-wider text-zinc-700 mb-1">
                            Full Name <span class="text-red-500">*</span>
                        </label>
                        <input id="name"
                               name="name"
                               type="text"
                               required
                               value="{{ old('name') }}"
                               placeholder="Ar. Rafiqul Islam"
                               class="w-full px-3 py-2 text-xs bg-zinc-50 hover:bg-white focus:bg-white border @error('name') border-red-500 @else border-zinc-300 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-black/10 focus:border-black transition-all">
                    </div>

                    <div>
                        <label for="title" class="block text-[11px] font-bold uppercase tracking-wider text-zinc-700 mb-1">
                            Role / Title
                        </label>
                        <input id="title"
                               name="title"
                               type="text"
                               value="{{ old('title') }}"
                               placeholder="Principal Architect"
                               class="w-full px-3 py-2 text-xs bg-zinc-50 hover:bg-white focus:bg-white border border-zinc-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-black/10 focus:border-black transition-all">
                    </div>
                </div>

                <!-- Email & Firm -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="email" class="block text-[11px] font-bold uppercase tracking-wider text-zinc-700 mb-1">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input id="email"
                               name="email"
                               type="email"
                               required
                               value="{{ old('email') }}"
                               placeholder="architect@domain.com"
                               class="w-full px-3 py-2 text-xs bg-zinc-50 hover:bg-white focus:bg-white border @error('email') border-red-500 @else border-zinc-300 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-black/10 focus:border-black transition-all">
                    </div>

                    <div>
                        <label for="company" class="block text-[11px] font-bold uppercase tracking-wider text-zinc-700 mb-1">
                            Firm / Studio
                        </label>
                        <input id="company"
                               name="company"
                               type="text"
                               value="{{ old('company') }}"
                               placeholder="Studio Forma"
                               class="w-full px-3 py-2 text-xs bg-zinc-50 hover:bg-white focus:bg-white border border-zinc-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-black/10 focus:border-black transition-all">
                    </div>
                </div>

                <!-- Country & Website -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="country" class="block text-[11px] font-bold uppercase tracking-wider text-zinc-700 mb-1">
                            Country <span class="text-red-500">*</span>
                        </label>
                        <select id="country"
                                name="country"
                                required
                                class="w-full px-3 py-2 text-xs bg-zinc-50 hover:bg-white focus:bg-white border @error('country') border-red-500 @else border-zinc-300 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-black/10 focus:border-black transition-all">
                            @if(isset($activeCountries) && $activeCountries->count() > 0)
                                @foreach($activeCountries as $c)
                                    <option value="{{ $c->name }}" {{ old('country', 'Bangladesh') == $c->name ? 'selected' : '' }}>
                                        {{ $c->name }}
                                    </option>
                                @endforeach
                                <option value="Others" {{ old('country') == 'Others' ? 'selected' : '' }}>Others</option>
                            @else
                                <option value="Bangladesh" selected>Bangladesh</option>
                                <option value="Malaysia">Malaysia</option>
                                <option value="Indonesia">Indonesia</option>
                                <option value="Japan">Japan</option>
                                <option value="United States">United States</option>
                                <option value="United Kingdom">United Kingdom</option>
                                <option value="Others">Others</option>
                            @endif
                        </select>
                    </div>

                    <div>
                        <label for="website" class="block text-[11px] font-bold uppercase tracking-wider text-zinc-700 mb-1">
                            Studio Website
                        </label>
                        <input id="website"
                               name="website"
                               type="url"
                               value="{{ old('website') }}"
                               placeholder="https://studio.com"
                               class="w-full px-3 py-2 text-xs bg-zinc-50 hover:bg-white focus:bg-white border border-zinc-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-black/10 focus:border-black transition-all">
                    </div>
                </div>

                <!-- Password & Confirm Password -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="password" class="block text-[11px] font-bold uppercase tracking-wider text-zinc-700 mb-1">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input id="password"
                                   name="password"
                                   type="password"
                                   required
                                   placeholder="Min. 6 chars"
                                   class="w-full px-3 pr-8 py-2 text-xs bg-zinc-50 hover:bg-white focus:bg-white border @error('password') border-red-500 @else border-zinc-300 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-black/10 focus:border-black transition-all">
                            <button type="button"
                                    onclick="togglePassword('password', 'password-toggle-icon')"
                                    class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-zinc-400 hover:text-zinc-700 focus:outline-none"
                                    aria-label="Toggle password visibility">
                                <svg id="password-toggle-icon" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-[11px] font-bold uppercase tracking-wider text-zinc-700 mb-1">
                            Confirm Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input id="password_confirmation"
                                   name="password_confirmation"
                                   type="password"
                                   required
                                   placeholder="Repeat password"
                                   class="w-full px-3 pr-8 py-2 text-xs bg-zinc-50 hover:bg-white focus:bg-white border border-zinc-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-black/10 focus:border-black transition-all">
                            <button type="button"
                                    onclick="togglePassword('password_confirmation', 'password-confirm-toggle-icon')"
                                    class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-zinc-400 hover:text-zinc-700 focus:outline-none"
                                    aria-label="Toggle confirm password visibility">
                                <svg id="password-confirm-toggle-icon" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit"
                            class="w-full py-3 px-5 bg-black hover:bg-zinc-800 text-white text-xs sm:text-sm font-bold rounded-xl transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                        <span>Create Account</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </div>
            </form>

            <!-- Links -->
            <div class="pt-4 border-t border-zinc-100 text-center space-y-2">
                <p class="text-xs text-zinc-600">
                    Already have an account?
                    <a href="{{ route('author.login') }}" class="font-bold text-black hover:underline ml-1">
                        Sign In Here &rarr;
                    </a>
                </p>
                <div>
                    <a href="{{ route('admin.login') }}" class="text-[11px] text-zinc-400 hover:text-zinc-700 transition-colors">
                        Admin Login &rarr;
                    </a>
                </div>
            </div>

        </div>
    </main>

    <!-- Footer Attribution -->
    <footer class="relative z-10 w-full max-w-5xl mx-auto flex flex-col sm:flex-row items-center justify-between text-[11px] text-white/70 py-2 gap-2">
        <div>
            @if($showcaseProject)
                <span>Featured Architecture: <strong>{{ $showcaseProject->title }}</strong></span>
                @if($showcaseProject->country)
                    <span class="text-white/40">&bull;</span>
                    <span>{{ $showcaseProject->country }}</span>
                @endif
            @endif
        </div>
        <div>
            <span>&copy; {{ date('Y') }} {{ $siteLogoText }} MAGAZINE</span>
        </div>
    </footer>

    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />';
            } else {
                input.type = 'password';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
            }
        }
    </script>
</body>
</html>
