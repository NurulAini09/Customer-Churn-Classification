<!doctype html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'ChurnPredict AI')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
      @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
      <script src="https://cdn.tailwindcss.com"></script>
      <script>
        tailwind.config = {
          darkMode: 'class',
          theme: {
            extend: {
              fontFamily: { sans: ['Poppins', 'ui-sans-serif', 'system-ui', 'sans-serif'] },
              colors: {
                brand: {
                  50: '#EEF2FF',
                  100: '#E0E7FF',
                  500: '#6366F1',
                  600: '#4F46E5',
                  700: '#4338CA',
                }
              }
            }
          }
        }
      </script>
    @endif

    <script>
      try {
        if (localStorage.getItem('sidebarCollapsed') === 'true') {
          document.documentElement.classList.add('sidebar-collapsed');
        }
        if (localStorage.getItem('theme') === 'dark') {
          document.documentElement.classList.add('dark');
        }
      } catch (error) {}
    </script>

    <style>
      [x-cloak] { display: none !important; }
      *, *::before, *::after {
        font-family: 'Poppins', ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
      }
      input, select, textarea, button {
        font-family: 'Poppins', ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
      }
      body {
        font-family: 'Poppins', ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        background-color: #F0F4F9;
      }
      .app-stage {
        height: 100vh;
        overflow: hidden;
        background: #F0F4F9;
      }
      .app-shell {
        height: 100vh;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 240px minmax(0, 1fr);
        background: #F0F4F9;
      }
      html.sidebar-collapsed .app-shell {
        grid-template-columns: 72px minmax(0, 1fr);
      }
      html.sidebar-collapsed .sidebar-expanded-only {
        display: none !important;
      }
      html:not(.sidebar-collapsed) .sidebar-collapsed-only {
        display: none !important;
      }
      .loader-spin {
        animation: loaderSpin .75s linear infinite;
      }
      .page-loader-backdrop {
        background: rgba(15, 23, 42, 0.25);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
      }
      @keyframes loaderSpin {
        to { transform: rotate(360deg); }
      }

      /* Clean Number Inputs Without Spinner Clashing */
      input[type="number"]::-webkit-inner-spin-button,
      input[type="number"]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
      }
      input[type="number"] {
        -moz-appearance: textfield;
        appearance: textfield;
      }

      /* Dark Mode Palette Rules */
      html.dark body,
      html.dark .app-stage,
      html.dark .app-shell {
        background-color: #111827 !important;
        color: #E2E8F0 !important;
      }
      html.dark .bg-white {
        background-color: #111827 !important;
      }
      html.dark .bg-slate-50,
      html.dark .bg-\[\#F8FAFC\],
      html.dark .bg-\[\#F3F6FA\],
      html.dark .bg-\[\#F0F4F9\] {
        background-color: #111827 !important;
      }
      html.dark .border-slate-200,
      html.dark .border-slate-100,
      html.dark .border-\[\#D8E2EC\],
      html.dark .border-\[\#E5EEF8\] {
        border-color: rgba(30, 41, 59, 0.5) !important;
      }
      html.dark input,
      html.dark select,
      html.dark textarea {
        background-color: #111827 !important;
        color: #F8FAFC !important;
        border-color: #1F2937 !important;
      }
      html.dark input::placeholder,
      html.dark textarea::placeholder {
        color: #64748B !important;
      }

      @media (max-width: 1023px) {
        .app-stage { min-height: 100vh; height: auto; overflow: visible; }
        .app-shell { min-height: 100vh; height: auto; display: block; }
      }
    </style>
  </head>
  <body class="h-screen overflow-hidden text-slate-700 antialiased lg:overflow-hidden max-lg:h-auto max-lg:overflow-auto">
    @php
      $authUser = auth()->user();
      $currentUser = [
        'name' => $authUser?->name ?? 'Pengguna',
        'email' => $authUser?->email ?? 'user@churnpredict.local',
        'role' => $authUser ? ($authUser->email === 'admin@gmail.com' ? 'Administrator' : 'Pengguna') : 'Viewer',
        'photo_url' => null,
      ];

      if ($authUser) {
        $photoMatches = glob(public_path(sprintf('profile-photos/user-%d.*', $authUser->id))) ?: [];
        $currentUser['photo_url'] = $photoMatches ? asset('profile-photos/' . basename($photoMatches[0])) : null;
      }

      $userInitial = strtoupper(substr($currentUser['name'], 0, 1));
      $userPhoto = $currentUser['photo_url'];

      $menuGroups = [
        [
          'title' => 'Menu Utama',
          'items' => [
            ['key' => 'dashboard', 'label' => 'Dashboard', 'route' => 'prediction.index', 'icon' => 'layout-dashboard'],
            ['key' => 'prediction', 'label' => 'Klasifikasi Churn', 'route' => 'prediction.page', 'icon' => 'activity'],
            ['key' => 'history', 'label' => 'Riwayat Klasifikasi', 'route' => 'prediction.history.page', 'icon' => 'history'],
          ],
        ],
        [
          'title' => 'Model & Informasi',
          'items' => [
            ['key' => 'model', 'label' => 'Informasi Model', 'route' => 'prediction.model.page', 'icon' => 'cpu'],
            ['key' => 'about', 'label' => 'Tentang Sistem', 'route' => 'prediction.about.page', 'icon' => 'info'],
          ],
        ],
        [
          'title' => 'Pengaturan & Akun',
          'items' => [
            ['key' => 'profile', 'label' => 'Profil Saya', 'route' => 'profile.page', 'icon' => 'user'],
            ['key' => 'users', 'label' => 'Kelola User', 'route' => 'users.page', 'icon' => 'users'],
          ],
        ],
      ];
    @endphp

    <div
      x-data="{ sidebarOpen: false, sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true' }"
      x-init="
        document.documentElement.classList.toggle('sidebar-collapsed', sidebarCollapsed);
        $watch('sidebarCollapsed', value => {
          localStorage.setItem('sidebarCollapsed', value ? 'true' : 'false');
          document.documentElement.classList.toggle('sidebar-collapsed', value);
        })
      "
      class="app-stage"
    >
      <div id="page-loader" class="page-loader-backdrop pointer-events-none fixed inset-0 z-[80] hidden items-center justify-center">
        <div class="flex w-60 items-center gap-3.5 rounded-xl border border-slate-100 bg-white/95 px-4 py-3.5 shadow-xl backdrop-blur dark:border-slate-800 dark:bg-slate-900/95">
          <span class="loader-spin h-5 w-5 shrink-0 rounded-full border-2 border-indigo-200 border-t-indigo-600 dark:border-indigo-900 dark:border-t-indigo-400"></span>
          <div>
            <span class="block text-xs font-semibold text-slate-800 dark:text-slate-200">Memuat Halaman</span>
            <span class="text-[11px] text-slate-500 dark:text-slate-400">Menyiapkan data tampilan...</span>
          </div>
        </div>
      </div>

      <div class="app-shell transition-[grid-template-columns] duration-200" x-bind:style="sidebarCollapsed ? 'grid-template-columns: 72px minmax(0, 1fr)' : 'grid-template-columns: 240px minmax(0, 1fr)'">
        <div class="hidden h-screen overflow-hidden border-r border-slate-200/80 bg-white lg:block dark:border-slate-800 dark:bg-slate-900">
          @include('partials.sidebar', ['menuGroups' => $menuGroups])
        </div>

        <div x-cloak x-show="sidebarOpen" class="fixed inset-0 z-50 lg:hidden">
          <button type="button" class="absolute inset-0 bg-slate-950/40 backdrop-blur-xs" x-on:click="sidebarOpen = false" aria-label="Tutup sidebar"></button>
          <aside class="relative h-full w-72 overflow-hidden bg-white shadow-2xl dark:bg-slate-900" x-data="{ sidebarCollapsed: false }">
            @include('partials.sidebar', ['menuGroups' => $menuGroups])
          </aside>
        </div>

        <div class="flex min-h-0 min-w-0 flex-col bg-[#F0F4F9] dark:bg-[#0B0F19]">
          <!-- Top Header with Admin / User profile on top right -->
          <header class="sticky top-0 z-30 flex h-16 shrink-0 items-center justify-between border-b border-transparent bg-white/90 px-4 backdrop-blur-md sm:px-6 lg:px-8 dark:bg-slate-900/90">
            <!-- Left: Hamburger Menu Toggle Button (No Border) -->
            <div class="flex items-center gap-3">
              <!-- Mobile Hamburger Button -->
              <button
                type="button"
                class="grid h-9 w-9 place-items-center rounded-xl text-slate-600 transition hover:bg-slate-100/80 hover:text-slate-900 lg:hidden dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white"
                x-on:click="sidebarOpen = true"
                title="Buka Menu Navigasi"
                aria-label="Buka Menu"
              >
                <i data-lucide="menu" class="h-5 w-5"></i>
              </button>

              <!-- Desktop Hamburger / Toggle Button -->
              <button
                type="button"
                class="hidden h-9 w-9 place-items-center rounded-xl text-slate-600 transition hover:bg-slate-100/80 hover:text-slate-900 lg:grid dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white"
                x-on:click="sidebarCollapsed = !sidebarCollapsed"
                title="Perkecil / Perbesar Sidebar"
                aria-label="Toggle Sidebar"
              >
                <i data-lucide="menu" class="h-5 w-5"></i>
              </button>
            </div>

            <!-- Right: Status Badge, Dark Mode, and Admin Profile Dropdown -->
            <div class="flex items-center gap-3">
              <!-- System Status Pill -->
              <span class="hidden sm:inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700 dark:bg-blue-950/60 dark:text-blue-300">
                <span class="h-2 w-2 rounded-full bg-blue-600 animate-pulse"></span>
                Operasional
              </span>

              <!-- Dark Mode Switcher -->
              <button
                type="button"
                class="grid h-8.5 w-8.5 place-items-center rounded-full text-slate-500 transition hover:bg-slate-100 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white"
                x-data="{ darkMode: localStorage.getItem('theme') === 'dark' }"
                x-init="$watch('darkMode', value => { localStorage.setItem('theme', value ? 'dark' : 'light'); document.documentElement.classList.toggle('dark', value); })"
                x-on:click="darkMode = !darkMode"
                x-bind:title="darkMode ? 'Gunakan mode terang' : 'Gunakan mode gelap'"
                aria-label="Ubah mode tampilan"
              >
                <i data-lucide="moon" x-show="!darkMode" class="h-4 w-4"></i>
                <i data-lucide="sun" x-show="darkMode" class="h-4 w-4"></i>
              </button>

              <!-- Admin / User Profile Menu in Top Right Corner -->
              <div class="relative" x-data="{ userMenuOpen: false }" x-on:click.outside="userMenuOpen = false">
                <button
                  type="button"
                  x-on:click="userMenuOpen = !userMenuOpen"
                  class="flex items-center gap-2 rounded-full border border-transparent bg-slate-100/80 p-1 pl-1.5 pr-3 transition hover:bg-slate-200/80 dark:bg-slate-800 dark:hover:bg-slate-700"
                >
                  @if ($userPhoto)
                    <img src="{{ $userPhoto }}" alt="{{ $currentUser['name'] }}" class="h-7 w-7 shrink-0 rounded-full object-cover">
                  @else
                    <span class="grid h-7 w-7 shrink-0 place-items-center rounded-full bg-slate-600 text-xs font-bold text-white dark:bg-blue-600">{{ $userInitial }}</span>
                  @endif
                  <span class="text-xs font-semibold text-slate-800 dark:text-slate-200">{{ $currentUser['name'] }}</span>
                  <i data-lucide="chevron-down" class="h-3.5 w-3.5 text-slate-400 transition-transform duration-200" x-bind:class="userMenuOpen ? 'rotate-180' : ''"></i>
                </button>

                <!-- Dropdown -->
                <div
                  x-cloak
                  x-show="userMenuOpen"
                  x-transition:enter="transition ease-out duration-100"
                  x-transition:enter-start="transform opacity-0 scale-95"
                  x-transition:enter-end="transform opacity-100 scale-100"
                  x-transition:leave="transition ease-in duration-75"
                  x-transition:leave-start="transform opacity-100 scale-100"
                  x-transition:leave-end="transform opacity-0 scale-95"
                  class="absolute right-0 mt-2 w-52 rounded-2xl border border-transparent bg-white py-2 shadow-xl z-50 dark:bg-slate-900"
                >
                  <div class="border-b border-transparent px-4 py-2">
                    <span class="block truncate text-xs font-bold text-slate-800 dark:text-slate-200">{{ $currentUser['name'] }}</span>
                    <span class="block truncate text-[11px] text-slate-400">{{ $currentUser['email'] }}</span>
                  </div>

                  <a
                    href="{{ route('profile.page') }}"
                    class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-slate-700 transition hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800"
                    x-on:click="userMenuOpen = false"
                  >
                    <i data-lucide="user" class="h-4 w-4 text-slate-400"></i>
                    Profil Saya
                  </a>

                  <a
                    href="{{ route('users.page') }}"
                    class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-slate-700 transition hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800"
                    x-on:click="userMenuOpen = false"
                  >
                    <i data-lucide="users" class="h-4 w-4 text-slate-400"></i>
                    Kelola User
                  </a>

                  <div class="my-1 border-t border-transparent"></div>

                  <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button
                      type="submit"
                      class="flex w-full items-center gap-2.5 px-4 py-2 text-xs font-medium text-rose-600 transition hover:bg-rose-50 dark:text-rose-400 dark:hover:bg-rose-950/30"
                    >
                      <i data-lucide="log-out" class="h-4 w-4"></i>
                      Keluar
                    </button>
                  </form>
                </div>
              </div>
            </div>
          </header>

          <main id="page-content" class="min-h-0 flex-1 overflow-y-auto px-4 pb-8 pt-2 sm:px-6 lg:px-8">
            @yield('content')
          </main>
        </div>
      </div>
    </div>

    @unless (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
      <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
      <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
      <script src="https://unpkg.com/lucide@latest"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.5.2/flowbite.min.js"></script>
      <script>
        if (window.lucide) {
          window.lucide.createIcons();
        }
      </script>
    @endunless
  </body>
</html>
