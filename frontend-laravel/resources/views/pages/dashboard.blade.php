@extends('layouts.app')

@section('title', 'Dashboard - ChurnPredict AI')
@section('page-title', 'Dashboard')

@section('content')
  @php
    $stats = $dashboard['stats'];
    $statMeta = [
      ['code' => 'PRED', 'tone' => 'border-[#F2AFCB]'],
      ['code' => 'RATE', 'tone' => 'border-[#64748B]'],
      ['code' => 'AVG', 'tone' => 'border-[#F2AFCB]'],
      ['code' => 'HIGH', 'tone' => 'border-[#C9A6A6]'],
    ];
  @endphp

  <div class="sticky top-0 z-20 -mx-5 mb-3 flex flex-col gap-3 bg-[#F3F6FA] px-5 py-3 sm:-mx-6 sm:flex-row sm:items-center sm:justify-between sm:px-6">
    <div>
      <h2 class="text-[19px] font-semibold tracking-[-0.01em] text-[#102A43]">Dashboard</h2>
      <p class="mt-1 text-[13px] text-[#64748B]">Ringkasan performa prediksi churn dan status integrasi sistem.</p>
    </div>
    <a href="{{ route('prediction.page') }}" class="inline-flex h-9 items-center justify-center gap-2 rounded-md bg-[#DB5A8D] px-3.5 text-xs font-semibold text-white transition hover:bg-[#C84A7B]">
      <i data-lucide="plus" class="h-3.5 w-3.5"></i>
      Prediksi Baru
    </a>
  </div>

  <section class="mb-4 grid overflow-hidden rounded-md border border-[#D8E2EC] bg-white sm:grid-cols-2 xl:grid-cols-4">
    @foreach ($stats as $index => $stat)
      <div class="{{ $statMeta[$index]['tone'] ?? 'border-[#D8E2EC]' }} border-b border-l-2 border-r border-[#E5EEF8] bg-white p-4 last:border-r-0 xl:border-b-0">
        <div class="mb-3 flex items-center justify-between gap-3">
          <p class="text-[11px] font-semibold uppercase tracking-[0.08em] text-[#64748B]">{{ $stat['label'] }}</p>
          <span class="rounded border border-[#D8E2EC] bg-[#F8FAFC] px-1.5 py-0.5 text-[10px] font-semibold text-[#64748B]">{{ $statMeta[$index]['code'] ?? 'KPI' }}</span>
        </div>
        <p class="text-[22px] font-semibold leading-none tracking-[-0.02em] text-[#6F3D58]">{{ $stat['value'] }}</p>
        <p class="mt-2 text-[12px] text-[#6B7C93]">{{ $stat['trend'] }}</p>
      </div>
    @endforeach
  </section>

  <div class="grid gap-4 2xl:grid-cols-[minmax(0,1fr)_320px]">
    <section class="min-w-0 rounded-md border border-[#D8E2EC] bg-white">
      <div class="flex items-center justify-between border-b border-[#D8E2EC] px-4 py-3">
        <div>
          <h3 class="text-[15px] font-semibold text-[#102A43]">Riwayat Terbaru</h3>
          <p class="mt-0.5 text-[12px] text-[#64748B]">Data prediksi terakhir yang tersimpan.</p>
        </div>
        <a href="{{ route('prediction.history.page') }}" class="text-[12px] font-semibold text-[#DB5A8D] hover:text-[#C84A7B]">Lihat semua</a>
      </div>
      @include('partials.history-table', ['history' => $dashboard['recentHistory']])
    </section>

    <section class="rounded-md border border-[#D8E2EC] bg-white">
      <div class="border-b border-[#D8E2EC] px-4 py-3">
        <h3 class="text-[15px] font-semibold text-[#102A43]">System Health</h3>
        <p class="mt-0.5 text-[12px] text-[#64748B]">Komponen aplikasi aktif.</p>
      </div>
      <div class="divide-y divide-[#E5EEF8] px-4 text-[13px]">
        <div class="flex items-center justify-between py-3">
          <span class="text-[#64748B]">Web Layer</span>
          <span class="rounded border border-[#F6C7DA] bg-[#FFF0F6] px-2 py-1 text-[11px] font-semibold text-[#DB5A8D]">Laravel</span>
        </div>
        <div class="flex items-center justify-between py-3">
          <span class="text-[#64748B]">Prediction API</span>
          <span class="rounded border border-[#F6C7DA] bg-[#FFF0F6] px-2 py-1 text-[11px] font-semibold text-[#DB5A8D]">FastAPI</span>
        </div>
        <div class="flex items-center justify-between py-3">
          <span class="text-[#64748B]">ML Model</span>
          <span class="text-right text-[12px] font-semibold text-[#102A43]">Random Forest + PSO</span>
        </div>
      </div>
    </section>
  </div>
@endsection
