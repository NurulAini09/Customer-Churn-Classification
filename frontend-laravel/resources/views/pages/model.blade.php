@extends('layouts.app')

@section('title', 'Informasi Model - ChurnPredict AI')
@section('page-title', 'Informasi Model')

@section('content')
  <div class="mb-5 flex flex-col gap-3 border-b border-transparent pb-1 sm:flex-row sm:items-center sm:justify-between">
    <div>
      <span class="text-[10px] font-bold tracking-wider uppercase text-blue-600 dark:text-blue-400">— ARSITEKTUR MACHINE LEARNING</span>
      <h1 class="text-2xl font-semibold tracking-tight text-slate-700 dark:text-white mt-0.5">Informasi Model AI</h1>
      <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">Spesifikasi arsitektur Random Forest Classifier dengan optimasi PSO.</p>
    </div>
    <a href="http://127.0.0.1:5001/docs" target="_blank" class="inline-flex h-9 items-center gap-2 rounded-xl border border-transparent bg-white px-3.5 text-xs font-semibold text-slate-700 shadow-xs transition hover:bg-slate-50 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800">
      <i data-lucide="external-link" class="h-3.5 w-3.5 text-blue-600 dark:text-blue-400"></i>
      Dokumentasi API
    </a>
  </div>

  <div class="grid gap-6 xl:grid-cols-[1fr_380px]">
    <section class="rounded-2xl border border-transparent bg-white shadow-xs dark:bg-slate-900">
      <div class="border-b border-transparent px-6 py-4">
        <h2 class="text-sm font-bold text-slate-900 dark:text-white">Spesifikasi Model</h2>
        <p class="text-[11px] text-slate-400">Informasi inti machine learning dan integrasi service.</p>
      </div>
      <dl class="divide-y divide-slate-100/50 dark:divide-slate-800/40">
        @foreach ([
          'Model Utama' => 'Random Forest Classifier',
          'Metode Optimasi' => 'Particle Swarm Optimization (PSO)',
          'Fitur Input' => '18 variabel layanan dan profil pelanggan',
          'Output Prediksi' => 'Status churn, probabilitas, level risiko, dan faktor dominan',
          'Backend Engine' => 'FastAPI + scikit-learn + joblib',
          'Integrasi Layanan' => 'Laravel HTTP Client ke endpoint FastAPI /predict',
        ] as $label => $value)
          <div class="grid gap-2 px-6 py-3.5 sm:grid-cols-[170px_1fr]">
            <dt class="text-xs font-semibold text-slate-400">{{ $label }}</dt>
            <dd class="text-xs font-semibold text-slate-800 dark:text-slate-200">{{ $value }}</dd>
          </div>
        @endforeach
      </dl>
    </section>

    <section class="rounded-2xl border border-transparent bg-white shadow-xs dark:bg-slate-900">
      <div class="border-b border-transparent px-6 py-4">
        <h2 class="text-sm font-bold text-slate-900 dark:text-white">Kontrak API (FastAPI)</h2>
        <p class="text-[11px] text-slate-400">Contoh format payload request dan response JSON.</p>
      </div>
      <div class="space-y-3.5 p-6 pt-2">
        <div class="rounded-xl border border-transparent bg-slate-50/70 p-4 text-[11px] font-mono leading-5 text-slate-700 dark:bg-slate-950/40 dark:text-slate-300">
          <span class="font-bold text-blue-600 dark:text-blue-400">POST /predict</span>
<pre class="mt-1.5 overflow-x-auto"><code>{
  "account_length": 128,
  "area_code": 415,
  "customer_service_calls": 2
}</code></pre>
        </div>
        <div class="rounded-xl border border-transparent bg-slate-50/70 p-4 text-[11px] font-mono leading-5 text-slate-700 dark:bg-slate-950/40 dark:text-slate-300">
          <span class="font-bold text-emerald-600 dark:text-emerald-400">RESPONSE (200 OK)</span>
<pre class="mt-1.5 overflow-x-auto"><code>{
  "success": true,
  "data": {
    "result": "Tidak Churn",
    "probability": 23.4,
    "risiko": "Rendah"
  }
}</code></pre>
        </div>
      </div>
    </section>
  </div>
@endsection
