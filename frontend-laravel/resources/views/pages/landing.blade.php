@extends('layouts.guest')

@section('title', 'ChurnPredict AI - Prediksi Churn Pelanggan')

@section('content')
  <div class="min-h-screen overflow-hidden bg-[#F8FAFC]">
    <div class="pointer-events-none absolute inset-x-0 top-0 h-[540px] bg-[radial-gradient(circle_at_72%_12%,rgba(219,90,141,0.16),transparent_28%),radial-gradient(circle_at_22%_20%,rgba(99,102,241,0.14),transparent_30%)]"></div>

    <header class="relative mx-auto flex max-w-6xl items-center justify-between px-5 py-5 sm:px-8">
      <a href="{{ route('landing') }}" class="flex items-center gap-2.5" aria-label="ChurnPredict AI">
        <span class="grid h-9 w-9 place-items-center rounded-xl bg-gradient-to-br from-[#B94473] to-[#E16B9A] text-white shadow-[0_8px_20px_rgba(219,90,141,0.25)]"><i data-lucide="activity" class="h-4 w-4"></i></span>
        <span><span class="block text-sm font-extrabold tracking-tight text-[#102A43]">ChurnPredict<span class="text-[#DB5A8D]">AI</span></span><span class="block text-[9px] font-medium text-slate-400">Customer Intelligence</span></span>
      </a>
      <nav class="flex items-center gap-2 sm:gap-3" aria-label="Navigasi utama">
        <a href="#fitur" class="hidden px-2 py-1 text-xs font-medium text-slate-500 transition hover:text-[#DB5A8D] sm:inline">Fitur</a>
        <a href="#cara-kerja" class="hidden px-2 py-1 text-xs font-medium text-slate-500 transition hover:text-[#DB5A8D] sm:inline">Cara kerja</a>
        <a href="{{ route('login') }}" class="rounded-lg px-3 py-2 text-xs font-semibold text-[#475569] transition hover:bg-white hover:text-[#DB5A8D]">Masuk</a>
        <a href="{{ route('register') }}" class="rounded-lg bg-[#DB5A8D] px-3.5 py-2 text-xs font-semibold text-white shadow-[0_6px_14px_rgba(219,90,141,0.22)] transition hover:bg-[#C84A7B]">Mulai sekarang</a>
      </nav>
    </header>

    <main class="relative">
      <section class="mx-auto grid max-w-6xl items-center gap-12 px-5 pb-20 pt-12 sm:px-8 lg:grid-cols-[1fr_0.88fr] lg:pb-28 lg:pt-20">
        <div class="max-w-xl">
          <div class="inline-flex items-center gap-2 rounded-full border border-[#F6C7DA] bg-white/80 px-3 py-1.5 text-[11px] font-semibold text-[#C84A7B] shadow-sm"><span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Sistem prediksi churn siap digunakan</div>
          <h1 class="mt-5 text-4xl font-extrabold leading-[1.12] tracking-[-0.045em] text-[#102A43] sm:text-5xl">Kenali risiko churn <span class="text-[#DB5A8D]">sebelum</span> pelanggan pergi.</h1>
          <p class="mt-5 max-w-lg text-sm leading-7 text-[#64748B]">ChurnPredict AI membantu Anda mengolah data layanan pelanggan menjadi insight risiko yang jelas, cepat, dan mudah ditindaklanjuti.</p>
          <div class="mt-7 flex flex-wrap items-center gap-3"><a href="{{ route('register') }}" class="inline-flex h-11 items-center gap-2 rounded-xl bg-[#DB5A8D] px-5 text-sm font-semibold text-white shadow-[0_12px_24px_rgba(219,90,141,0.26)] transition hover:-translate-y-0.5 hover:bg-[#C84A7B]"><i data-lucide="sparkles" class="h-4 w-4"></i> Coba klasifikasi sekarang</a><a href="#cara-kerja" class="inline-flex h-11 items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 text-sm font-semibold text-[#475569] transition hover:border-[#F2AFCB] hover:text-[#DB5A8D]"><i data-lucide="circle-play" class="h-4 w-4"></i> Pelajari caranya</a></div>
          <div class="mt-8 flex items-center gap-5 text-[11px] font-medium text-slate-500"><span class="flex items-center gap-1.5"><i data-lucide="shield-check" class="h-3.5 w-3.5 text-emerald-500"></i> Analisis terstruktur</span><span class="flex items-center gap-1.5"><i data-lucide="zap" class="h-3.5 w-3.5 text-amber-500"></i> Hasil cepat</span></div>
        </div>

        <div class="relative mx-auto w-full max-w-md">
          <div class="absolute -inset-5 rounded-[2rem] bg-gradient-to-br from-[#FBE1EB] via-transparent to-indigo-100 blur-2xl"></div>
          <div class="relative overflow-hidden rounded-2xl border border-white bg-white p-4 shadow-[0_24px_65px_rgba(15,23,42,0.13)]">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3"><div class="flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-rose-400"></span><span class="h-2.5 w-2.5 rounded-full bg-amber-400"></span><span class="h-2.5 w-2.5 rounded-full bg-emerald-400"></span></div><span class="rounded bg-slate-50 px-2 py-1 text-[9px] font-semibold text-slate-400">DASHBOARD</span></div>
            <div class="mt-4 grid grid-cols-3 gap-2"><div class="rounded-lg bg-[#F5F3FF] p-2.5"><i data-lucide="files" class="h-3.5 w-3.5 text-violet-600"></i><p class="mt-2 text-lg font-bold text-slate-800">—</p><p class="text-[8px] font-medium text-slate-400">Klasifikasi</p></div><div class="rounded-lg bg-[#EFF6FF] p-2.5"><i data-lucide="gauge" class="h-3.5 w-3.5 text-blue-600"></i><p class="mt-2 text-lg font-bold text-slate-800">AI</p><p class="text-[8px] font-medium text-slate-400">Analisis</p></div><div class="rounded-lg bg-[#FFF1F6] p-2.5"><i data-lucide="siren" class="h-3.5 w-3.5 text-[#DB5A8D]"></i><p class="mt-2 text-lg font-bold text-slate-800">3</p><p class="text-[8px] font-medium text-slate-400">Level risiko</p></div></div>
            <div class="mt-3 rounded-xl border border-slate-100 p-3"><div class="flex items-center justify-between"><p class="text-[10px] font-bold text-slate-600">Distribusi risiko</p><span class="text-[9px] text-emerald-600">Terbarui</span></div><div class="mt-4 flex h-16 items-end gap-2">@foreach ([35, 52, 30, 67, 42, 88, 54] as $height)<span class="flex-1 rounded-t bg-gradient-to-t from-[#DB5A8D] to-[#F3AAC5]" style="height: {{ $height }}%"></span>@endforeach</div><div class="mt-2 flex justify-between text-[8px] text-slate-400"><span>Risiko rendah</span><span>Risiko tinggi</span></div></div>
            <div class="mt-3 flex items-center gap-3 rounded-xl bg-[#FFF5F8] p-3"><span class="grid h-8 w-8 place-items-center rounded-lg bg-white text-[#DB5A8D]"><i data-lucide="brain-circuit" class="h-4 w-4"></i></span><div><p class="text-[10px] font-bold text-[#6F3D58]">Model siap menganalisis</p><p class="mt-0.5 text-[9px] text-slate-500">Random Forest + PSO</p></div><span class="ml-auto h-2 w-2 rounded-full bg-emerald-500"></span></div>
          </div>
        </div>
      </section>

      <section id="fitur" class="border-y border-slate-200/70 bg-white/70 py-16"><div class="mx-auto max-w-6xl px-5 sm:px-8"><div class="max-w-lg"><p class="text-[11px] font-bold uppercase tracking-[0.15em] text-[#DB5A8D]">Satu platform, insight yang jelas</p><h2 class="mt-2 text-2xl font-bold tracking-tight text-[#102A43]">Dari data mentah ke keputusan yang lebih cepat.</h2></div><div class="mt-8 grid gap-4 md:grid-cols-3"><article class="rounded-xl border border-slate-100 bg-white p-5"><span class="grid h-9 w-9 place-items-center rounded-lg bg-[#FFF0F6] text-[#DB5A8D]"><i data-lucide="activity" class="h-4 w-4"></i></span><h3 class="mt-4 text-sm font-bold text-[#102A43]">Prediksi terukur</h3><p class="mt-2 text-xs leading-5 text-slate-500">Klasifikasikan risiko churn dari atribut layanan pelanggan yang relevan.</p></article><article class="rounded-xl border border-slate-100 bg-white p-5"><span class="grid h-9 w-9 place-items-center rounded-lg bg-blue-50 text-blue-600"><i data-lucide="layout-dashboard" class="h-4 w-4"></i></span><h3 class="mt-4 text-sm font-bold text-[#102A43]">Dashboard ringkas</h3><p class="mt-2 text-xs leading-5 text-slate-500">Pantau riwayat, probabilitas, dan tingkat risiko dalam satu tempat.</p></article><article class="rounded-xl border border-slate-100 bg-white p-5"><span class="grid h-9 w-9 place-items-center rounded-lg bg-emerald-50 text-emerald-600"><i data-lucide="history" class="h-4 w-4"></i></span><h3 class="mt-4 text-sm font-bold text-[#102A43]">Riwayat terpusat</h3><p class="mt-2 text-xs leading-5 text-slate-500">Tinjau kembali hasil klasifikasi untuk mendukung tindak lanjut tim.</p></article></div></div></section>

      <section id="cara-kerja" class="mx-auto max-w-6xl px-5 py-16 sm:px-8"><div class="grid gap-10 lg:grid-cols-[0.8fr_1.2fr]"><div><p class="text-[11px] font-bold uppercase tracking-[0.15em] text-[#DB5A8D]">Cara kerja</p><h2 class="mt-2 text-2xl font-bold tracking-tight text-[#102A43]">Mulai dalam tiga langkah sederhana.</h2><p class="mt-3 text-sm leading-6 text-slate-500">Tidak perlu mengubah model Anda. Seluruh proses klasifikasi tetap menggunakan layanan prediction yang sudah terintegrasi.</p></div><ol class="grid gap-3 sm:grid-cols-3">@foreach ([['01', 'Masuk ke sistem', 'Gunakan akun Anda untuk membuka dashboard.'], ['02', 'Isi data pelanggan', 'Lengkapi data layanan melalui form klasifikasi.'], ['03', 'Tinjau hasil', 'Dapatkan probabilitas dan tingkat risiko churn.']] as $step)<li class="rounded-xl border border-slate-200 bg-white p-4"><span class="text-xs font-extrabold text-[#DB5A8D]">{{ $step[0] }}</span><h3 class="mt-5 text-sm font-bold text-[#102A43]">{{ $step[1] }}</h3><p class="mt-2 text-[11px] leading-5 text-slate-500">{{ $step[2] }}</p></li>@endforeach</ol></div></section>

      <section class="mx-auto max-w-6xl px-5 pb-16 sm:px-8"><div class="flex flex-col items-start justify-between gap-5 rounded-2xl bg-[#102A43] px-6 py-8 sm:flex-row sm:items-center sm:px-9"><div><p class="text-xl font-bold tracking-tight text-white">Siap memahami risiko churn pelanggan?</p><p class="mt-1 text-sm text-slate-300">Mulai dari satu klasifikasi dan temukan insightnya.</p></div><a href="{{ route('register') }}" class="inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2.5 text-xs font-bold text-[#102A43] transition hover:bg-[#FFF0F6]"><i data-lucide="arrow-right" class="h-3.5 w-3.5"></i> Buat akun</a></div></section>
    </main>

    <footer class="border-t border-slate-200 bg-white py-5"><div class="mx-auto flex max-w-6xl flex-col gap-2 px-5 text-[10px] text-slate-400 sm:flex-row sm:items-center sm:justify-between sm:px-8"><span>© {{ now()->year }} ChurnPredict AI</span><span>Customer churn classification system</span></div></footer>
  </div>
@endsection
