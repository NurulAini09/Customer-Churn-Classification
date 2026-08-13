@extends('layouts.app')

@section('title', 'Riwayat Klasifikasi - ChurnPredict AI')
@section('page-title', 'Riwayat Klasifikasi')

@section('content')
  <div class="sticky top-0 z-20 -mx-5 mb-3 flex flex-col gap-3 bg-[#F3F6FA] px-5 py-3 sm:-mx-6 sm:flex-row sm:items-center sm:justify-between sm:px-6">
    <div>
      <h2 class="text-xl font-bold text-[#102A43]">Riwayat Klasifikasi</h2>
      <p class="mt-1 text-xs font-medium text-[#64748B]">Data tersimpan dari hasil klasifikasi</p>
    </div>
    <div class="flex flex-wrap gap-2">
      <a href="{{ route('prediction.page') }}" class="inline-flex h-9 items-center gap-2 rounded-md bg-[#DB5A8D] px-3.5 text-xs font-semibold text-white transition hover:bg-[#C84A7B]">
        <i data-lucide="plus" class="h-3.5 w-3.5"></i>
        Klasifikasi Baru
      </a>
      <form method="POST" action="{{ route('prediction.history.clear') }}">
        @csrf
        <button type="submit" class="inline-flex h-9 items-center gap-2 rounded-md border border-[#e7b7b7] bg-white px-3.5 text-xs font-semibold text-[#b73535] transition hover:bg-[#fff4f4]" onclick="return confirm('Hapus seluruh riwayat klasifikasi?');">
          <i data-lucide="trash-2" class="h-3.5 w-3.5"></i>
          Hapus Semua Riwayat
        </button>
      </form>
    </div>
  </div>

  @if (session('success_message'))
    <div class="mb-3 rounded-md border border-[#F6C7DA] bg-[#FFF0F6] px-4 py-3 text-[13px] font-medium text-[#DB5A8D]">
      {{ session('success_message') }}
    </div>
  @endif

  <section class="rounded-md border border-[#D8E2EC] bg-white">
    <div class="border-b border-[#D8E2EC] px-4 py-3">
      <h3 class="text-base font-semibold text-[#102A43]">Tabel Riwayat</h3>
      <p class="mt-1 text-xs text-[#64748B]">Riwayat klasifikasi disimpan di database agar bisa ditinjau kembali.</p>
    </div>

    @include('partials.history-table', ['history' => $history])
  </section>
@endsection
