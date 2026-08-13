@extends('layouts.app')

@section('title', 'Klasifikasi Churn - ChurnPredict AI')
@section('page-title', 'Klasifikasi Churn')

@section('content')
  @php
    $hasResult = !empty($resultData);
    $isChurn = $hasResult && ($resultData['result'] === 'Churn');
    $probability = $hasResult ? (float) $resultData['probability'] : 0;
    $groupedFields = collect($formFields)->groupBy('group');
  @endphp

  <div class="sticky top-0 z-20 -mx-5 mb-3 flex flex-col gap-3 bg-[#F3F6FA] px-5 py-3 sm:-mx-6 sm:flex-row sm:items-center sm:justify-between sm:px-6">
    <div>
      <h2 class="text-xl font-bold text-[#102A43]">Klasifikasi Churn</h2>
      <p class="mt-1 text-xs font-medium text-[#64748B]">Kirim data pelanggan ke FastAPI</p>
    </div>
    <div class="flex flex-wrap gap-2">
      <a href="{{ route('prediction.reset') }}" class="inline-flex h-9 items-center gap-2 rounded-md border border-[#D8E2EC] bg-white px-3.5 text-xs font-semibold text-[#475569]">
        <i data-lucide="rotate-ccw" class="h-4 w-4"></i>
        Reset
      </a>
      <button form="prediction-form" type="submit" class="inline-flex h-9 items-center gap-2 rounded-md bg-[#DB5A8D] px-3.5 text-xs font-semibold text-white transition hover:bg-[#C84A7B]">
        <i data-lucide="send" class="h-4 w-4"></i>
        Jalankan Klasifikasi
      </button>
    </div>
  </div>

  <div class="grid gap-5 xl:grid-cols-[1fr_390px]">
    <section class="rounded-md border border-[#D8E2EC] bg-white">
      <div class="border-b border-[#D8E2EC] px-5 py-4">
        <h3 class="text-base font-semibold text-[#102A43]">Input Data</h3>
        <p class="mt-1 text-xs text-[#64748B]">Isi variabel pelanggan yang dibutuhkan model.</p>
      </div>

      <div class="p-5">
        @if ($errorMessage)
          <div class="mb-4 rounded-md border border-[#f1b8b8] bg-[#fff1f1] p-3 text-sm font-medium leading-6 text-[#c22535]">{{ $errorMessage }}</div>
        @endif

        @if ($errors->any())
          <div class="mb-4 rounded-md border border-[#e7d599] bg-[#fff9df] p-3 text-sm text-[#886b14]">
            <p class="font-bold">Input belum valid.</p>
            <ul class="mt-2 list-inside list-disc leading-6">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form id="prediction-form" method="POST" action="{{ route('prediction.predict') }}" x-data="{ loading: false }" x-on:submit="loading = true" class="space-y-4">
          @csrf

          @foreach ($groupedFields as $group => $fields)
            <fieldset class="rounded-md border border-[#D8E2EC] bg-[#F8FAFC]">
              <legend class="ml-4 bg-white px-2.5 text-[11px] font-bold uppercase tracking-[0.14em] text-[#DB5A8D]">{{ $group }}</legend>
              <div class="grid gap-3 p-4 md:grid-cols-2">
                @foreach ($fields as $field)
                  <label class="block">
                    <span class="mb-1.5 block text-xs font-semibold text-[#475569]">{{ $field['label'] }}</span>
                    @if ($field['type'] === 'select')
                      <select name="{{ $field['name'] }}" class="h-10 w-full rounded-md border border-[#D8E2EC] bg-white px-3 text-sm font-medium text-[#102A43] outline-none transition focus:border-[#DB5A8D]">
                        <option value="0" @selected($formValues[$field['name']] == '0')>No</option>
                        <option value="1" @selected($formValues[$field['name']] == '1')>Yes</option>
                      </select>
                    @else
                      <input name="{{ $field['name'] }}" type="number" min="0" step="{{ $field['step'] }}" value="{{ $formValues[$field['name']] }}" required class="h-10 w-full rounded-md border border-[#D8E2EC] bg-white px-3 text-sm font-medium text-[#102A43] outline-none transition focus:border-[#DB5A8D]">
                    @endif
                  </label>
                @endforeach
              </div>
            </fieldset>
          @endforeach
        </form>
      </div>
    </section>

    <div class="space-y-5">
      <section class="rounded-md border border-[#D8E2EC] bg-white">
        <div class="border-b border-[#D8E2EC] px-5 py-4">
          <h3 class="text-base font-semibold text-[#102A43]">Hasil Klasifikasi</h3>
          <p class="mt-1 text-xs text-[#64748B]">Output model dan risk scoring.</p>
        </div>
        <div class="p-5">
          @if ($hasResult)
            <div class="text-center">
              <div class="mx-auto mb-4 grid h-28 w-28 place-items-center rounded-full" style="background: conic-gradient({{ $isChurn ? '#d74646' : '#DB5A8D' }} {{ max(5, min(100, $probability)) }}%, #E5EEF8 0);">
                <div class="grid h-20 w-20 place-items-center rounded-full bg-white">
                  <div>
                    <div class="text-xl font-bold text-[#102A43]">{{ number_format($probability, 1) }}%</div>
                    <div class="text-xs uppercase tracking-[0.12em] text-[#64748B]">Risk</div>
                  </div>
                </div>
              </div>
              <p class="{{ $isChurn ? 'text-[#d74646]' : 'text-[#DB5A8D]' }} text-xl font-bold uppercase">{{ $resultData['result'] }}</p>
              <p class="mt-3 text-sm leading-6 text-[#475569]">{{ $resultData['keterangan'] }}</p>
            </div>
          @else
            <div class="grid min-h-36 place-items-center rounded-md border border-dashed border-[#D8E2EC] bg-[#F8FAFC] p-6 text-center text-sm font-medium leading-6 text-[#64748B]">
              Hasil klasifikasi akan tampil setelah data pelanggan diproses.
            </div>
          @endif
        </div>
      </section>

      <section class="rounded-md border border-[#D8E2EC] bg-white">
        <div class="border-b border-[#D8E2EC] px-5 py-4">
          <h3 class="text-base font-semibold text-[#102A43]">Faktor Dominan</h3>
          <p class="mt-1 text-xs text-[#64748B]">Feature importance dari model.</p>
        </div>
        <div class="p-5">
          @if (!empty($resultData['top_factors']))
            <div class="space-y-5">
              @foreach ($resultData['top_factors'] as $factor)
                <div>
                  <div class="mb-2 flex justify-between gap-4 text-sm font-semibold">
                    <span class="text-[#475569]">{{ $factor['label'] }}</span>
                    <span class="text-[#102A43]">{{ $factor['importance_percentage'] }}%</span>
                  </div>
                  <div class="h-1.5 rounded-full bg-[#E5EEF8]">
                    <div class="h-1.5 rounded-full bg-[#DB5A8D]" style="width: {{ min(max((float) $factor['importance_percentage'], 0), 100) }}%"></div>
                  </div>
                </div>
              @endforeach
            </div>
          @else
            <div class="rounded-md border border-dashed border-[#D8E2EC] bg-[#F8FAFC] p-5 text-sm font-medium leading-6 text-[#64748B]">Faktor pengaruh model akan tampil setelah hasil klasifikasi tersedia.</div>
          @endif
        </div>
      </section>
    </div>
  </div>
@endsection
