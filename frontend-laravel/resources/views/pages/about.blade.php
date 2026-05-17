@extends('layouts.app')

@section('title', 'Tentang Sistem - ChurnPredict AI')
@section('page-title', 'Tentang Sistem')

@section('content')
  <div class="sticky top-0 z-20 -mx-5 mb-3 bg-[#F3F6FA] px-5 py-3 sm:-mx-6 sm:px-6">
    <h2 class="text-xl font-bold text-[#102A43]">Tentang Sistem</h2>
    <p class="mt-1 text-xs font-medium text-[#64748B]">Arsitektur aplikasi prediksi churn pelanggan</p>
  </div>

  <div class="grid gap-5 xl:grid-cols-[1fr_360px]">
    <section class="rounded-md border border-[#D8E2EC] bg-white">
      <div class="border-b border-[#D8E2EC] px-4 py-3">
        <h3 class="text-base font-semibold text-[#102A43]">Ringkasan</h3>
      </div>
      <div class="p-4">
        <p class="max-w-3xl text-[13px] leading-6 text-[#475569]">
          Sistem ini memisahkan Laravel sebagai aplikasi web utama dan FastAPI sebagai prediction service.
          Laravel menangani halaman, form, hasil prediksi, dan riwayat. FastAPI menangani proses machine learning
          menggunakan model Random Forest yang dioptimasi PSO.
        </p>

        <div class="mt-5 grid gap-3 md:grid-cols-2">
          @foreach ([
            ['title' => 'Laravel Frontend', 'body' => 'Dashboard, form prediksi, routing, dan riwayat prediksi.'],
            ['title' => 'FastAPI Backend', 'body' => 'Endpoint prediksi, validasi payload, dan response JSON.'],
            ['title' => 'ML Layer', 'body' => 'Load model dari folder khusus dan menjalankan prediksi.'],
            ['title' => 'Database', 'body' => 'Menyimpan riwayat prediksi untuk analisis ulang.'],
          ] as $card)
            <div class="rounded-md border border-[#D8E2EC] bg-[#F8FAFC] p-4">
              <h4 class="text-[13px] font-bold text-[#102A43]">{{ $card['title'] }}</h4>
              <p class="mt-2 text-[13px] leading-6 text-[#475569]">{{ $card['body'] }}</p>
            </div>
          @endforeach
        </div>
      </div>
    </section>

    <section class="rounded-md border border-[#D8E2EC] bg-white">
      <div class="border-b border-[#D8E2EC] px-4 py-3">
        <h3 class="text-base font-semibold text-[#102A43]">Alur Kerja</h3>
      </div>
      <div class="space-y-0 p-4">
        @foreach ([
          'User mengisi data pelanggan di Laravel.',
          'Laravel memvalidasi dan mengirim JSON ke FastAPI.',
          'FastAPI menjalankan model Random Forest + PSO.',
          'Laravel menampilkan hasil dan menyimpan riwayat.',
        ] as $index => $step)
          <div class="flex gap-3 border-b border-[#E5EEF8] py-3.5 last:border-b-0">
            <span class="grid h-7 w-7 shrink-0 place-items-center rounded-md bg-[#FFF0F6] text-xs font-bold text-[#DB5A8D]">{{ $index + 1 }}</span>
            <p class="text-[13px] font-medium leading-6 text-[#475569]">{{ $step }}</p>
          </div>
        @endforeach
      </div>
    </section>
  </div>
@endsection
