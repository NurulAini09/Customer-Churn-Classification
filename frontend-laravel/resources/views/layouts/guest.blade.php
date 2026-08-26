<!doctype html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Klasifikasi Churn Pelanggan - Random Forest + PSO')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
      @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
      <script src="https://cdn.tailwindcss.com"></script>
      <script src="https://unpkg.com/lucide@latest"></script>
      <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
      <script>
        tailwind.config = {
          theme: {
            extend: {
              fontFamily: { sans: ['Poppins', 'ui-sans-serif', 'system-ui'] }
            }
          }
        }
      </script>
    @endif

    <style>
      html {
        scroll-behavior: smooth;
      }
      *, *::before, *::after {
        font-family: 'Poppins', ui-sans-serif, system-ui, sans-serif;
      }
      input, select, textarea, button {
        font-family: 'Poppins', ui-sans-serif, system-ui, sans-serif;
      }
      body {
        font-family: 'Poppins', ui-sans-serif, system-ui, sans-serif;
      }
      /* Soft Custom Scrollbar */
      ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
      }
      ::-webkit-scrollbar-track {
        background: #F0F4F9;
      }
      ::-webkit-scrollbar-thumb {
        background: #CBD5E1;
        border-radius: 9999px;
        transition: background 0.2s ease;
      }
      ::-webkit-scrollbar-thumb:hover {
        background: #94A3B8;
      }
    </style>
  </head>
  <body class="min-h-screen bg-[#F8FAFC] text-slate-700 antialiased">
    @yield('content')

    <script>
      document.addEventListener('DOMContentLoaded', () => {
        if (window.lucide && typeof window.lucide.createIcons === 'function') {
          window.lucide.createIcons();
        }
      });
    </script>
  </body>
</html>
