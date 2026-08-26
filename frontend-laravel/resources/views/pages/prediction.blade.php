@extends('layouts.app')

@section('title', 'Klasifikasi Churn - ChurnPredict AI')
@section('page-title', 'Klasifikasi Churn')

@section('content')
  @php
    $hasResult = !empty($resultData);
    $isChurn = $hasResult && ($resultData['result'] === 'Churn');
    $probability = $hasResult ? (float) $resultData['probability'] : 0;
    $riskLevel = $hasResult ? ($resultData['risiko'] ?? ($probability >= 75 ? 'Tinggi' : ($probability >= 50 ? 'Sedang' : 'Rendah'))) : null;
  @endphp

  <div
    x-data="churnPredictionForm({
      initialValues: {{ json_encode($formValues) }},
      hasResult: {{ $hasResult ? 'true' : 'false' }}
    })"
    class="space-y-6 pb-12"
  >
    <!-- Top Action Header & Sample Presets -->
    <div class="flex flex-col gap-4 border-b border-transparent pb-1 sm:flex-row sm:items-center sm:justify-between">
      <div>
        <span class="text-[10px] font-bold tracking-wider uppercase text-blue-600 dark:text-blue-400">— MODUL PREDIKSI & RETENSI</span>
        <div class="flex items-center gap-2.5 mt-0.5">
          <h1 class="text-2xl font-semibold tracking-tight text-slate-700 dark:text-white">Klasifikasi Churn Pelanggan</h1>
          <span class="inline-flex items-center gap-1 rounded-full bg-blue-50 px-2.5 py-0.5 text-[10px] font-semibold text-blue-600 dark:bg-blue-950/60 dark:text-blue-300">
            <i data-lucide="sparkles" class="h-3 w-3"></i>
            Random Forest + PSO
          </span>
        </div>
        <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
          Kelola variabel perilaku dan parameter penggunaan layanan pelanggan untuk memprediksi tingkat risiko churn.
        </p>
      </div>

      <!-- Quick Presets & Actions -->
      <div class="flex flex-wrap items-center gap-2">
        <div class="flex items-center gap-1 rounded-xl border border-transparent bg-white p-1 shadow-xs dark:bg-slate-900">
          <span class="px-2.5 text-[11px] font-semibold text-slate-400">Sampel:</span>
          <button
            type="button"
            x-on:click="loadPreset('churn')"
            class="inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1 text-xs font-medium text-rose-600 transition hover:bg-rose-50 dark:text-rose-400 dark:hover:bg-rose-950/40"
            title="Muat data sampel pelanggan risiko tinggi churn"
          >
            <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span>
            Risiko Tinggi
          </button>
          <button
            type="button"
            x-on:click="loadPreset('loyal')"
            class="inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1 text-xs font-medium text-emerald-600 transition hover:bg-emerald-50 dark:text-emerald-400 dark:hover:bg-emerald-950/40"
            title="Muat data sampel pelanggan setia / loyal"
          >
            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
            Loyal / Aman
          </button>
          <button
            type="button"
            x-on:click="loadPreset('moderate')"
            class="inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1 text-xs font-medium text-amber-600 transition hover:bg-amber-50 dark:text-amber-400 dark:hover:bg-amber-950/40"
            title="Muat data sampel pelanggan risiko moderat"
          >
            <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
            Moderat
          </button>
        </div>

        <a
          href="{{ route('prediction.reset') }}"
          class="inline-flex h-9 items-center gap-1.5 rounded-xl border border-transparent bg-white px-3.5 text-xs font-medium text-slate-600 shadow-xs transition hover:bg-slate-50 hover:text-slate-900 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800"
          title="Reset semua isian formulir"
        >
          <i data-lucide="rotate-ccw" class="h-3.5 w-3.5 text-slate-400"></i>
          Reset
        </a>
      </div>
    </div>

    <!-- Toast Notification for Presets -->
    <div
      x-show="toastMessage"
      x-transition:enter="transition ease-out duration-300"
      x-transition:enter-start="opacity-0 -translate-y-2"
      x-transition:enter-end="opacity-100 translate-y-0"
      x-transition:leave="transition ease-in duration-200"
      x-transition:leave-start="opacity-100 translate-y-0"
      x-transition:leave-end="opacity-0 -translate-y-2"
      class="flex items-center justify-between rounded-xl border border-indigo-100/90 bg-indigo-50/80 px-4 py-3 text-xs font-medium text-indigo-900 shadow-2xs dark:border-indigo-900/40 dark:bg-indigo-950/40 dark:text-indigo-200"
      style="display: none;"
    >
      <div class="flex items-center gap-2">
        <i data-lucide="check-circle" class="h-4 w-4 text-indigo-600 dark:text-indigo-400"></i>
        <span x-text="toastMessage"></span>
      </div>
      <button type="button" x-on:click="toastMessage = ''" class="rounded p-1 text-indigo-600 hover:bg-indigo-100/50 dark:text-indigo-400">
        <i data-lucide="x" class="h-3.5 w-3.5"></i>
      </button>
    </div>

    <!-- Error Alerts -->
    @if ($errorMessage)
      <div class="flex items-start gap-3 rounded-xl border border-red-100 bg-red-50/80 p-4 text-xs text-red-800 shadow-2xs dark:border-red-900/40 dark:bg-red-950/30 dark:text-red-300">
        <i data-lucide="alert-circle" class="mt-0.5 h-4 w-4 shrink-0 text-red-600 dark:text-red-400"></i>
        <div>
          <p class="font-semibold">Gagal Menjalankan Klasifikasi</p>
          <p class="mt-0.5 leading-relaxed">{{ $errorMessage }}</p>
        </div>
      </div>
    @endif

    @if ($errors->any())
      <div class="flex items-start gap-3 rounded-xl border border-amber-100 bg-amber-50/80 p-4 text-xs text-amber-900 shadow-2xs dark:border-amber-900/40 dark:bg-amber-950/30 dark:text-amber-300">
        <i data-lucide="alert-triangle" class="mt-0.5 h-4 w-4 shrink-0 text-amber-600 dark:text-amber-400"></i>
        <div>
          <p class="font-semibold">Periksa Kembali Input Formulir:</p>
          <ul class="mt-1 list-inside list-disc space-y-0.5">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      </div>
    @endif

    <!-- Main Grid: Left Form, Right Result -->
    <div class="grid gap-6 xl:grid-cols-[1fr_380px]">
      
      <!-- Left Column: Form Sections -->
      <div class="space-y-5">
        <form
          id="prediction-form"
          method="POST"
          action="{{ route('prediction.predict') }}"
          x-on:submit="loading = true"
          class="space-y-5"
        >
          @csrf

          <!-- Section 1: Profil & Akun Pelanggan -->
          <div class="rounded-2xl border border-transparent bg-white p-6 shadow-xs dark:bg-slate-900">
            <div class="mb-4 flex items-center justify-between border-b border-transparent pb-1">
              <div class="flex items-center gap-3">
                <span class="grid h-8 w-8 place-items-center rounded-xl bg-blue-50 text-blue-600 dark:bg-blue-950/60 dark:text-blue-400">
                  <i data-lucide="user" class="h-4 w-4"></i>
                </span>
                <div>
                  <h2 class="text-sm font-bold text-slate-900 dark:text-white">1. Profil & Akun Pelanggan</h2>
                  <p class="text-[11px] text-slate-400">Masa berlangganan dan wilayah kode area telepon</p>
                </div>
              </div>
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
              <!-- Account Length -->
              <div>
                <label for="account_length" class="mb-1.5 block text-xs font-semibold text-slate-700 dark:text-slate-300">
                  Masa Berlangganan (Account Length)
                </label>
                <div class="relative">
                  <input
                    id="account_length"
                    type="number"
                    name="account_length"
                    min="0"
                    step="1"
                    x-model="form.account_length"
                    required
                    placeholder="Contoh: 120"
                    class="h-10 w-full rounded-xl border border-slate-200/50 bg-slate-50/50 pl-3.5 pr-14 text-sm font-medium text-slate-900 outline-none transition focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 dark:border-slate-800 dark:bg-slate-900 dark:text-white"
                  >
                  <span class="pointer-events-none absolute inset-y-0 right-3.5 flex items-center text-xs font-medium text-slate-400">
                    hari
                  </span>
                </div>
                <p class="mt-1.5 text-[11px] text-slate-400">Jumlah hari pelanggan telah aktif menggunakan layanan.</p>
              </div>

              <!-- Area Code -->
              <div>
                <label class="mb-1.5 block text-xs font-semibold text-slate-700 dark:text-slate-300">
                  Kode Area (Area Code)
                </label>
                <input type="hidden" name="area_code" x-model="form.area_code">
                <div class="grid grid-cols-3 gap-2">
                  <button
                    type="button"
                    x-on:click="form.area_code = '408'"
                    class="flex h-10 items-center justify-center rounded-xl border text-xs font-semibold transition"
                    x-bind:class="form.area_code == '408' ? 'border-blue-600 bg-blue-50/80 text-blue-700 dark:bg-blue-950/60 dark:text-blue-300' : 'border-transparent bg-slate-100/70 text-slate-600 hover:bg-slate-200/70 dark:bg-slate-800 dark:text-slate-400'"
                  >
                    408
                  </button>
                  <button
                    type="button"
                    x-on:click="form.area_code = '415'"
                    class="flex h-10 items-center justify-center rounded-xl border text-xs font-semibold transition"
                    x-bind:class="form.area_code == '415' ? 'border-blue-600 bg-blue-50/80 text-blue-700 dark:bg-blue-950/60 dark:text-blue-300' : 'border-transparent bg-slate-100/70 text-slate-600 hover:bg-slate-200/70 dark:bg-slate-800 dark:text-slate-400'"
                  >
                    415
                  </button>
                  <button
                    type="button"
                    x-on:click="form.area_code = '510'"
                    class="flex h-10 items-center justify-center rounded-xl border text-xs font-semibold transition"
                    x-bind:class="form.area_code == '510' ? 'border-blue-600 bg-blue-50/80 text-blue-700 dark:bg-blue-950/60 dark:text-blue-300' : 'border-transparent bg-slate-100/70 text-slate-600 hover:bg-slate-200/70 dark:bg-slate-800 dark:text-slate-400'"
                  >
                    510
                  </button>
                </div>
                <p class="mt-1.5 text-[11px] text-slate-400">Pilih kode area registrasi (408, 415, 510).</p>
              </div>
            </div>
          </div>          <!-- Section 2: Fitur & Paket Layanan -->
          <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-xs dark:border-slate-800 dark:bg-slate-900">
            <div class="mb-4 flex items-center justify-between border-b border-slate-100 pb-3 dark:border-slate-800">
              <div class="flex items-center gap-3">
                <span class="grid h-9 w-9 place-items-center rounded-xl bg-blue-50 text-blue-600 dark:bg-blue-950/60 dark:text-blue-400">
                  <i data-lucide="zap" class="h-4.5 w-4.5"></i>
                </span>
                <div>
                  <h2 class="text-sm font-bold text-slate-900 dark:text-white">2. Fitur & Paket Layanan</h2>
                  <p class="text-[11px] text-slate-400">Status add-on paket internasional dan fitur pesan voicemail</p>
                </div>
              </div>
            </div>

            <div class="grid gap-5 md:grid-cols-3">
              <!-- International Plan Toggle -->
              <div>
                <label class="mb-1.5 block text-xs font-semibold text-slate-700 dark:text-slate-300">
                  Paket Internasional
                </label>
                <input type="hidden" name="international_plan" x-model="form.international_plan">
                <div class="grid grid-cols-2 gap-1 rounded-xl bg-slate-100/90 p-1 dark:bg-slate-800">
                  <button
                    type="button"
                    x-on:click="form.international_plan = '0'"
                    class="inline-flex h-8.5 items-center justify-center gap-1.5 rounded-lg text-xs font-semibold transition"
                    x-bind:class="form.international_plan === '0' ? 'bg-white text-slate-800 shadow-2xs dark:bg-slate-900 dark:text-white' : 'text-slate-500 hover:text-slate-800 dark:text-slate-400'"
                  >
                    <span class="h-1.5 w-1.5 rounded-full" x-bind:class="form.international_plan === '0' ? 'bg-slate-400' : 'bg-transparent'"></span>
                    Tidak Aktif
                  </button>
                  <button
                    type="button"
                    x-on:click="form.international_plan = '1'"
                    class="inline-flex h-8.5 items-center justify-center gap-1.5 rounded-lg text-xs font-semibold transition"
                    x-bind:class="form.international_plan === '1' ? 'bg-blue-600 text-white shadow-2xs' : 'text-slate-500 hover:text-slate-800 dark:text-slate-400'"
                  >
                    <span class="h-1.5 w-1.5 rounded-full" x-bind:class="form.international_plan === '1' ? 'bg-emerald-300' : 'bg-transparent'"></span>
                    Aktif (Yes)
                  </button>
                </div>
                <p class="mt-1.5 text-[11px] text-slate-400">Status add-on paket panggilan internasional.</p>
              </div>

              <!-- Voice Mail Plan Toggle -->
              <div>
                <label class="mb-1.5 block text-xs font-semibold text-slate-700 dark:text-slate-300">
                  Paket Voicemail
                </label>
                <input type="hidden" name="voice_mail_plan" x-model="form.voice_mail_plan">
                <div class="grid grid-cols-2 gap-1 rounded-xl bg-slate-100/90 p-1 dark:bg-slate-800">
                  <button
                    type="button"
                    x-on:click="form.voice_mail_plan = '0'; if (form.number_vmail_messages > 0) form.number_vmail_messages = 0;"
                    class="inline-flex h-8.5 items-center justify-center gap-1.5 rounded-lg text-xs font-semibold transition"
                    x-bind:class="form.voice_mail_plan === '0' ? 'bg-white text-slate-800 shadow-2xs dark:bg-slate-900 dark:text-white' : 'text-slate-500 hover:text-slate-800 dark:text-slate-400'"
                  >
                    <span class="h-1.5 w-1.5 rounded-full" x-bind:class="form.voice_mail_plan === '0' ? 'bg-slate-400' : 'bg-transparent'"></span>
                    Tidak Aktif
                  </button>
                  <button
                    type="button"
                    x-on:click="form.voice_mail_plan = '1'; if (!form.number_vmail_messages || form.number_vmail_messages == 0) form.number_vmail_messages = 25;"
                    class="inline-flex h-8.5 items-center justify-center gap-1.5 rounded-lg text-xs font-semibold transition"
                    x-bind:class="form.voice_mail_plan === '1' ? 'bg-blue-600 text-white shadow-2xs' : 'text-slate-500 hover:text-slate-800 dark:text-slate-400'"
                  >
                    <span class="h-1.5 w-1.5 rounded-full" x-bind:class="form.voice_mail_plan === '1' ? 'bg-emerald-300' : 'bg-transparent'"></span>
                    Aktif (Yes)
                  </button>
                </div>
                <p class="mt-1.5 text-[11px] text-slate-400">Fitur kotak pesan suara pelanggan.</p>
              </div>

              <!-- Number Vmail Messages -->
              <div>
                <label for="number_vmail_messages" class="mb-1.5 block text-xs font-semibold text-slate-700 dark:text-slate-300">
                  Jumlah Pesan Voicemail
                </label>
                <div class="relative">
                  <input
                    id="number_vmail_messages"
                    type="number"
                    name="number_vmail_messages"
                    min="0"
                    step="1"
                    x-model="form.number_vmail_messages"
                    required
                    placeholder="0"
                    class="h-10 w-full rounded-xl border border-slate-200/80 bg-slate-50/50 pl-3.5 pr-14 text-sm font-semibold text-slate-900 outline-none transition focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                  >
                  <span class="pointer-events-none absolute inset-y-0 right-3.5 flex items-center text-xs font-medium text-slate-400">
                    pesan
                  </span>
                </div>
                <p class="mt-1.5 text-[11px] text-slate-400">Jumlah pesan tersimpan (0 jika paket non-aktif).</p>
              </div>
            </div>
          </div>

          <!-- Section 3: Aktivitas Panggilan Lokal (Usage) as Modern Clean Table (Image 2 Style) -->
          <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-xs dark:border-slate-800 dark:bg-slate-900">
            <!-- Table Header Bar -->
            <div class="flex flex-col justify-between gap-3 border-b border-slate-200/80 p-5 sm:flex-row sm:items-center dark:border-slate-800">
              <div class="flex items-center gap-3">
                <span class="grid h-9 w-9 place-items-center rounded-xl bg-blue-50 text-blue-600 dark:bg-blue-950/60 dark:text-blue-400">
                  <i data-lucide="phone-call" class="h-4.5 w-4.5"></i>
                </span>
                <div>
                  <h2 class="text-sm font-bold text-slate-900 dark:text-white">3. Aktivitas Panggilan Lokal (Usage)</h2>
                  <p class="text-[11px] text-slate-400">Durasi menit, jumlah panggilan, dan total estimasi biaya tagihan harian</p>
                </div>
              </div>

              <!-- Live Summary Chip -->
              <div class="inline-flex items-center gap-2 rounded-xl border border-slate-200/80 bg-slate-50/90 px-3.5 py-1.5 text-xs font-medium text-slate-600 dark:border-slate-700/80 dark:bg-slate-800/80 dark:text-slate-300">
                <span class="flex items-center gap-1.5">
                  <i data-lucide="clock" class="h-3.5 w-3.5 text-amber-500"></i>
                  <strong class="font-bold text-slate-900 dark:text-white" x-text="totalLocalMinutes()">0</strong> min
                </span>
                <span class="text-slate-300 dark:text-slate-600">•</span>
                <span class="flex items-center gap-1.5">
                  <i data-lucide="phone" class="h-3.5 w-3.5 text-indigo-500"></i>
                  <strong class="font-bold text-slate-900 dark:text-white" x-text="totalLocalCalls()">0</strong> call
                </span>
                <span class="text-slate-300 dark:text-slate-600">•</span>
                <span class="flex items-center gap-1 text-blue-600 dark:text-blue-400 font-bold">
                  <span>$</span><span x-text="totalLocalCharges()">0.00</span>
                </span>
              </div>
            </div>

            <!-- Structured Table Content (Image 2 Aesthetic) -->
            <div class="overflow-x-auto">
              <table class="min-w-full border-collapse text-[13px]">
                <thead>
                  <tr class="border-b border-slate-200/80 bg-slate-50/80 text-left text-[11px] font-bold uppercase tracking-wider text-slate-600 dark:border-slate-800 dark:bg-slate-800/50 dark:text-slate-400">
                    <th class="px-5 py-3.5">Sesi / Waktu Panggilan</th>
                    <th class="px-5 py-3.5">Total Durasi (Menit)</th>
                    <th class="px-5 py-3.5">Jumlah Panggilan</th>
                    <th class="px-5 py-3.5">Estimasi Biaya ($)</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                  <!-- Row 1: Daytime (Siang) -->
                  <tr class="transition hover:bg-slate-50/60 dark:hover:bg-slate-800/40">
                    <td class="whitespace-nowrap px-5 py-4">
                      <div class="flex items-center gap-3">
                        <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-amber-50 text-amber-600 dark:bg-amber-950/60 dark:text-amber-400">
                          <i data-lucide="sun" class="h-4.5 w-4.5"></i>
                        </span>
                        <div>
                          <div class="font-bold text-slate-900 dark:text-white">Panggilan Siang (Daytime)</div>
                          <div class="text-[11px] font-medium text-slate-400">06:00 - 17:00</div>
                        </div>
                      </div>
                    </td>
                    <td class="px-5 py-4">
                      <div class="relative min-w-[140px]">
                        <input
                          type="number"
                          name="total_day_minutes"
                          min="0"
                          step="any"
                          x-model="form.total_day_minutes"
                          required
                          placeholder="180.0"
                          class="h-9 w-full rounded-xl border border-slate-200/80 bg-white pl-3.5 pr-12 text-xs font-semibold text-slate-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                        >
                        <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-[11px] font-medium text-slate-400">min</span>
                      </div>
                    </td>
                    <td class="px-5 py-4">
                      <div class="relative min-w-[130px]">
                        <input
                          type="number"
                          name="total_day_calls"
                          min="0"
                          step="1"
                          x-model="form.total_day_calls"
                          required
                          placeholder="100"
                          class="h-9 w-full rounded-xl border border-slate-200/80 bg-white pl-3.5 pr-12 text-xs font-semibold text-slate-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                        >
                        <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-[11px] font-medium text-slate-400">call</span>
                      </div>
                    </td>
                    <td class="px-5 py-4">
                      <div class="relative min-w-[130px]">
                        <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-xs font-bold text-slate-400">$</span>
                        <input
                          type="number"
                          name="total_day_charge"
                          min="0"
                          step="any"
                          x-model="form.total_day_charge"
                          required
                          placeholder="30.60"
                          class="h-9 w-full rounded-xl border border-slate-200/80 bg-white pl-7 pr-3 text-xs font-semibold text-slate-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                        >
                      </div>
                    </td>
                  </tr>

                  <!-- Row 2: Evening (Sore) -->
                  <tr class="transition hover:bg-slate-50/60 dark:hover:bg-slate-800/40">
                    <td class="whitespace-nowrap px-5 py-4">
                      <div class="flex items-center gap-3">
                        <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-indigo-50 text-indigo-600 dark:bg-indigo-950/60 dark:text-indigo-400">
                          <i data-lucide="sunset" class="h-4.5 w-4.5"></i>
                        </span>
                        <div>
                          <div class="font-bold text-slate-900 dark:text-white">Panggilan Sore (Evening)</div>
                          <div class="text-[11px] font-medium text-slate-400">17:00 - 23:00</div>
                        </div>
                      </div>
                    </td>
                    <td class="px-5 py-4">
                      <div class="relative min-w-[140px]">
                        <input
                          type="number"
                          name="total_eve_minutes"
                          min="0"
                          step="any"
                          x-model="form.total_eve_minutes"
                          required
                          placeholder="200.0"
                          class="h-9 w-full rounded-xl border border-slate-200/80 bg-white pl-3.5 pr-12 text-xs font-semibold text-slate-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                        >
                        <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-[11px] font-medium text-slate-400">min</span>
                      </div>
                    </td>
                    <td class="px-5 py-4">
                      <div class="relative min-w-[130px]">
                        <input
                          type="number"
                          name="total_eve_calls"
                          min="0"
                          step="1"
                          x-model="form.total_eve_calls"
                          required
                          placeholder="100"
                          class="h-9 w-full rounded-xl border border-slate-200/80 bg-white pl-3.5 pr-12 text-xs font-semibold text-slate-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                        >
                        <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-[11px] font-medium text-slate-400">call</span>
                      </div>
                    </td>
                    <td class="px-5 py-4">
                      <div class="relative min-w-[130px]">
                        <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-xs font-bold text-slate-400">$</span>
                        <input
                          type="number"
                          name="total_eve_charge"
                          min="0"
                          step="any"
                          x-model="form.total_eve_charge"
                          required
                          placeholder="17.00"
                          class="h-9 w-full rounded-xl border border-slate-200/80 bg-white pl-7 pr-3 text-xs font-semibold text-slate-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                        >
                      </div>
                    </td>
                  </tr>

                  <!-- Row 3: Night (Malam) -->
                  <tr class="transition hover:bg-slate-50/60 dark:hover:bg-slate-800/40">
                    <td class="whitespace-nowrap px-5 py-4">
                      <div class="flex items-center gap-3">
                        <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-purple-50 text-purple-600 dark:bg-purple-950/60 dark:text-purple-400">
                          <i data-lucide="moon" class="h-4.5 w-4.5"></i>
                        </span>
                        <div>
                          <div class="font-bold text-slate-900 dark:text-white">Panggilan Malam (Night)</div>
                          <div class="text-[11px] font-medium text-slate-400">23:00 - 06:00</div>
                        </div>
                      </div>
                    </td>
                    <td class="px-5 py-4">
                      <div class="relative min-w-[140px]">
                        <input
                          type="number"
                          name="total_night_minutes"
                          min="0"
                          step="any"
                          x-model="form.total_night_minutes"
                          required
                          placeholder="200.0"
                          class="h-9 w-full rounded-xl border border-slate-200/80 bg-white pl-3.5 pr-12 text-xs font-semibold text-slate-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                        >
                        <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-[11px] font-medium text-slate-400">min</span>
                      </div>
                    </td>
                    <td class="px-5 py-4">
                      <div class="relative min-w-[130px]">
                        <input
                          type="number"
                          name="total_night_calls"
                          min="0"
                          step="1"
                          x-model="form.total_night_calls"
                          required
                          placeholder="100"
                          class="h-9 w-full rounded-xl border border-slate-200/80 bg-white pl-3.5 pr-12 text-xs font-semibold text-slate-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                        >
                        <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-[11px] font-medium text-slate-400">call</span>
                      </div>
                    </td>
                    <td class="px-5 py-4">
                      <div class="relative min-w-[130px]">
                        <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-xs font-bold text-slate-400">$</span>
                        <input
                          type="number"
                          name="total_night_charge"
                          min="0"
                          step="any"
                          x-model="form.total_night_charge"
                          required
                          placeholder="9.00"
                          class="h-9 w-full rounded-xl border border-slate-200/80 bg-white pl-7 pr-3 text-xs font-semibold text-slate-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                        >
                      </div>
                    </td>
                  </tr>
                </tbody>
                <!-- Table Footer Summary -->
                <tfoot>
                  <tr class="border-t border-slate-200/80 bg-slate-50/70 text-xs font-semibold dark:border-slate-800 dark:bg-slate-800/40">
                    <td class="px-5 py-3 font-bold text-slate-700 dark:text-slate-300">
                      <div class="flex items-center gap-2">
                        <i data-lucide="calculator" class="h-3.5 w-3.5 text-blue-600 dark:text-blue-400"></i>
                        <span>Total Akumulasi Lokal</span>
                      </div>
                    </td>
                    <td class="px-5 py-3">
                      <span class="inline-flex items-center gap-1 font-bold text-slate-900 dark:text-white">
                        <span x-text="totalLocalMinutes()">0</span>
                        <span class="text-[10px] font-normal text-slate-400">min</span>
                      </span>
                    </td>
                    <td class="px-5 py-3">
                      <span class="inline-flex items-center gap-1 font-bold text-slate-900 dark:text-white">
                        <span x-text="totalLocalCalls()">0</span>
                        <span class="text-[10px] font-normal text-slate-400">calls</span>
                      </span>
                    </td>
                    <td class="px-5 py-3">
                      <span class="inline-flex items-center gap-0.5 font-bold text-blue-600 dark:text-blue-400">
                        <span>$</span><span x-text="totalLocalCharges()">0.00</span>
                      </span>
                    </td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>

          <!-- Section 4: Internasional & Customer Service Calls -->
          <div class="grid gap-5 lg:grid-cols-2">
            
            <!-- International Usage -->
            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-xs dark:border-slate-800 dark:bg-slate-900">
              <div class="mb-4 flex items-center gap-3 border-b border-slate-100 pb-3 dark:border-slate-800">
                <span class="grid h-9 w-9 place-items-center rounded-xl bg-blue-50 text-blue-600 dark:bg-blue-950/60 dark:text-blue-400">
                  <i data-lucide="globe" class="h-4.5 w-4.5"></i>
                </span>
                <div>
                  <h2 class="text-sm font-bold text-slate-900 dark:text-white">4. Panggilan Internasional</h2>
                  <p class="text-[11px] text-slate-400">Penggunaan layanan roaming & panggilan internasional</p>
                </div>
              </div>

              <div class="space-y-3.5">
                <div>
                  <label class="mb-1 block text-xs font-semibold text-slate-700 dark:text-slate-300">
                    Total Menit Internasional
                  </label>
                  <div class="relative">
                    <input
                      type="number"
                      name="total_intl_minutes"
                      min="0"
                      step="any"
                      x-model="form.total_intl_minutes"
                      required
                      placeholder="10.0"
                      class="h-9 w-full rounded-xl border border-slate-200/80 bg-slate-50/50 pl-3.5 pr-12 text-xs font-semibold text-slate-900 outline-none transition focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                    >
                    <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-[11px] font-medium text-slate-400">min</span>
                  </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                  <div>
                    <label class="mb-1 block text-xs font-semibold text-slate-700 dark:text-slate-300">Frekuensi Panggilan</label>
                    <div class="relative">
                      <input
                        type="number"
                        name="total_intl_calls"
                        min="0"
                        step="1"
                        x-model="form.total_intl_calls"
                        required
                        placeholder="4"
                        class="h-9 w-full rounded-xl border border-slate-200/80 bg-slate-50/50 pl-3.5 pr-12 text-xs font-semibold text-slate-900 outline-none transition focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                      >
                      <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-[11px] font-medium text-slate-400">call</span>
                    </div>
                  </div>

                  <div>
                    <label class="mb-1 block text-xs font-semibold text-slate-700 dark:text-slate-300">Biaya Tagihan ($)</label>
                    <div class="relative">
                      <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-xs font-bold text-slate-400">$</span>
                      <input
                        type="number"
                        name="total_intl_charge"
                        min="0"
                        step="any"
                        x-model="form.total_intl_charge"
                        required
                        placeholder="2.70"
                        class="h-9 w-full rounded-xl border border-slate-200/80 bg-slate-50/50 pl-7 pr-3 text-xs font-semibold text-slate-900 outline-none transition focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                      >
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Customer Service Support Calls -->
            <div
              class="rounded-2xl border bg-white p-6 shadow-xs transition dark:bg-slate-900"
              x-bind:class="form.customer_service_calls >= 3 ? 'border-rose-300 ring-1 ring-rose-200 dark:border-rose-900 dark:ring-rose-950' : 'border-slate-200/80 dark:border-slate-800'"
            >
              <div class="mb-4 flex items-center justify-between border-b border-slate-100 pb-3 dark:border-slate-800">
                <div class="flex items-center gap-3">
                  <span class="grid h-9 w-9 place-items-center rounded-xl bg-blue-50 text-blue-600 dark:bg-blue-950/60 dark:text-blue-400">
                    <i data-lucide="help-circle" class="h-4.5 w-4.5"></i>
                  </span>
                  <div>
                    <h2 class="text-sm font-bold text-slate-900 dark:text-white">5. Layanan Pelanggan (CS)</h2>
                    <p class="text-[11px] text-slate-400">Frekuensi komplain / bantuan CS</p>
                  </div>
                </div>
                <span
                  class="rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider transition"
                  x-bind:class="form.customer_service_calls >= 3 ? 'bg-rose-100 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300' : 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300'"
                  x-text="form.customer_service_calls >= 3 ? 'Kritis (≥3)' : 'Normal'"
                >
                  Normal
                </span>
              </div>

              <div class="space-y-3.5">
                <!-- Stepper input -->
                <div class="flex items-center gap-2">
                  <button
                    type="button"
                    x-on:click="if (form.customer_service_calls > 0) form.customer_service_calls--"
                    class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-slate-100/80 text-slate-700 transition hover:bg-slate-200 active:scale-95 dark:bg-slate-800 dark:text-slate-300"
                    title="Kurangi"
                  >
                    <i data-lucide="minus" class="h-4 w-4"></i>
                  </button>

                  <input
                    type="number"
                    name="customer_service_calls"
                    min="0"
                    step="1"
                    x-model="form.customer_service_calls"
                    required
                    placeholder="1"
                    class="h-10 w-full rounded-xl border border-slate-200/50 bg-slate-50/50 text-center text-sm font-bold text-slate-900 outline-none focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 dark:border-slate-800 dark:bg-slate-900 dark:text-white"
                  >

                  <button
                    type="button"
                    x-on:click="form.customer_service_calls++"
                    class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-slate-100/80 text-slate-700 transition hover:bg-slate-200 active:scale-95 dark:bg-slate-800 dark:text-slate-300"
                    title="Tambah"
                  >
                    <i data-lucide="plus" class="h-4 w-4"></i>
                  </button>
                </div>

                <!-- Quick Button Chips -->
                <div class="flex items-center justify-between gap-1.5 pt-0.5">
                  <span class="text-[11px] text-slate-400">Pilih Cepat:</span>
                  <div class="flex gap-1.5">
                    <template x-for="val in [0, 1, 2, 3, 4, 5]" :key="val">
                      <button
                        type="button"
                        x-on:click="form.customer_service_calls = val"
                        class="h-7 w-7 rounded-lg text-xs font-semibold transition"
                        x-bind:class="form.customer_service_calls == val ? (val >= 3 ? 'bg-rose-600 text-white shadow-2xs' : 'bg-blue-600 text-white shadow-2xs') : 'bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-400'"
                        x-text="val"
                      ></button>
                    </template>
                  </div>
                </div>

                <!-- Warning Banner if CS calls >= 3 -->
                <div
                  x-show="form.customer_service_calls >= 3"
                  x-transition
                  class="flex items-center gap-2 rounded-xl bg-rose-50 p-3 text-xs font-medium text-rose-700 dark:bg-rose-950/30 dark:text-rose-300"
                  style="display: none;"
                >
                  <i data-lucide="alert-circle" class="h-4 w-4 shrink-0 text-rose-600"></i>
                  <span>≥ 3 panggilan CS meningkatkan risiko churn secara signifikan!</span>
                </div>
              </div>
            </div>

          </div>

          <!-- Bottom Submit Bar -->
          <div class="flex flex-col items-center justify-between gap-3 rounded-2xl border border-transparent bg-white p-5 shadow-xs sm:flex-row dark:bg-slate-900">
            <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
              <i data-lucide="info" class="h-4 w-4 text-blue-600 dark:text-blue-400"></i>
              <span>Seluruh parameter akan diproses oleh model Random Forest via API.</span>
            </div>

            <div class="flex w-full items-center gap-2.5 sm:w-auto">
              <a
                href="{{ route('prediction.reset') }}"
                class="inline-flex h-10 flex-1 items-center justify-center gap-1.5 rounded-xl border border-transparent bg-slate-100/80 px-4 text-xs font-medium text-slate-600 transition hover:bg-slate-200/80 dark:bg-slate-800 dark:text-slate-300 sm:flex-none"
              >
                <i data-lucide="rotate-ccw" class="h-3.5 w-3.5"></i>
                Reset Form
              </a>

              <button
                type="submit"
                class="inline-flex h-10 flex-1 items-center justify-center gap-2 rounded-xl bg-blue-600 px-6 text-xs font-semibold text-white shadow-md shadow-blue-500/20 transition hover:bg-blue-700 active:scale-[0.99] dark:shadow-none sm:flex-none"
              >
                <span x-show="!loading" class="flex items-center gap-2">
                  <i data-lucide="send" class="h-3.5 w-3.5"></i>
                  Jalankan Klasifikasi AI
                </span>
                <span x-show="loading" class="flex items-center gap-2" style="display: none;">
                  <i data-lucide="refresh-cw" class="h-3.5 w-3.5 animate-spin"></i>
                  Memproses AI...
                </span>
              </button>
            </div>
          </div>

        </form>
      </div>

      <!-- Right Column: Result Panel & Feature Importance -->
      <div class="space-y-5">
        
        <!-- Output Box: Hasil Klasifikasi -->
        <div class="rounded-2xl border border-transparent bg-white p-6 shadow-xs dark:bg-slate-900">
          <div class="mb-5 flex items-center justify-between border-b border-transparent pb-1">
            <div class="flex items-center gap-3">
              <span class="grid h-8 w-8 place-items-center rounded-xl bg-blue-50 text-blue-600 dark:bg-blue-950/60 dark:text-blue-400">
                <i data-lucide="gauge" class="h-4 w-4"></i>
              </span>
              <div>
                <h2 class="text-sm font-bold text-slate-900 dark:text-white">Hasil Klasifikasi</h2>
                <p class="text-[11px] text-slate-400">Prediksi & tingkat risiko churn</p>
              </div>
            </div>
            @if ($hasResult)
              <span class="rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider {{ $isChurn ? 'bg-rose-100 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300' : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300' }}">
                {{ $resultData['risiko'] ?? ($isChurn ? 'Risiko Tinggi' : 'Risiko Rendah') }}
              </span>
            @endif
          </div>

          @if ($hasResult)
            <div class="space-y-5">
              
              <!-- Circular SVG Probability Gauge -->
              <div class="flex flex-col items-center justify-center py-2">
                <div class="relative h-36 w-36">
                  <svg class="h-full w-full -rotate-90 transform" viewBox="0 0 100 100">
                    <!-- Background circle -->
                    <circle
                      cx="50"
                      cy="50"
                      r="40"
                      class="stroke-slate-100 dark:stroke-slate-800"
                      stroke-width="9"
                      fill="none"
                    />
                    <!-- Progress circle -->
                    @php
                      $circumference = 2 * M_PI * 40;
                      $strokeOffset = $circumference - ($probability / 100) * $circumference;
                      $strokeColor = $isChurn ? '#E11D48' : '#059669';
                    @endphp
                    <circle
                      cx="50"
                      cy="50"
                      r="40"
                      stroke="{{ $strokeColor }}"
                      stroke-width="9"
                      stroke-linecap="round"
                      fill="none"
                      stroke-dasharray="{{ $circumference }}"
                      stroke-dashoffset="{{ $strokeOffset }}"
                      class="transition-all duration-700 ease-out"
                    />
                  </svg>
                  
                  <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                    <span class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">
                      {{ number_format($probability, 1) }}%
                    </span>
                    <span class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">
                      Probabilitas
                    </span>
                  </div>
                </div>
              </div>

              <!-- Result Status Card -->
              <div class="rounded-xl border border-transparent p-4 text-center {{ $isChurn ? 'bg-rose-50/70 text-rose-950 dark:bg-rose-950/20 dark:text-rose-200' : 'bg-emerald-50/70 text-emerald-950 dark:bg-emerald-950/20 dark:text-emerald-200' }}">
                <div class="flex items-center justify-center gap-1.5 text-sm font-bold uppercase tracking-wider {{ $isChurn ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-600 dark:text-emerald-400' }}">
                  <i data-lucide="{{ $isChurn ? 'shield-alert' : 'shield-check' }}" class="h-4 w-4"></i>
                  <span>{{ $resultData['result'] }}</span>
                </div>
                <p class="mt-1.5 text-xs leading-relaxed text-slate-600 dark:text-slate-300">
                  {{ $resultData['keterangan'] ?? ($isChurn ? 'Pelanggan diprediksi berpotensi tinggi untuk berhenti berlangganan (churn).' : 'Pelanggan memiliki probabilitas loyalitas tinggi dan stabil.') }}
                </p>
              </div>

              <!-- Retention Recommendations -->
              <div class="rounded-xl border border-transparent bg-slate-50/60 p-4 dark:bg-slate-800/40">
                <div class="mb-2 flex items-center gap-1.5 text-xs font-semibold text-slate-800 dark:text-slate-200">
                  <i data-lucide="sparkles" class="h-3.5 w-3.5 text-blue-600 dark:text-blue-400"></i>
                  <span>Rekomendasi Tindakan Retensi:</span>
                </div>
                <ul class="space-y-1.5 text-xs text-slate-600 dark:text-slate-300">
                  @if ($isChurn)
                    <li class="flex items-start gap-2">
                      <i data-lucide="check" class="mt-0.5 h-3.5 w-3.5 shrink-0 text-rose-600 dark:text-rose-400"></i>
                      <span>Hubungi segera via Senior Customer Care untuk menangani keluhan.</span>
                    </li>
                    <li class="flex items-start gap-2">
                      <i data-lucide="check" class="mt-0.5 h-3.5 w-3.5 shrink-0 text-rose-600 dark:text-rose-400"></i>
                      <span>Tawarkan diskon tarif pemakaian siang atau penyesuaian paket.</span>
                    </li>
                  @else
                    <li class="flex items-start gap-2">
                      <i data-lucide="check" class="mt-0.5 h-3.5 w-3.5 shrink-0 text-emerald-600 dark:text-emerald-400"></i>
                      <span>Pelanggan stabil. Jaga loyalitas dengan program reward berkala.</span>
                    </li>
                    <li class="flex items-start gap-2">
                      <i data-lucide="check" class="mt-0.5 h-3.5 w-3.5 shrink-0 text-emerald-600 dark:text-emerald-400"></i>
                      <span>Tawarkan produk nilai tambah (add-on) yang relevan.</span>
                    </li>
                  @endif
                </ul>
              </div>

            </div>
          @else
            <!-- Empty State Ready to Predict -->
            <div class="flex flex-col items-center justify-center rounded-2xl border border-dashed border-slate-200/80 bg-slate-50/40 p-8 text-center dark:border-slate-800 dark:bg-slate-900/50">
              <div class="grid h-12 w-12 place-items-center rounded-2xl bg-blue-50 text-blue-600 dark:bg-blue-950/60 dark:text-blue-400">
                <i data-lucide="sparkles" class="h-6 w-6"></i>
              </div>
              <h3 class="mt-3 text-xs font-bold text-slate-900 dark:text-white">Siap Menjalankan Klasifikasi</h3>
              <p class="mt-1 text-xs leading-relaxed text-slate-400">
                Isi parameter di samping atau klik sampel siap pakai di atas untuk menguji prediksi.
              </p>
              <div class="mt-4 flex gap-2">
                <button
                  type="button"
                  x-on:click="loadPreset('churn')"
                  class="rounded-xl border border-transparent bg-rose-50 px-3 py-1.5 text-xs font-semibold text-rose-600 hover:bg-rose-100 dark:bg-rose-950/40 dark:text-rose-400"
                >
                  Uji Churn
                </button>
                <button
                  type="button"
                  x-on:click="loadPreset('loyal')"
                  class="rounded-xl border border-transparent bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-600 hover:bg-emerald-100 dark:bg-emerald-950/40 dark:text-emerald-400"
                >
                  Uji Loyal
                </button>
              </div>
            </div>
          @endif
        </div>

        <!-- Faktor Dominan (Feature Importance) -->
        <div class="rounded-2xl border border-transparent bg-white p-6 shadow-xs dark:bg-slate-900" style="font-family: 'Poppins', sans-serif;">
          <div class="mb-4 flex items-center justify-between border-b border-transparent pb-1">
            <div class="flex items-center gap-3">
              <span class="grid h-8 w-8 place-items-center rounded-xl bg-blue-50 text-blue-600 dark:bg-blue-950/60 dark:text-blue-400">
                <i data-lucide="bar-chart-3" class="h-4 w-4"></i>
              </span>
              <div>
                <h2 class="text-sm font-semibold text-slate-700 dark:text-white">Faktor Pengaruh Model</h2>
                <p class="text-[11px] text-slate-400">Tingkat signifikansi fitur pada Random Forest</p>
              </div>
            </div>
          </div>

          @if (!empty($resultData['top_factors']))
            <div class="space-y-3.5">
              @foreach ($resultData['top_factors'] as $factor)
                <div>
                  <div class="mb-1 flex items-center justify-between text-xs">
                    <span class="font-normal text-slate-600 dark:text-slate-300">
                      {{ $factor['label'] }}
                    </span>
                    <div class="flex items-center gap-1.5">
                      @if (isset($factor['input_value']))
                        <span class="rounded bg-slate-100 px-1.5 py-0.5 text-[10px] text-slate-500 dark:bg-slate-800 dark:text-slate-400">
                          Nilai: {{ $factor['input_value'] }}
                        </span>
                      @endif
                      <span class="font-semibold text-slate-700 dark:text-white">{{ $factor['importance_percentage'] }}%</span>
                    </div>
                  </div>
                  <div class="h-1.5 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
                    <div
                      class="h-full rounded-full bg-blue-600 transition-all duration-500 dark:bg-blue-500"
                      style="width: {{ min(max((float) $factor['importance_percentage'] * 3, 5), 100) }}%"
                    ></div>
                  </div>
                </div>
              @endforeach
              <p class="pt-1 text-[10px] text-slate-400">
                * Signifikansi dihitung berdasarkan bobot pohon keputusan Random Forest.
              </p>
            </div>
          @else
            <div class="flex flex-col items-center justify-center rounded-2xl border border-dashed border-slate-200/80 bg-slate-50/40 p-6 text-center text-xs text-slate-400 dark:border-slate-800 dark:bg-slate-900/50">
              <i data-lucide="bar-chart-3" class="mb-1.5 h-5 w-5 text-slate-300 dark:text-slate-600"></i>
              <span>Faktor dominan akan tampil otomatis setelah klasifikasi diproses.</span>
            </div>
          @endif
        </div>

      </div>
    </div>
  </div>

  <script>
    document.addEventListener('alpine:init', () => {
      Alpine.data('churnPredictionForm', (config) => ({
        loading: false,
        toastMessage: '',
        form: {
          account_length: config.initialValues.account_length || '',
          area_code: config.initialValues.area_code || '415',
          international_plan: String(config.initialValues.international_plan ?? '0'),
          voice_mail_plan: String(config.initialValues.voice_mail_plan ?? '0'),
          number_vmail_messages: config.initialValues.number_vmail_messages ?? '',
          total_day_minutes: config.initialValues.total_day_minutes ?? '',
          total_day_calls: config.initialValues.total_day_calls ?? '',
          total_day_charge: config.initialValues.total_day_charge ?? '',
          total_eve_minutes: config.initialValues.total_eve_minutes ?? '',
          total_eve_calls: config.initialValues.total_eve_calls ?? '',
          total_eve_charge: config.initialValues.total_eve_charge ?? '',
          total_night_minutes: config.initialValues.total_night_minutes ?? '',
          total_night_calls: config.initialValues.total_night_calls ?? '',
          total_night_charge: config.initialValues.total_night_charge ?? '',
          total_intl_minutes: config.initialValues.total_intl_minutes ?? '',
          total_intl_calls: config.initialValues.total_intl_calls ?? '',
          total_intl_charge: config.initialValues.total_intl_charge ?? '',
          customer_service_calls: config.initialValues.customer_service_calls ?? '1',
        },

        presets: {
          churn: {
            account_length: 128,
            area_code: 415,
            international_plan: '1',
            voice_mail_plan: '0',
            number_vmail_messages: 0,
            total_day_minutes: 285.5,
            total_day_calls: 110,
            total_day_charge: 48.54,
            total_eve_minutes: 242.0,
            total_eve_calls: 108,
            total_eve_charge: 20.57,
            total_night_minutes: 215.0,
            total_night_calls: 95,
            total_night_charge: 9.68,
            total_intl_minutes: 14.5,
            total_intl_calls: 3,
            total_intl_charge: 3.92,
            customer_service_calls: 4,
          },
          loyal: {
            account_length: 104,
            area_code: 415,
            international_plan: '0',
            voice_mail_plan: '1',
            number_vmail_messages: 28,
            total_day_minutes: 152.4,
            total_day_calls: 92,
            total_day_charge: 25.91,
            total_eve_minutes: 172.0,
            total_eve_calls: 88,
            total_eve_charge: 14.62,
            total_night_minutes: 168.0,
            total_night_calls: 80,
            total_night_charge: 7.56,
            total_intl_minutes: 8.2,
            total_intl_calls: 5,
            total_intl_charge: 2.21,
            customer_service_calls: 1,
          },
          moderate: {
            account_length: 85,
            area_code: 510,
            international_plan: '0',
            voice_mail_plan: '0',
            number_vmail_messages: 0,
            total_day_minutes: 198.0,
            total_day_calls: 98,
            total_day_charge: 33.66,
            total_eve_minutes: 195.0,
            total_eve_calls: 94,
            total_eve_charge: 16.58,
            total_night_minutes: 190.0,
            total_night_calls: 88,
            total_night_charge: 8.55,
            total_intl_minutes: 10.0,
            total_intl_calls: 4,
            total_intl_charge: 2.70,
            customer_service_calls: 2,
          }
        },

        loadPreset(type) {
          const sample = this.presets[type];
          if (!sample) return;
          
          Object.keys(sample).forEach((key) => {
            this.form[key] = sample[key];
          });

          const titles = {
            churn: 'Sampel Pelanggan Risiko Tinggi (High Churn)',
            loyal: 'Sampel Pelanggan Loyal / Aman (Low Risk)',
            moderate: 'Sampel Pelanggan Risiko Moderat'
          };
          this.toastMessage = `Berhasil memuat ${titles[type] || 'sampel'} ke formulir!`;
          
          setTimeout(() => {
            if (window.lucide) window.lucide.createIcons();
          }, 50);

          setTimeout(() => {
            this.toastMessage = '';
          }, 4000);
        },

        totalLocalMinutes() {
          const day = parseFloat(this.form.total_day_minutes) || 0;
          const eve = parseFloat(this.form.total_eve_minutes) || 0;
          const night = parseFloat(this.form.total_night_minutes) || 0;
          return (day + eve + night).toFixed(1);
        },

        totalLocalCalls() {
          const day = parseInt(this.form.total_day_calls) || 0;
          const eve = parseInt(this.form.total_eve_calls) || 0;
          const night = parseInt(this.form.total_night_calls) || 0;
          return day + eve + night;
        },

        totalLocalCharges() {
          const day = parseFloat(this.form.total_day_charge) || 0;
          const eve = parseFloat(this.form.total_eve_charge) || 0;
          const night = parseFloat(this.form.total_night_charge) || 0;
          return (day + eve + night).toFixed(2);
        },

        init() {
          this.$nextTick(() => {
            if (window.lucide) window.lucide.createIcons();
          });
        }
      }));
    });
  </script>
@endsection

