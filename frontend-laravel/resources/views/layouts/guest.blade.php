<!doctype html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Login - ChurnPredict AI')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
      @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
      <script src="https://cdn.tailwindcss.com"></script>
      <script src="https://unpkg.com/lucide@latest"></script>
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

    <style>
      body { font-family: Inter, ui-sans-serif, system-ui, sans-serif; }
    </style>
  </head>
  <body class="min-h-screen bg-[#F3F6FA] text-[#102A43] antialiased">
    @yield('content')

    @unless (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
      <script>
        if (window.lucide) {
          window.lucide.createIcons();
        }
      </script>
    @endunless
  </body>
</html>
