
@php
    $siteLogo = \App\Models\Setting::logoUrl();
    $siteLogoText = \App\Models\Setting::logoText();
    $siteFavicon = \App\Models\Setting::faviconUrl();
    $siteTitle = \App\Models\Setting::siteTitle();
    $showcaseProject = \App\Models\Project::where('status', 'approved')
        ->whereNotNull('featured_image')
        ->latest()
        ->skip(2)
        ->first()
        ?? \App\Models\Project::where('status', 'approved')->whereNotNull('featured_image')->first()
        ?? \App\Models\Project::whereNotNull('featured_image')->first();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editorial Admin Login | {{ $siteTitle }}</title>
    @if($siteFavicon)
        <link rel="icon" type="image/x-icon" href="{{ $siteFavicon }}">
        <link rel="shortcut icon" href="{{ $siteFavicon }}">
        <link rel="apple-touch-icon" href="{{ $siteFavicon }}">
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .site-cinzel {
            font-family: 'Cinzel', serif;
            letter-spacing: -0.04em;
        }
    </style>
</head>

<body class="min-h-screen bg-slate-950 text-slate-100 antialiased selection:bg-amber-400 selection:text-black relative flex flex-col justify-between p-4 sm:p-6">

    @if($showcaseProject && $showcaseProject->featured_image)
        <div class="fixed inset-0 z-0">
            <img src="{{ $showcaseProject->featured_image }}"
                 alt="{{ $showcaseProject->title }}"
                 class="w-full h-full object-cover">

            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/75 to-slate-950/85 backdrop-blur-[2px]"></div>
        </div>
    @else
        <div class="fixed inset-0 z-0 bg-slate-950"></div>
    @endif

    <main class="relative z-10 w-full max-w-lg mx-auto my-auto py-6">

        <div class="bg-[rgb(104_123_147_/_95%)] backdrop-blur-xl border border-white/20 shadow-2xl rounded-2xl p-8 sm:p-10 space-y-7">

            <div class="text-center">
                <h1 class="text-3xl sm:text-4xl font-bold tracking-tight text-white drop-shadow-md">
                    Admin Sign In
                </h1>

                <p class="mt-2 text-sm sm:text-base font-medium text-white/85">
                    Sign in to access the administration panel
                </p>
            </div>

            @if($errors->any())
                <div class="p-3.5 bg-red-950/75 border border-red-400/40 rounded-xl text-sm text-red-100 flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>

                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            @if(session('success'))
                <div class="p-3.5 bg-emerald-950/75 border border-emerald-400/40 rounded-xl text-sm text-emerald-100 flex items-center gap-2">
                    <span class="text-emerald-300 font-bold text-base">✓</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <form action="{{ route('admin.login') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="admin_email" class="block text-sm font-bold uppercase tracking-wider text-white mb-2">
                        Email
                    </label>

                    <input id="admin_email"
                           name="email"
                           type="email"
                           autocomplete="email"
                           required
                           value="{{ old('email') }}"
                           placeholder="admin@magazine.com"
                           class="w-full px-4 py-3 text-sm sm:text-base bg-slate-950/80 hover:bg-slate-950 focus:bg-slate-950 border @error('email') border-red-400 @else border-white/20 @enderror rounded-xl text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-400/30 focus:border-amber-400 transition-all">
                </div>

                <div>
                    <label for="admin_password" class="block text-sm font-bold uppercase tracking-wider text-white mb-2">
                        Password
                    </label>

                    <div class="relative">
                        <input id="admin_password"
                               name="password"
                               type="password"
                               autocomplete="current-password"
                               required
                               placeholder="••••••••"
                               class="w-full px-4 pr-12 py-3 text-sm sm:text-base bg-slate-950/80 hover:bg-slate-950 focus:bg-slate-950 border @error('password') border-red-400 @else border-white/20 @enderror rounded-xl text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-400/30 focus:border-amber-400 transition-all">

                        <button type="button"
                                onclick="togglePassword('admin_password', 'password-toggle-icon')"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-white/70 hover:text-white focus:outline-none"
                                aria-label="Toggle password visibility">

                            <svg id="password-toggle-icon"
                                 class="w-5 h-5"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between text-sm pt-1">
                    <label class="flex items-center gap-2.5 cursor-pointer text-white/90 hover:text-white">
                        <input type="checkbox"
                               name="remember"
                               class="w-4.5 h-4.5 rounded border-white/30 bg-slate-950 text-amber-500 focus:ring-0">

                        <span>Keep me signed in</span>
                    </label>
                </div>

                <div class="pt-2">
                    <button type="submit"
                            class="w-full py-3.5 px-5 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 text-sm sm:text-base font-bold rounded-xl transition-all shadow-lg shadow-amber-500/20 flex items-center justify-center gap-2">
                        <span>Sign In</span>
                    </button>
                </div>
            </form>

            <div class="pt-5 border-t border-white/20 text-center">
                <a href="{{ route('author.login') }}"
                   class="text-sm font-medium text-white/80 hover:text-amber-300 transition-colors">
                    Author Studio Login &rarr;
                </a>
            </div>

        </div>
    </main>

    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

            if (input.type === 'password') {
                input.type = 'text';

                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>';
            } else {
                input.type = 'password';

                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
            }
        }
    </script>

</body>
</html>
