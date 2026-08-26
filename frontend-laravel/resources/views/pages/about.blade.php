@extends('layouts.app')

@section('title', 'Tentang Sistem - ChurnPredict AI')
@section('page-title', 'Tentang Sistem')

@section('content')
  <div class="mb-5 flex flex-col gap-3 border-b border-slate-100/90 pb-4 sm:flex-row sm:items-center sm:justify-between dark:border-slate-800/80">
    <div>
      <h1 class="text-xl font-semibold tracking-tight text-slate-700 dark:text-white">Tentang Sistem</h1>
      <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">Arsitektur aplikasi klasifikasi churn pelanggan dan integrasi ML.</p>
    </div>
  </div>

  <div class="grid gap-6 xl:grid-cols-[1fr_380px]">
    <section class="rounded-xl border border-slate-100/90 bg-white shadow-2xs dark:border-slate-800/80 dark:bg-slate-900">
      <div class="border-b border-slate-100/70 px-5 py-3.5 dark:border-slate-800/60">
        <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Ringkasan Arsitektur</h2>
        <p class="text-[11px] text-slate-500 dark:text-slate-400">Pemisahan fungsional antara antarmuka pengguna dan engine machine learning.</p>
      </div>
      <div class="p-5">
        <p class="text-xs leading-relaxed text-slate-600 dark:text-slate-300">
          Sistem ini memisahkan Laravel sebagai aplikasi web utama dan FastAPI sebagai service klasifikasi.
          Laravel menangani antarmuka pengguna, formulir klasifikasi, visualisasi dashboard, dan penyimpanan riwayat. 
          FastAPI memproses machine learning secara independen menggunakan model Random Forest yang dioptimasi PSO.
        </p>

        <div class="mt-5 grid gap-3.5 md:grid-cols-2">
          @foreach ([
            ['icon' => 'layout-dashboard', 'title' => 'Laravel Frontend', 'body' => 'Dashboard analitik, formulir klasifikasi interaktif, manajemen user, dan riwayat.'],
            ['icon' => 'cpu', 'title' => 'FastAPI Backend', 'body' => 'Endpoint inferensi REST API dengan validasi skema data dan kalkulasi probabilitas cepat.'],
            ['icon' => 'brain-circuit', 'title' => 'ML Layer (PSO + RF)', 'body' => 'Memuat model teroptimasi PSO untuk menghasilkan klasifikasi akurat dan faktor pengaruh.'],
            ['icon' => 'database', 'title' => 'Database Storage', 'body' => 'Menyimpan riwayat klasifikasi, metrik agregat, dan preferensi pengguna aplikasi.'],
          ] as $card)
            <div class="rounded-xl border border-slate-100/80 bg-slate-50/50 p-4 dark:border-slate-800/60 dark:bg-slate-800/30">
              <div class="flex items-center gap-2">
                <span class="grid h-6 w-6 place-items-center rounded-md bg-indigo-50 text-indigo-600 dark:bg-indigo-950/60 dark:text-indigo-400">
                  <i data-lucide="{{ $card['icon'] }}" class="h-3.5 w-3.5"></i>
                </span>
                <h3 class="text-xs font-bold text-slate-800 dark:text-slate-200">{{ $card['title'] }}</h3>
              </div>
              <p class="mt-2 text-xs leading-relaxed text-slate-500 dark:text-slate-400">{{ $card['body'] }}</p>
            </div>
          @endforeach
        </div>
      </div>
    </section>

    <section class="rounded-xl border border-slate-100/90 bg-white shadow-2xs dark:border-slate-800/80 dark:bg-slate-900">
      <div class="border-b border-slate-100/70 px-5 py-3.5 dark:border-slate-800/60">
        <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Alur Kerja Sistem</h2>
        <p class="text-[11px] text-slate-500 dark:text-slate-400">Langkah pemrosesan dari input ke hasil prediksi.</p>
      </div>
      <div class="space-y-0 p-5">
        @foreach ([
          'User memasukkan parameter profil layanan pelanggan di Laravel.',
          'Laravel memvalidasi payload dan mengirim HTTP request ke FastAPI.',
          'FastAPI memproses data menggunakan model Random Forest + PSO.',
          'Hasil probabilitas dan faktor dominan ditampilkan dan disimpan ke database.',
        ] as $index => $step)
          <div class="flex gap-3 border-b border-slate-100/80 py-3.5 first:pt-0 last:border-b-0 dark:border-slate-800/60">
            <span class="grid h-6 w-6 shrink-0 place-items-center rounded-full bg-indigo-50 text-xs font-bold text-indigo-600 dark:bg-indigo-950/60 dark:text-indigo-400">{{ $index + 1 }}</span>
            <p class="text-xs leading-relaxed text-slate-600 dark:text-slate-300">{{ $step }}</p>
          </div>
        @endforeach
      </div>
    </section>
  </div>
@endsection
