@php
    $siteLogo = \App\Models\Setting::logoUrl();
    $siteLogoText = \App\Models\Setting::logoText();
    $showcaseProject = \App\Models\Project::where('status', 'approved')
        ->whereNotNull('featured_image')
        ->latest()
        ->first()
        ?? \App\Models\Project::whereNotNull('featured_image')->first();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Author Login | {{ $siteLogoText }}</title>

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

    <!-- Centered Login Card -->
    <main class="relative z-10 w-full max-w-md mx-auto my-auto py-6">
        <div class="bg-white/95 backdrop-blur-xl border border-white/40 shadow-2xl rounded-2xl p-7 sm:p-9 space-y-6">

            <!-- Card Header -->
            <div class="text-center">
                <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-800 bg-emerald-50 border border-emerald-200 px-2.5 py-1 rounded-full inline-block mb-2">
                    Author & Architect Portal
                </span>
                <h1 class="text-2xl font-bold tracking-tight text-zinc-950">Sign In</h1>
            </div>

            <!-- Error Alerts -->
            @if($errors->any())
                <div class="p-3 bg-red-50 border border-red-200 rounded-xl text-xs text-red-800 flex items-center gap-2">
                    <svg class="w-4 h-4 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            @if(session('success'))
                <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-xs text-emerald-900 flex items-center gap-2">
                    <span class="text-emerald-600 font-bold">✓</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('author.login') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-zinc-700 mb-1.5">
                        Email Address
                    </label>
                    <input id="email"
                           name="email"
                           type="email"
                           autocomplete="email"
                           required
                           value="{{ old('email') }}"
                           placeholder="architect@domain.com"
                           class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-zinc-50 hover:bg-white focus:bg-white border @error('email') border-red-500 @else border-zinc-300 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-black/10 focus:border-black transition-all">
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold uppercase tracking-wider text-zinc-700 mb-1.5">
                        Password
                    </label>
                    <div class="relative">
                        <input id="password"
                               name="password"
                               type="password"
                               autocomplete="current-password"
                               required
                               placeholder="••••••••"
                               class="w-full px-3.5 pr-10 py-2.5 text-xs sm:text-sm bg-zinc-50 hover:bg-white focus:bg-white border @error('password') border-red-500 @else border-zinc-300 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-black/10 focus:border-black transition-all">
                        <button type="button"
                                onclick="togglePassword('password', 'password-toggle-icon')"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-zinc-400 hover:text-zinc-700 focus:outline-none"
                                aria-label="Toggle password visibility">
                            <svg id="password-toggle-icon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs pt-0.5">
                    <label class="flex items-center gap-2 cursor-pointer text-zinc-600 hover:text-black">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-zinc-300 text-black focus:ring-black">
                        <span>Remember me</span>
                    </label>
                </div>

                <div class="pt-1">
                    <button type="submit"
                            class="w-full py-3 px-5 bg-black hover:bg-zinc-800 text-white text-xs sm:text-sm font-bold rounded-xl transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                        <span>Sign In</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </div>
            </form>

            <!-- Links -->
            <div class="pt-4 border-t border-zinc-100 text-center space-y-2">
                <p class="text-xs text-zinc-600">
                    Don't have an author profile?
                    <a href="{{ route('author.register') }}" class="font-bold text-black hover:underline ml-1">
                        Register Here &rarr;
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
