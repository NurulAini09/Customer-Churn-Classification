@extends('layouts.app')

@section('title', 'Dashboard Analitik - ChurnPredict AI')
@section('page-title', 'Dashboard')

@section('content')
  @php
    $metrics = $dashboard['metrics'] ?? [];
    $stats = $dashboard['stats'] ?? [];
    $riskDist = $dashboard['riskDistribution'] ?? [];
    $dailyTrend = $dashboard['dailyTrend'] ?? [];
    $correlations = $dashboard['featureCorrelations'] ?? [];
    $insights = $dashboard['insights'] ?? [];
    $recentHistory = $dashboard['recentHistory'] ?? [];
  @endphp

  <div
    x-data="dashboardApp({
      dailyTrend: {{ json_encode($dailyTrend) }},
      riskDistribution: {{ json_encode($riskDist) }},
      metrics: {{ json_encode($metrics) }},
      recentHistory: {{ json_encode($recentHistory) }}
    })"
    x-init="init()"
    class="space-y-5 pb-6"
  >
    <!-- Top Action Bar & Status Banner -->
    <div class="flex flex-col gap-3 border-b border-transparent pb-1 sm:flex-row sm:items-center sm:justify-between">
      <div>
        <span class="text-[10px] font-semibold tracking-wider uppercase text-blue-600 dark:text-blue-400">— RINGKASAN ANALITIK & EKSEKUTIF</span>
        <div class="flex items-center gap-2 mt-0.5">
          <h1 class="text-2xl font-semibold tracking-tight text-slate-700 dark:text-white">Dashboard Analitik</h1>
          <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-0.5 text-[10px] font-medium text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300">
            <span class="status-pulse-dot h-2 w-2 rounded-full bg-emerald-500"></span>
            FastAPI & ML Aktif
          </span>
        </div>
        <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
          Ringkasan performa klasifikasi churn pelanggan, segmentasi risiko, dan analitik retensi.
        </p>
      </div>

      <!-- Action Buttons -->
      <div class="flex flex-wrap items-center gap-2">
        <button
          type="button"
          x-on:click="simulatorOpen = !simulatorOpen"
          class="inline-flex h-9 items-center gap-1.5 rounded-xl border border-transparent bg-white px-3.5 text-xs font-medium text-slate-700 shadow-xs transition hover:bg-slate-50 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800"
        >
          <i data-lucide="sliders" class="h-3.5 w-3.5 text-blue-600 dark:text-blue-400"></i>
          <span x-text="simulatorOpen ? 'Tutup Simulasi' : 'Simulasi Cepat'">Simulasi Cepat</span>
        </button>

        <button
          type="button"
          x-on:click="exportCsv()"
          class="inline-flex h-9 items-center gap-1.5 rounded-xl border border-transparent bg-white px-3.5 text-xs font-medium text-slate-700 shadow-xs transition hover:bg-slate-50 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800"
        >
          <i data-lucide="download" class="h-3.5 w-3.5 text-slate-400"></i>
          Export CSV
        </button>

        <a
          href="{{ route('prediction.page') }}"
          class="inline-flex h-9 items-center justify-center gap-1.5 rounded-xl bg-blue-600 px-4 text-xs font-semibold text-white shadow-md shadow-blue-500/20 transition hover:bg-blue-700 active:scale-[0.99] dark:shadow-none"
        >
          <i data-lucide="plus" class="h-4 w-4"></i>
          Klasifikasi Baru
        </a>
      </div>
    </div>

    <!-- Interactive Quick Churn Simulator -->
    <div
      x-show="simulatorOpen"
      x-transition:enter="transition ease-out duration-300"
      x-transition:enter-start="opacity-0 -translate-y-3"
      x-transition:enter-end="opacity-100 translate-y-0"
      x-transition:leave="transition ease-in duration-200"
      x-transition:leave-start="opacity-100 translate-y-0"
      x-transition:leave-end="opacity-0 -translate-y-3"
      class="overflow-hidden rounded-2xl border border-blue-200/80 bg-gradient-to-br from-white to-blue-50/30 p-5 shadow-xs dark:border-blue-900/40 dark:from-slate-900 dark:to-blue-950/20"
    >
      <div class="mb-4 flex items-center justify-between border-b border-blue-100/80 pb-3 dark:border-blue-900/30">
        <div class="flex items-center gap-2.5">
          <div class="grid h-8 w-8 place-items-center rounded-xl bg-blue-50 text-blue-600 dark:bg-blue-950/60 dark:text-blue-400">
            <i data-lucide="sliders" class="h-4 w-4"></i>
          </div>
          <div>
            <h3 class="text-sm font-semibold text-slate-700 dark:text-white">Simulasi Cepat Risiko Churn (What-If Calculator)</h3>
            <p class="text-[11px] text-slate-400">Geser parameter untuk melihat perkiraan probabilitas churn pelanggan secara real-time.</p>
          </div>
        </div>
        <button
          type="button"
          x-on:click="simulatorOpen = false"
          class="rounded-lg p-1.5 text-slate-400 hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-950/40 dark:hover:text-blue-400"
        >
          &times;
        </button>
      </div>

      <div class="grid gap-6 lg:grid-cols-12">
        <!-- Sliders Form (7 cols) -->
        <div class="space-y-4 lg:col-span-7">
          <!-- CS Calls Slider -->
          <div>
            <div class="flex justify-between text-xs">
              <span class="font-medium text-slate-600 dark:text-slate-300">Panggilan Customer Service (CS Calls)</span>
              <span class="font-semibold text-blue-600 dark:text-blue-400" x-text="simCsCalls + ' kali'">0 kali</span>
            </div>
            <input
              type="range"
              min="0"
              max="9"
              step="1"
              x-model.number="simCsCalls"
              x-on:input="calcSimRisk()"
              class="accent-slider mt-2 h-2 w-full cursor-pointer appearance-none rounded-lg bg-slate-200 dark:bg-slate-700"
            />
            <div class="mt-1 flex justify-between text-[10px] text-slate-400">
              <span>0 (Puas)</span>
              <span>3 (Batas Kritis)</span>
              <span>9 (Komplain Berat)</span>
            </div>
          </div>

          <!-- Total Day Minutes Slider -->
          <div>
            <div class="flex justify-between text-xs">
              <span class="font-medium text-slate-600 dark:text-slate-300">Total Menit Bicara Siang (Day Minutes)</span>
              <span class="font-semibold text-blue-600 dark:text-blue-400" x-text="simDayMinutes + ' menit'">180 menit</span>
            </div>
            <input
              type="range"
              min="0"
              max="350"
              step="5"
              x-model.number="simDayMinutes"
              x-on:input="calcSimRisk()"
              class="accent-slider mt-2 h-2 w-full cursor-pointer appearance-none rounded-lg bg-slate-200 dark:bg-slate-700"
            />
            <div class="mt-1 flex justify-between text-[10px] text-slate-400">
              <span>0 min</span>
              <span>180 min (Rata-rata)</span>
              <span>350 min (Tinggi)</span>
            </div>
          </div>

          <!-- Account Length Slider -->
          <div>
            <div class="flex justify-between text-xs">
              <span class="font-medium text-slate-600 dark:text-slate-300">Masa Berlangganan (Account Length)</span>
              <span class="font-semibold text-blue-600 dark:text-blue-400" x-text="simAccountLength + ' hari'">100 hari</span>
            </div>
            <input
              type="range"
              min="1"
              max="250"
              step="5"
              x-model.number="simAccountLength"
              x-on:input="calcSimRisk()"
              class="accent-slider mt-2 h-2 w-full cursor-pointer appearance-none rounded-lg bg-slate-200 dark:bg-slate-700"
            />
          </div>

          <!-- Toggles for International & Voicemail Plan -->
          <div class="grid grid-cols-2 gap-3 pt-1">
            <label class="flex cursor-pointer items-center justify-between rounded-xl border border-slate-200/80 bg-white p-2.5 transition hover:border-blue-400 dark:border-slate-700 dark:bg-slate-800 dark:hover:border-blue-600">
              <span class="text-xs font-medium text-slate-600 dark:text-slate-300">Paket Internasional</span>
              <input
                type="checkbox"
                x-model="simIntlPlan"
                x-on:change="calcSimRisk()"
                class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 dark:border-slate-600"
              />
            </label>
            <label class="flex cursor-pointer items-center justify-between rounded-xl border border-slate-200/80 bg-white p-2.5 transition hover:border-blue-400 dark:border-slate-700 dark:bg-slate-800 dark:hover:border-blue-600">
              <span class="text-xs font-medium text-slate-600 dark:text-slate-300">Paket Voicemail</span>
              <input
                type="checkbox"
                x-model="simVmailPlan"
                x-on:change="calcSimRisk()"
                class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 dark:border-slate-600"
              />
            </label>
          </div>
        </div>

        <!-- Simulation Result Live Display (5 cols) -->
        <div class="flex flex-col justify-between rounded-2xl border border-slate-200/80 bg-white p-4 shadow-xs lg:col-span-5 dark:border-slate-700 dark:bg-slate-800">
          <div>
            <div class="flex items-center justify-between">
              <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Perkiraan Skor Risiko</span>
              <span
                class="rounded-full px-2.5 py-0.5 text-[11px] font-semibold uppercase tracking-wide"
                x-bind:class="{
                  'bg-rose-50 text-rose-600 dark:bg-rose-950/60 dark:text-rose-400': simRiskLevel === 'Tinggi',
                  'bg-amber-50 text-amber-600 dark:bg-amber-950/60 dark:text-amber-400': simRiskLevel === 'Sedang',
                  'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/60 dark:text-emerald-400': simRiskLevel === 'Rendah'
                }"
                x-text="'Risiko ' + simRiskLevel"
              ></span>
            </div>

            <!-- Big Probability Percentage -->
            <div class="mt-4 flex items-baseline gap-2">
              <span class="text-4xl font-semibold tracking-tight" x-bind:class="{
                'text-rose-600 dark:text-rose-400': simRiskLevel === 'Tinggi',
                'text-amber-600 dark:text-amber-400': simRiskLevel === 'Sedang',
                'text-emerald-600 dark:text-emerald-400': simRiskLevel === 'Rendah'
              }" x-text="simProb.toFixed(1) + '%'">0%</span>
              <span class="text-xs font-medium text-slate-500 dark:text-slate-400" x-text="simResult">Tidak Churn</span>
            </div>

            <!-- Dynamic Meter Progress Bar -->
            <div class="mt-3">
              <div class="h-2.5 w-full overflow-hidden rounded-full bg-slate-100 dark:bg-slate-700">
                <div
                  class="h-full rounded-full transition-all duration-300"
                  x-bind:class="{
                    'bg-rose-500': simRiskLevel === 'Tinggi',
                    'bg-amber-500': simRiskLevel === 'Sedang',
                    'bg-emerald-500': simRiskLevel === 'Rendah'
                  }"
                  x-bind:style="'width: ' + simProb + '%'"
                ></div>
              </div>
            </div>

            <!-- Dynamic Advice Note -->
            <p class="mt-3 text-xs leading-relaxed text-slate-500 dark:text-slate-400" x-text="simAdvice"></p>
          </div>

          <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-700">
            <a
              href="{{ route('prediction.page') }}"
              class="inline-flex w-full items-center justify-center gap-1.5 rounded-xl bg-blue-600 py-2.5 text-xs font-semibold text-white shadow-md shadow-blue-500/20 transition hover:bg-blue-700 active:scale-[0.99]"
            >
              <i data-lucide="arrow-up-right" class="h-3.5 w-3.5"></i>
              Buka Form Prediksi Lengkap
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- 4 Primary KPI Stat Cards -->
    <section class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
      <!-- Card 1: Total Klasifikasi -->
      <div class="stat-card-glow relative overflow-hidden rounded-2xl border border-transparent bg-white p-5 shadow-xs dark:bg-slate-900">
        <div class="flex items-center justify-between">
          <span class="text-[11px] font-medium uppercase tracking-[0.08em] text-slate-500 dark:text-slate-400">Total Klasifikasi</span>
          <div class="grid h-9 w-9 place-items-center rounded-xl bg-blue-50 text-blue-600 dark:bg-blue-950/60 dark:text-blue-400">
            <i data-lucide="users" class="h-4 w-4"></i>
          </div>
        </div>
        <p class="mt-2 text-2xl font-semibold leading-none tracking-tight text-slate-700 dark:text-white">{{ $metrics['total'] ?? 0 }}</p>
        <div class="mt-2.5 flex items-center justify-between text-[11px]">
          <span class="text-slate-400">Riwayat tersimpan</span>
          <span class="font-medium text-blue-600 dark:text-blue-400">{{ $metrics['churn_count'] ?? 0 }} Churn</span>
        </div>
      </div>

      <!-- Card 2: Churn Rate -->
      <div class="stat-card-glow relative overflow-hidden rounded-2xl border border-transparent bg-white p-5 shadow-xs dark:bg-slate-900">
        <div class="flex items-center justify-between">
          <span class="text-[11px] font-medium uppercase tracking-[0.08em] text-slate-500 dark:text-slate-400">Tingkat Churn Rate</span>
          <div class="grid h-9 w-9 place-items-center rounded-xl bg-rose-50 text-rose-600 dark:bg-rose-950/60 dark:text-rose-400">
            <i data-lucide="trending-down" class="h-4 w-4"></i>
          </div>
        </div>
        <div class="mt-2 flex items-baseline gap-2">
          <p class="text-2xl font-semibold leading-none tracking-tight text-slate-700 dark:text-white">{{ $metrics['churn_rate'] ?? 0 }}%</p>
          <span class="text-[10px] font-medium uppercase {{ ($metrics['churn_rate'] ?? 0) > 25 ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-600 dark:text-emerald-400' }}">
            {{ ($metrics['churn_rate'] ?? 0) > 25 ? 'Waspada' : 'Terkendali' }}
          </span>
        </div>
        <div class="mt-2.5 flex items-center justify-between text-[11px]">
          <span class="text-slate-400">{{ $metrics['non_churn_count'] ?? 0 }} Retained</span>
          <div class="h-1.5 w-16 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
            <div class="h-full bg-rose-500" style="width: {{ min(100, $metrics['churn_rate'] ?? 0) }}%"></div>
          </div>
        </div>
      </div>

      <!-- Card 3: Average Probability -->
      <div class="stat-card-glow relative overflow-hidden rounded-2xl border border-transparent bg-white p-5 shadow-xs dark:bg-slate-900">
        <div class="flex items-center justify-between">
          <span class="text-[11px] font-medium uppercase tracking-[0.08em] text-slate-500 dark:text-slate-400">Avg Probability</span>
          <div class="grid h-9 w-9 place-items-center rounded-xl bg-indigo-50 text-indigo-600 dark:bg-indigo-950/60 dark:text-indigo-400">
            <i data-lucide="activity" class="h-4 w-4"></i>
          </div>
        </div>
        <p class="mt-2 text-2xl font-semibold leading-none tracking-tight text-slate-700 dark:text-white">{{ $metrics['average_probability'] ?? 0 }}%</p>
        <div class="mt-2.5 flex items-center justify-between text-[11px]">
          <span class="text-slate-400">Rata-rata risiko sistem</span>
          <span class="font-medium text-blue-600 dark:text-blue-400">
            {{ ($metrics['average_probability'] ?? 0) >= 60 ? 'Risiko Tinggi' : (($metrics['average_probability'] ?? 0) >= 35 ? 'Risiko Sedang' : 'Risiko Rendah') }}
          </span>
        </div>
      </div>

      <!-- Card 4: Prioritas Intervensi -->
      <div class="stat-card-glow relative overflow-hidden rounded-2xl border border-rose-200/60 bg-gradient-to-br from-white to-rose-50/30 p-5 shadow-xs dark:border-rose-900/30 dark:from-slate-900 dark:to-rose-950/10">
        <div class="flex items-center justify-between">
          <span class="text-[11px] font-medium uppercase tracking-[0.08em] text-rose-600 dark:text-rose-400">Prioritas Intervensi</span>
          <div class="grid h-9 w-9 place-items-center rounded-xl bg-rose-50 text-rose-600 dark:bg-rose-950/60 dark:text-rose-400">
            <i data-lucide="shield-alert" class="h-4 w-4"></i>
          </div>
        </div>
        <p class="mt-2 text-2xl font-semibold leading-none tracking-tight text-rose-600 dark:text-rose-400">{{ $metrics['high_risk_count'] ?? 0 }}</p>
        <div class="mt-2.5 flex items-center justify-between text-[11px]">
          <span class="font-medium text-rose-500 dark:text-rose-400">Pelanggan Risiko Tinggi</span>
          <a href="{{ route('prediction.history.page') }}" class="font-medium text-blue-600 hover:underline dark:text-blue-400">Tinjau &rarr;</a>
        </div>
      </div>
    </section>

    <!-- Visual Analytics & Key Charts Grid -->
    <div class="grid gap-4 lg:grid-cols-12">
      <!-- Chart 1: Trend Klasifikasi Harian -->
      <section class="rounded-2xl border border-transparent bg-white p-5 shadow-xs lg:col-span-7 dark:bg-slate-900">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between border-b border-slate-100/80 pb-3.5 dark:border-slate-800">
          <div>
            <h3 class="text-sm font-semibold text-slate-700 dark:text-white">Tren Klasifikasi & Churn</h3>
            <p class="text-[11px] text-slate-400">Distribusi volume prediksi harian pelanggan churn vs aman.</p>
          </div>
          <div class="flex items-center gap-1 text-xs">
            <button
              type="button"
              x-on:click="toggleChartDataset('all')"
              x-bind:class="activeDataset === 'all' ? 'bg-blue-600 text-white' : 'bg-slate-50 text-slate-500 hover:bg-slate-100 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-slate-700'"
              class="rounded-lg px-2.5 py-1 font-medium transition"
            >Semua</button>
            <button
              type="button"
              x-on:click="toggleChartDataset('churn')"
              x-bind:class="activeDataset === 'churn' ? 'bg-rose-500 text-white' : 'bg-slate-50 text-slate-500 hover:bg-slate-100 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-slate-700'"
              class="rounded-lg px-2.5 py-1 font-medium transition"
            >Churn Saja</button>
          </div>
        </div>

        <div class="relative mt-4 h-64 w-full">
          <canvas id="dailyTrendChart"></canvas>
          <div
            x-show="!hasTrendData"
            class="absolute inset-0 flex flex-col items-center justify-center bg-white/90 text-center text-xs text-slate-500 dark:bg-slate-900/90 dark:text-slate-400"
          >
            <i data-lucide="bar-chart-3" class="h-8 w-8 text-slate-300 dark:text-slate-600"></i>
            <p class="mt-2 font-medium">Belum cukup data riwayat untuk menampilkan grafik tren.</p>
            <p class="text-[11px] text-slate-400">Jalankan prediksi untuk menghasilkan visualisasi.</p>
          </div>
        </div>
      </section>

      <!-- Chart 2: Distribusi Tingkat Risiko -->
      <section class="rounded-2xl border border-transparent bg-white p-5 shadow-xs lg:col-span-5 dark:bg-slate-900">
        <div class="border-b border-slate-100/80 pb-3.5 dark:border-slate-800">
          <h3 class="text-sm font-semibold text-slate-700 dark:text-white">Distribusi Tingkat Risiko</h3>
          <p class="text-[11px] text-slate-400">Segmentasi hasil prediksi model (Rendah, Sedang, Tinggi).</p>
        </div>

        <div class="relative mt-4 flex flex-col items-center justify-center">
          <div class="relative h-52 w-52">
            <canvas id="riskDistributionChart"></canvas>
            <div class="pointer-events-none absolute inset-0 flex flex-col items-center justify-center text-center">
              <span class="text-2xl font-semibold tracking-tight text-slate-700 dark:text-white">{{ $metrics['total'] ?? 0 }}</span>
              <span class="text-[10px] uppercase font-medium text-slate-400">Total Data</span>
            </div>
          </div>

          <!-- Custom Legend Badges -->
          <div class="mt-4 grid w-full grid-cols-3 gap-2 text-center text-xs">
            @foreach ($riskDist as $item)
              <div class="rounded-xl border border-slate-200/80 p-2.5 dark:border-slate-700" style="background-color: {{ $item['bg'] }};">
                <span class="text-[10px] font-semibold uppercase tracking-wider" style="color: {{ $item['text'] }}">{{ $item['label'] }}</span>
                <p class="mt-0.5 font-semibold text-slate-700 dark:text-white">{{ $item['count'] }} ({{ $item['percentage'] }}%)</p>
              </div>
            @endforeach
          </div>
        </div>
      </section>
    </div>

    <!-- Feature Impact & Actionable Insights Section -->
    <div class="grid gap-4 lg:grid-cols-12">
      <!-- Key Churn Drivers Breakdown (7 cols) -->
      <section class="rounded-2xl border border-transparent bg-white p-5 shadow-xs lg:col-span-7 dark:bg-slate-900">
        <div class="flex items-center justify-between border-b border-slate-100/80 pb-3 dark:border-slate-800">
          <div>
            <h3 class="text-sm font-semibold text-slate-700 dark:text-white">Analisis Faktor Kritis Pemicu Churn</h3>
            <p class="text-[11px] text-slate-400">Korelasi churn rate berdasarkan parameter layanan pelanggan.</p>
          </div>
          <span class="rounded-lg bg-slate-50 px-2.5 py-0.5 text-[10px] font-medium text-slate-500 dark:bg-slate-800 dark:text-slate-400">Faktor Dominan</span>
        </div>

        <div class="mt-4 space-y-4">
          <!-- Factor 1: Customer Service Calls -->
          <div>
            <div class="mb-1.5 flex items-center justify-between text-xs">
              <span class="font-medium text-slate-600 dark:text-slate-300">Jumlah Panggilan Customer Service (CS Calls)</span>
              <span class="text-[11px] text-slate-400">Tingkat Churn</span>
            </div>
            <div class="space-y-2">
              <div class="flex items-center gap-3 text-xs">
                <span class="w-24 text-[11px] text-slate-400">0-1 Panggilan</span>
                <div class="h-2 flex-1 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
                  <div class="h-full rounded-full bg-emerald-500" style="width: {{ $correlations['cs_calls']['low']['rate'] ?? 10 }}%"></div>
                </div>
                <span class="w-12 text-right font-semibold text-slate-700 dark:text-white">{{ $correlations['cs_calls']['low']['rate'] ?? 0 }}%</span>
              </div>
              <div class="flex items-center gap-3 text-xs">
                <span class="w-24 text-[11px] text-slate-400">2-3 Panggilan</span>
                <div class="h-2 flex-1 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
                  <div class="h-full rounded-full bg-amber-500" style="width: {{ $correlations['cs_calls']['med']['rate'] ?? 25 }}%"></div>
                </div>
                <span class="w-12 text-right font-semibold text-slate-700 dark:text-white">{{ $correlations['cs_calls']['med']['rate'] ?? 0 }}%</span>
              </div>
              <div class="flex items-center gap-3 text-xs">
                <span class="w-24 text-[11px] font-medium text-rose-600 dark:text-rose-400">≥ 4 Panggilan</span>
                <div class="h-2 flex-1 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
                  <div class="h-full rounded-full bg-rose-500" style="width: {{ $correlations['cs_calls']['high']['rate'] ?? 65 }}%"></div>
                </div>
                <span class="w-12 text-right font-semibold text-rose-600 dark:text-rose-400">{{ $correlations['cs_calls']['high']['rate'] ?? 0 }}%</span>
              </div>
            </div>
          </div>

          <!-- Factor 2: International Plan -->
          <div class="border-t border-slate-100/80 pt-3 dark:border-slate-800">
            <div class="mb-1.5 flex items-center justify-between text-xs">
              <span class="font-medium text-slate-600 dark:text-slate-300">Paket Layanan Internasional</span>
              <span class="text-[11px] text-slate-400">Tingkat Churn</span>
            </div>
            <div class="space-y-2">
              <div class="flex items-center gap-3 text-xs">
                <span class="w-24 text-[11px] text-slate-400">Dengan Paket</span>
                <div class="h-2 flex-1 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
                  <div class="h-full rounded-full bg-blue-500" style="width: {{ $correlations['intl_plan']['yes']['rate'] ?? 40 }}%"></div>
                </div>
                <span class="w-12 text-right font-semibold text-blue-600 dark:text-blue-400">{{ $correlations['intl_plan']['yes']['rate'] ?? 0 }}%</span>
              </div>
              <div class="flex items-center gap-3 text-xs">
                <span class="w-24 text-[11px] text-slate-400">Tanpa Paket</span>
                <div class="h-2 flex-1 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
                  <div class="h-full rounded-full bg-slate-400" style="width: {{ $correlations['intl_plan']['no']['rate'] ?? 15 }}%"></div>
                </div>
                <span class="w-12 text-right font-semibold text-slate-700 dark:text-white">{{ $correlations['intl_plan']['no']['rate'] ?? 0 }}%</span>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Smart Insights & Recommendations (5 cols) -->
      <section class="rounded-2xl border border-transparent bg-white p-5 shadow-xs lg:col-span-5 dark:bg-slate-900">
        <div class="flex items-center justify-between border-b border-slate-100/80 pb-3.5 dark:border-slate-800">
          <div class="flex items-center gap-2.5">
            <div class="grid h-7 w-7 place-items-center rounded-xl bg-blue-50 text-blue-600 dark:bg-blue-950/60 dark:text-blue-400">
              <i data-lucide="sparkles" class="h-4 w-4"></i>
            </div>
            <div>
              <h3 class="text-sm font-semibold text-slate-700 dark:text-white">Insight Otomatis Sistem</h3>
            </div>
          </div>
          <span class="rounded-lg bg-blue-50 px-2 py-0.5 text-[10px] font-medium uppercase tracking-wider text-blue-600 dark:bg-blue-950/60 dark:text-blue-400">AI Intelligence</span>
        </div>

        <div class="mt-4 space-y-3">
          @forelse ($insights as $insight)
            <div class="rounded-xl border border-slate-200/80 bg-slate-50/60 p-3.5 text-xs transition hover:bg-slate-100/70 dark:border-slate-800 dark:bg-slate-800/40 dark:hover:bg-slate-800/70">
              <div class="flex items-center justify-between gap-2">
                <div class="flex items-center gap-2">
                  @if ($insight['type'] === 'danger')
                    <span class="grid h-6 w-6 shrink-0 place-items-center rounded-lg bg-rose-50 text-rose-600 dark:bg-rose-950/60 dark:text-rose-400">
                      <i data-lucide="{{ $insight['icon'] ?? 'alert-circle' }}" class="h-3.5 w-3.5"></i>
                    </span>
                  @elseif ($insight['type'] === 'warning')
                    <span class="grid h-6 w-6 shrink-0 place-items-center rounded-lg bg-amber-50 text-amber-600 dark:bg-amber-950/60 dark:text-amber-400">
                      <i data-lucide="{{ $insight['icon'] ?? 'alert-triangle' }}" class="h-3.5 w-3.5"></i>
                    </span>
                  @else
                    <span class="grid h-6 w-6 shrink-0 place-items-center rounded-lg bg-emerald-50 text-emerald-600 dark:bg-emerald-950/60 dark:text-emerald-400">
                      <i data-lucide="{{ $insight['icon'] ?? 'shield-check' }}" class="h-3.5 w-3.5"></i>
                    </span>
                  @endif
                  <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $insight['title'] }}</span>
                </div>

                @if ($insight['type'] === 'danger')
                  <span class="shrink-0 rounded-lg bg-rose-50 px-2 py-0.5 text-[10px] font-medium text-rose-600 dark:bg-rose-950/60 dark:text-rose-400">Kritis</span>
                @elseif ($insight['type'] === 'warning')
                  <span class="shrink-0 rounded-lg bg-amber-50 px-2 py-0.5 text-[10px] font-medium text-amber-600 dark:bg-amber-950/60 dark:text-amber-400">Perhatian</span>
                @else
                  <span class="shrink-0 rounded-lg bg-emerald-50 px-2 py-0.5 text-[10px] font-medium text-emerald-600 dark:bg-emerald-950/60 dark:text-emerald-400">Optimal</span>
                @endif
              </div>
              <p class="mt-2 text-[11.5px] leading-relaxed text-slate-500 dark:text-slate-400">{{ $insight['text'] }}</p>
            </div>
          @empty
            <div class="rounded-xl border border-dashed border-slate-200/80 bg-slate-50/50 p-6 text-center text-xs text-slate-400 dark:border-slate-800 dark:bg-slate-900/50">
              Belum ada insight tersedia. Jalankan prediksi baru untuk melihat analisis pola retensi.
            </div>
          @endforelse
        </div>
      </section>
    </div>

    <!-- Recent History Table -->
    <section class="overflow-hidden rounded-2xl border border-transparent bg-white shadow-xs dark:bg-slate-900">
      <div class="flex flex-col justify-between gap-3 border-b border-slate-100/80 p-6 sm:flex-row sm:items-center dark:border-slate-800">
        <div>
          <h3 class="text-lg font-semibold tracking-tight text-slate-700 dark:text-white">Riwayat Klasifikasi Terbaru</h3>
          <p class="mt-0.5 text-xs text-slate-400">Data klasifikasi pelanggan terakhir yang diproses oleh model.</p>
        </div>
        <a
          href="{{ route('prediction.history.page') }}"
          class="inline-flex h-9 items-center gap-1.5 rounded-xl bg-blue-600 px-4 text-xs font-semibold text-white shadow-xs transition hover:bg-blue-700 active:scale-[0.99] dark:shadow-none"
        >
          <span>Lihat Semua Riwayat</span>
          <i data-lucide="arrow-right" class="h-3.5 w-3.5"></i>
        </a>
      </div>

      @include('partials.history-table', ['history' => $recentHistory])
    </section>

    <!-- System Architecture & Technical Health Info -->
    <section class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
      <div class="rounded-2xl border border-transparent bg-white p-4 shadow-xs dark:bg-slate-900">
        <span class="text-[10px] font-medium uppercase tracking-wider text-slate-400">Web Application</span>
        <div class="mt-1.5 flex items-center justify-between">
          <span class="font-semibold text-slate-700 text-xs dark:text-white">Laravel 11.x</span>
          <span class="rounded-lg bg-blue-50 px-2 py-0.5 text-[10px] font-medium text-blue-600 dark:bg-blue-950/60 dark:text-blue-400">Blade + Alpine</span>
        </div>
      </div>

      <div class="rounded-2xl border border-transparent bg-white p-4 shadow-xs dark:bg-slate-900">
        <span class="text-[10px] font-medium uppercase tracking-wider text-slate-400">Microservice ML</span>
        <div class="mt-1.5 flex items-center justify-between">
          <span class="font-semibold text-slate-700 text-xs dark:text-white">FastAPI Python</span>
          <span class="rounded-lg bg-emerald-50 px-2 py-0.5 text-[10px] font-medium text-emerald-600 dark:bg-emerald-950/60 dark:text-emerald-400">Port :5001</span>
        </div>
      </div>

      <div class="rounded-2xl border border-transparent bg-white p-4 shadow-xs dark:bg-slate-900">
        <span class="text-[10px] font-medium uppercase tracking-wider text-slate-400">Machine Learning</span>
        <div class="mt-1.5 flex items-center justify-between">
          <span class="font-semibold text-slate-700 text-xs dark:text-white">Random Forest</span>
          <span class="rounded-lg bg-indigo-50 px-2 py-0.5 text-[10px] font-medium text-indigo-600 dark:bg-indigo-950/60 dark:text-indigo-400">PSO Optimized</span>
        </div>
      </div>

      <div class="rounded-2xl border border-transparent bg-white p-4 shadow-xs dark:bg-slate-900">
        <span class="text-[10px] font-medium uppercase tracking-wider text-slate-400">Basis Data</span>
        <div class="mt-1.5 flex items-center justify-between">
          <span class="font-semibold text-slate-700 text-xs dark:text-white">SQLite Database</span>
          <span class="rounded-lg bg-slate-50 px-2 py-0.5 text-[10px] font-medium text-slate-500 dark:bg-slate-800 dark:text-slate-400">{{ $metrics['total'] ?? 0 }} Records</span>
        </div>
      </div>
    </section>
  </div>

  <!-- Chart.js & Alpine Integration Scripts -->
  <script>
    function dashboardApp(config) {
      return {
        simulatorOpen: false,
        activeDataset: 'all',
        hasTrendData: config.dailyTrend && config.dailyTrend.length > 0,
        trendChartInstance: null,
        riskChartInstance: null,

        // Simulator State
        simCsCalls: 1,
        simDayMinutes: 180,
        simAccountLength: 100,
        simIntlPlan: false,
        simVmailPlan: false,
        simProb: 18.5,
        simRiskLevel: 'Rendah',
        simResult: 'Tidak Churn',
        simAdvice: 'Pelanggan berada pada zona aman dengan interaksi dukungan pelanggan yang minim.',

        init() {
          this.$nextTick(() => {
            this.calcSimRisk();
            this.renderCharts();
          });
        },

        calcSimRisk() {
          let score = 12.0;

          if (this.simCsCalls >= 4) {
            score += 35 + (this.simCsCalls - 4) * 10;
          } else if (this.simCsCalls >= 2) {
            score += this.simCsCalls * 6;
          }

          if (this.simDayMinutes > 250) {
            score += 22;
          } else if (this.simDayMinutes > 200) {
            score += 10;
          } else if (this.simDayMinutes < 100) {
            score += 8;
          }

          if (this.simIntlPlan) {
            score += 28;
          }

          if (this.simVmailPlan) {
            score -= 10;
          }

          if (this.simAccountLength < 30) {
            score += 6;
          }

          score = Math.min(98.5, Math.max(4.2, score));
          this.simProb = score;

          if (score >= 60) {
            this.simRiskLevel = 'Tinggi';
            this.simResult = 'Churn (Risiko Tinggi)';
            this.simAdvice = 'Peringatan: Pelanggan terindikasi sangat mungkin churn. Disarankan intervensi langsung, audit keluhan CS, atau penawaran promo retensi.';
          } else if (score >= 35) {
            this.simRiskLevel = 'Sedang';
            this.simResult = 'Waspada (Risiko Sedang)';
            this.simAdvice = 'Pelanggan menunjukkan indikasi risiko moderat. Pantau kualitas panggilan dan kepuasan layanan secara berkala.';
          } else {
            this.simRiskLevel = 'Rendah';
            this.simResult = 'Aman (Tidak Churn)';
            this.simAdvice = 'Pelanggan berada pada zona loyalitas aman dengan tingkat penggunaan dan kepuasan yang stabil.';
          }
        },

        renderCharts() {
          if (!window.Chart) return;

          // Set Poppins as global font for all Chart.js instances
          window.Chart.defaults.font.family = 'Poppins';
          window.Chart.defaults.font.weight = '400';

          // 1. Render Daily Trend Chart
          const trendCanvas = document.getElementById('dailyTrendChart');
          if (trendCanvas && config.dailyTrend && config.dailyTrend.length > 0) {
            const labels = config.dailyTrend.map(d => d.label);
            const totalData = config.dailyTrend.map(d => d.total);
            const churnData = config.dailyTrend.map(d => d.churn);

            if (this.trendChartInstance) {
              this.trendChartInstance.destroy();
            }

            const ctx = trendCanvas.getContext('2d');
            const totalGradient = ctx.createLinearGradient(0, 0, 0, 240);
            totalGradient.addColorStop(0, 'rgba(37, 99, 235, 0.15)');
            totalGradient.addColorStop(1, 'rgba(37, 99, 235, 0.00)');

            const churnGradient = ctx.createLinearGradient(0, 0, 0, 240);
            churnGradient.addColorStop(0, 'rgba(244, 63, 94, 0.18)');
            churnGradient.addColorStop(1, 'rgba(244, 63, 94, 0.00)');

            this.trendChartInstance = new window.Chart(ctx, {
              type: 'line',
              data: {
                labels: labels,
                datasets: [
                  {
                    label: 'Total Prediksi',
                    data: totalData,
                    borderColor: '#2563EB',
                    backgroundColor: totalGradient,
                    fill: true,
                    tension: 0.35,
                    borderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#2563EB',
                  },
                  {
                    label: 'Prediksi Churn',
                    data: churnData,
                    borderColor: '#F43F5E',
                    backgroundColor: churnGradient,
                    fill: true,
                    tension: 0.35,
                    borderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#F43F5E',
                  }
                ]
              },
              options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                  legend: {
                    display: true,
                    position: 'top',
                    align: 'end',
                    labels: {
                      boxWidth: 10,
                      boxHeight: 10,
                      usePointStyle: true,
                      font: { size: 11, family: 'Poppins', weight: '500' }
                    }
                  },
                  tooltip: {
                    padding: 10,
                    cornerRadius: 10,
                    titleFont: { size: 12, weight: '600', family: 'Poppins' },
                    bodyFont: { size: 11, family: 'Poppins' }
                  }
                },
                scales: {
                  x: {
                    grid: { display: false },
                    ticks: { font: { size: 11, family: 'Poppins' } }
                  },
                  y: {
                    beginAtZero: true,
                    grid: { color: '#F1F5F9' },
                    ticks: { stepSize: 1, font: { size: 11, family: 'Poppins' } }
                  }
                }
              }
            });
          }

          // 2. Render Risk Distribution Doughnut Chart
          const riskCanvas = document.getElementById('riskDistributionChart');
          if (riskCanvas && config.riskDistribution) {
            const counts = config.riskDistribution.map(d => d.count);
            const colors = ['#10B981', '#F59E0B', '#F43F5E'];

            if (this.riskChartInstance) {
              this.riskChartInstance.destroy();
            }

            const total = counts.reduce((a, b) => a + b, 0);
            const datasetCounts = total === 0 ? [1, 0, 0] : counts;
            const datasetColors = total === 0 ? ['#E2E8F0', '#E2E8F0', '#E2E8F0'] : colors;

            this.riskChartInstance = new window.Chart(riskCanvas.getContext('2d'), {
              type: 'doughnut',
              data: {
                labels: ['Risiko Rendah', 'Risiko Sedang', 'Risiko Tinggi'],
                datasets: [{
                  data: datasetCounts,
                  backgroundColor: datasetColors,
                  borderWidth: 2,
                  borderColor: '#ffffff',
                  hoverOffset: 4
                }]
              },
              options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '74%',
                plugins: {
                  legend: { display: false },
                  tooltip: {
                    callbacks: {
                      label: function(context) {
                        return ' ' + context.label + ': ' + context.raw + ' pelanggan';
                      }
                    }
                  }
                }
              }
            });
          }
        },

        toggleChartDataset(type) {
          this.activeDataset = type;
          if (!this.trendChartInstance) return;

          if (type === 'churn') {
            this.trendChartInstance.data.datasets[0].hidden = true;
            this.trendChartInstance.data.datasets[1].hidden = false;
          } else {
            this.trendChartInstance.data.datasets[0].hidden = false;
            this.trendChartInstance.data.datasets[1].hidden = false;
          }
          this.trendChartInstance.update();
        },

        exportCsv() {
          if (!config.recentHistory || config.recentHistory.length === 0) {
            alert('Tidak ada riwayat data untuk diexport.');
            return;
          }

          const headers = ['ID', 'Tanggal', 'Hasil Klasifikasi', 'Probabilitas (%)', 'Tingkat Risiko', 'Area Code', 'CS Calls', 'Day Minutes'];
          const rows = config.recentHistory.map(item => [
            item.id,
            `"${item.timestamp}"`,
            `"${item.result}"`,
            item.probability,
            `"${item.risiko}"`,
            item.area_code,
            item.customer_service_calls,
            item.total_day_minutes
          ]);

          const csvContent = 'data:text/csv;charset=utf-8,' + [headers.join(','), ...rows.map(e => e.join(','))].join('\n');
          const encodedUri = encodeURI(csvContent);
          const link = document.createElement('a');
          link.setAttribute('href', encodedUri);
          link.setAttribute('download', `churn_prediction_summary_${new Date().toISOString().slice(0, 10)}.csv`);
          document.body.appendChild(link);
          link.click();
          document.body.removeChild(link);
        }
      };
    }
  </script>
@endsection
