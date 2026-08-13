<!doctype html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'ChurnPredict AI')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
      @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
      <script src="https://cdn.tailwindcss.com"></script>
      <script>
        tailwind.config = {
          theme: {
            extend: {
              fontFamily: { sans: ['Inter', 'ui-sans-serif', 'system-ui'] }
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
      body { font-family: Inter, ui-sans-serif, system-ui, sans-serif; }
      .app-stage {
        height: 100vh;
        overflow: hidden;
        background: #F3F6FA;
      }
      .app-shell {
        height: 100vh;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 230px minmax(0, 1fr);
        background: #F3F6FA;
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
      .soft-grid {
        background: #F3F6FA;
      }
      .loader-spin {
        animation: loaderSpin .8s linear infinite;
      }
      .page-loader-backdrop {
        background: rgba(255, 255, 255, 0.32);
        backdrop-filter: blur(2px);
        -webkit-backdrop-filter: blur(2px);
      }
      .page-loader-card {
        background: rgba(255, 255, 255, 0.94);
      }
      @keyframes loaderSpin {
        to { transform: rotate(360deg); }
      }
      html.dark body,
      html.dark [class*="text-[#102A43]"],
      html.dark [class*="text-[#0B1F33]"],
      html.dark [class*="text-[#6F3D58]"] {
        color: #E5E7EB !important;
      }
      html.dark [class*="text-[#475569]"],
      html.dark [class*="text-[#64748B]"],
      html.dark [class*="text-[#6B7C93]"] {
        color: #9CA3AF !important;
      }
      html.dark .app-stage,
      html.dark .app-shell,
      html.dark .soft-grid,
      html.dark [class*="bg-[#F3F6FA]"],
      html.dark [class*="bg-[#F6F8FB]"],
      html.dark [class*="bg-[#F8FAFC]"] {
        background-color: #0F172A !important;
      }
      html.dark .bg-white,
      html.dark [class*="bg-white"] {
        background-color: #111827 !important;
      }
      html.dark [class*="border-[#D8E2EC]"],
      html.dark [class*="border-[#E5EEF8]"] {
        border-color: #243244 !important;
      }
      html.dark [class*="bg-[#FFF0F6]"] {
        background-color: #4A1730 !important;
      }
      html.dark .page-loader-backdrop {
        background: rgba(15, 23, 42, 0.26) !important;
      }
      html.dark .page-loader-card {
        background: rgba(17, 24, 39, 0.96) !important;
      }
      html.dark [class*="border-[#F6C7DA]"] {
        border-color: #8A3158 !important;
      }
      html.dark table thead tr {
        background-color: #172033 !important;
      }
      html.dark table tbody tr:hover {
        background-color: #172033 !important;
      }
      html.dark input,
      html.dark select,
      html.dark textarea {
        background-color: #0F172A !important;
        color: #E5E7EB !important;
        border-color: #243244 !important;
      }
      @media (max-width: 1023px) {
        .app-stage { min-height: 100vh; height: auto; overflow: visible; }
        .app-shell { min-height: 100vh; height: auto; display: block; box-shadow: none; }
      }
    </style>
  </head>
  <body class="h-screen overflow-hidden text-[#102A43] antialiased lg:overflow-hidden max-lg:h-auto max-lg:overflow-auto">
    @php
      $navigation = [
        ['key' => 'dashboard', 'label' => 'Dashboard', 'route' => 'prediction.index', 'icon' => 'layout-dashboard'],
        ['key' => 'prediction', 'label' => 'Klasifikasi Churn', 'route' => 'prediction.page', 'icon' => 'activity'],
        ['key' => 'history', 'label' => 'Riwayat Klasifikasi', 'route' => 'prediction.history.page', 'icon' => 'history'],
        ['key' => 'model', 'label' => 'Informasi Model', 'route' => 'prediction.model.page', 'icon' => 'cpu'],
        ['key' => 'about', 'label' => 'Tentang Sistem', 'route' => 'prediction.about.page', 'icon' => 'info'],
        ['key' => 'profile', 'label' => 'Profile', 'route' => 'profile.page', 'icon' => 'user'],
        ['key' => 'users', 'label' => 'Users', 'route' => 'users.page', 'icon' => 'users'],
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
        <div class="page-loader-card flex w-[245px] items-center gap-3 rounded-md border border-[#E7D4DE] px-4 py-3 text-left shadow-[0_18px_55px_rgba(122,63,93,0.14)]">
          <span class="loader-spin h-6 w-6 shrink-0 rounded-full border-[3px] border-[#F6C7DA] border-t-[#DB5A8D]"></span>
          <span>
            <span class="block text-[13px] font-semibold text-[#102A43]">Memuat halaman</span>
            <span class="mt-0.5 block text-[12px] text-[#64748B]">Menyiapkan tampilan...</span>
          </span>
        </div>
      </div>

      <button
        type="button"
        class="fixed bottom-5 right-5 z-50 grid h-10 w-10 place-items-center rounded-md border border-[#D8E2EC] bg-white text-[#7A3F5D] shadow-[0_12px_30px_rgba(15,23,42,0.08)] transition hover:bg-[#F8FAFC]"
        x-data="{ darkMode: localStorage.getItem('theme') === 'dark' }"
        x-init="$watch('darkMode', value => { localStorage.setItem('theme', value ? 'dark' : 'light'); document.documentElement.classList.toggle('dark', value); })"
        x-on:click="darkMode = !darkMode"
        x-bind:title="darkMode ? 'Gunakan mode terang' : 'Gunakan mode gelap'"
        aria-label="Ubah mode terang atau gelap"
      >
        <i data-lucide="moon" x-show="!darkMode" class="h-4 w-4"></i>
        <i data-lucide="sun" x-show="darkMode" class="h-4 w-4"></i>
      </button>

      <div class="app-shell transition-[grid-template-columns] duration-200" x-bind:style="sidebarCollapsed ? 'grid-template-columns: 72px minmax(0, 1fr)' : 'grid-template-columns: 230px minmax(0, 1fr)'">
      <div class="hidden h-screen overflow-hidden border-r border-[#D8E2EC] bg-white lg:block">
        @include('partials.sidebar', ['navigation' => $navigation])
      </div>

      <div x-cloak x-show="sidebarOpen" class="fixed inset-0 z-50 lg:hidden">
        <button type="button" class="absolute inset-0 bg-slate-950/30" x-on:click="sidebarOpen = false" aria-label="Tutup sidebar"></button>
        <aside class="relative h-full w-72 bg-white shadow-2xl" x-data="{ sidebarCollapsed: false }">
          @include('partials.sidebar', ['navigation' => $navigation])
        </aside>
      </div>

      <div class="flex min-h-0 min-w-0 flex-col soft-grid">
        <main id="page-content" class="min-h-0 flex-1 overflow-y-auto px-5 pb-5 pt-0 sm:px-6">
          @yield('content')
        </main>
      </div>
      </div>
    </div>

    @unless (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
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
