@extends('layouts.app')

@section('title', 'Informasi Model - ChurnPredict AI')
@section('page-title', 'Informasi Model')

@section('content')
  <div class="sticky top-0 z-20 -mx-5 mb-3 flex flex-col gap-3 bg-[#F3F6FA] px-5 py-3 sm:-mx-6 sm:flex-row sm:items-center sm:justify-between sm:px-6">
    <div>
      <h2 class="text-xl font-bold text-[#102A43]">Informasi Model</h2>
      <p class="mt-1 text-xs font-medium text-[#64748B]">Random Forest + PSO</p>
    </div>
    <a href="http://127.0.0.1:5001/docs" target="_blank" class="inline-flex h-9 items-center gap-2 rounded-md border border-[#D8E2EC] bg-white px-3.5 text-xs font-semibold text-[#475569] transition hover:bg-[#F8FAFC]">
      <i data-lucide="external-link" class="h-3.5 w-3.5"></i>
      API Docs
    </a>
  </div>

  <div class="grid gap-5 xl:grid-cols-[1fr_360px]">
    <section class="rounded-md border border-[#D8E2EC] bg-white">
      <div class="border-b border-[#D8E2EC] px-4 py-3">
        <h3 class="text-base font-semibold text-[#102A43]">Spesifikasi Model</h3>
        <p class="mt-1 text-xs text-[#64748B]">Informasi inti model dan integrasi service.</p>
      </div>
      <dl class="divide-y divide-[#E5EEF8]">
        @foreach ([
          'Model' => 'Random Forest Classifier',
          'Optimasi' => 'Particle Swarm Optimization (PSO)',
          'Fitur Input' => '18 variabel pelanggan telekomunikasi',
          'Output' => 'Status churn, probabilitas, level risiko, dan faktor dominan',
          'Service' => 'FastAPI + scikit-learn + joblib',
          'Integrasi' => 'Laravel HTTP client ke endpoint /predict',
        ] as $label => $value)
          <div class="grid gap-2 px-4 py-3.5 sm:grid-cols-[160px_1fr]">
            <dt class="text-[13px] font-semibold text-[#64748B]">{{ $label }}</dt>
            <dd class="text-[13px] font-semibold text-[#102A43]">{{ $value }}</dd>
          </div>
        @endforeach
      </dl>
    </section>

    <section class="rounded-md border border-[#D8E2EC] bg-white">
      <div class="border-b border-[#D8E2EC] px-4 py-3">
        <h3 class="text-base font-semibold text-[#102A43]">API Contract</h3>
        <p class="mt-1 text-xs text-[#64748B]">Contoh request dan response.</p>
      </div>
      <div class="space-y-3 p-4">
        <div class="rounded-md border border-[#D8E2EC] bg-[#F8FAFC] p-3 text-[11px] leading-5 text-[#475569]">
<pre class="overflow-x-auto"><code>POST /predict
{
  "account_length": 128,
  "area_code": 415,
  "customer_service_calls": 2
}</code></pre>
        </div>
        <div class="rounded-md border border-[#D8E2EC] bg-[#F8FAFC] p-3 text-[11px] leading-5 text-[#475569]">
<pre class="overflow-x-auto"><code>{
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
