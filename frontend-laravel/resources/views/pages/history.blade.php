@extends('layouts.app')

@section('title', 'Riwayat Klasifikasi - ChurnPredict AI')
@section('page-title', 'Riwayat Klasifikasi')

@section('content')
  @if (session('success_message'))
    <div class="mb-4 flex items-center gap-2 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-xs font-medium text-emerald-800 dark:border-emerald-900/60 dark:bg-emerald-950/40 dark:text-emerald-300">
      <i data-lucide="check-circle" class="h-4 w-4 text-emerald-600 dark:text-emerald-400"></i>
      <span>{{ session('success_message') }}</span>
    </div>
  @endif

  <!-- Main Data Table Container -->
  <section
    class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-xs dark:border-slate-800 dark:bg-slate-900"
    x-data="{
      autoRefresh: true,
      countdown: 30,
      lastUpdated: '',
      isRefreshing: false,
      timer: null,
      countdownTimer: null,

      init() {
        this.lastUpdated = this.nowWib();
        this.startCountdown();
      },

      nowWib() {
        return new Date().toLocaleTimeString('id-ID', { timeZone: 'Asia/Jakarta', hour: '2-digit', minute: '2-digit', second: '2-digit' }) + ' WIB';
      },

      startCountdown() {
        this.countdown = 30;
        clearInterval(this.countdownTimer);
        this.countdownTimer = setInterval(() => {
          this.countdown--;
          if (this.countdown <= 0) {
            clearInterval(this.countdownTimer);
            if (this.autoRefresh) this.fetchHistory();
          }
        }, 1000);
      },

      async fetchHistory() {
        if (this.isRefreshing) return;
        this.isRefreshing = true;
        try {
          const res = await fetch('{{ route('prediction.history.json') }}', {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
          });
          if (!res.ok) throw new Error('Fetch failed');
          const data = await res.json();
          window.dispatchEvent(new CustomEvent('history-updated', { detail: { history: data.history } }));
          this.lastUpdated = this.nowWib();
        } catch(e) {
          console.warn('Auto-refresh gagal:', e);
        } finally {
          this.isRefreshing = false;
          if (this.autoRefresh) this.startCountdown();
        }
      },

      toggleAutoRefresh() {
        this.autoRefresh = !this.autoRefresh;
        if (this.autoRefresh) {
          this.startCountdown();
        } else {
          clearInterval(this.countdownTimer);
        }
      }
    }"
    x-init="init()"
  >
    <!-- Top Header Bar -->
    <div class="flex flex-col justify-between gap-3 border-b border-slate-200/80 p-6 sm:flex-row sm:items-center dark:border-slate-800">
      <div>
        <h1 class="text-xl font-semibold tracking-tight text-slate-700 dark:text-white">Riwayat Klasifikasi</h1>
        <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">Pantau dan audit riwayat prediksi pelanggan secara real-time</p>
      </div>

      <div class="flex flex-wrap items-center gap-2">
        <!-- Live Indicator + Countdown -->
        <div class="flex items-center gap-2 rounded-xl border border-slate-200/80 bg-slate-50 px-3 py-1.5 text-xs dark:border-slate-700 dark:bg-slate-800">
          <!-- Dot animasi -->
          <span class="relative flex h-2 w-2">
            <span
              x-show="autoRefresh && !isRefreshing"
              class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"
            ></span>
            <span
              x-bind:class="isRefreshing ? 'bg-amber-400' : (autoRefresh ? 'bg-emerald-500' : 'bg-slate-400')"
              class="relative inline-flex h-2 w-2 rounded-full"
            ></span>
          </span>
          <span
            x-bind:class="autoRefresh ? 'text-emerald-700 dark:text-emerald-400' : 'text-slate-500 dark:text-slate-400'"
            class="font-semibold"
          >
            <span x-show="isRefreshing">Memperbarui...</span>
            <span x-show="!isRefreshing && autoRefresh">Live · refresh dalam <span x-text="countdown" class="tabular-nums"></span>s</span>
            <span x-show="!isRefreshing && !autoRefresh">Diperbarui: <span x-text="lastUpdated"></span></span>
          </span>
        </div>

        <!-- Toggle Auto-refresh -->
        <button
          type="button"
          x-on:click="toggleAutoRefresh()"
          x-bind:class="autoRefresh ? 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-800/60 dark:bg-emerald-950/40 dark:text-emerald-400' : 'border-slate-200/80 bg-white text-slate-600 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300'"
          class="inline-flex h-9 items-center gap-1.5 rounded-xl border px-3 text-xs font-semibold transition hover:opacity-80"
          title="Toggle auto-refresh"
        >
          <i data-lucide="refresh-cw" class="h-3.5 w-3.5" x-bind:class="isRefreshing ? 'animate-spin' : ''"></i>
          <span x-text="autoRefresh ? 'Auto ON' : 'Auto OFF'"></span>
        </button>

        <!-- Manual Refresh -->
        <button
          type="button"
          x-on:click="fetchHistory()"
          x-bind:disabled="isRefreshing"
          class="inline-flex h-9 items-center gap-1.5 rounded-xl border border-slate-200/80 bg-white px-3 text-xs font-semibold text-slate-600 transition hover:bg-slate-50 disabled:opacity-50 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300"
          title="Refresh sekarang"
        >
          <i data-lucide="rotate-cw" class="h-3.5 w-3.5"></i>
          Refresh
        </button>

        <a href="{{ route('prediction.page') }}" class="inline-flex h-9 items-center gap-2 rounded-xl bg-blue-600 px-4 text-xs font-semibold text-white shadow-xs transition hover:bg-blue-700 active:scale-[0.99] dark:shadow-none">
          <i data-lucide="plus" class="h-3.5 w-3.5"></i>
          Tambah Klasifikasi
        </a>
        <form method="POST" action="{{ route('prediction.history.clear') }}" class="m-0">
          @csrf
          <button type="submit" class="inline-flex h-9 items-center gap-1.5 rounded-xl border border-slate-200/80 bg-white px-3 text-xs font-semibold text-rose-600 transition hover:bg-rose-50 dark:border-slate-700 dark:bg-slate-800 dark:text-rose-400 dark:hover:bg-rose-950/40" onclick="return confirm('Hapus seluruh riwayat klasifikasi?');">
            <i data-lucide="trash-2" class="h-3.5 w-3.5"></i>
            Hapus Semua
          </button>
        </form>
      </div>
    </div>

    @include('partials.history-table', ['history' => $history])
  </section>
@endsection

