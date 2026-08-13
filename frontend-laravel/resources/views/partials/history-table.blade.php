@if (count($history))
  <div class="overflow-x-auto" x-data="{ openHistoryId: null }">
    <table class="min-w-full border-collapse text-[13px]">
      <thead>
        <tr class="border-b border-[#D8E2EC] bg-[#F8FAFC] text-left text-[10px] font-semibold uppercase tracking-[0.12em] text-[#64748B]">
          <th class="px-4 py-2.5">Tanggal</th>
          <th class="px-4 py-2.5">Status</th>
          <th class="px-4 py-2.5">Probabilitas</th>
          <th class="px-4 py-2.5">Risiko</th>
          <th class="px-4 py-2.5">Area Code</th>
          <th class="px-4 py-2.5">Service Calls</th>
          <th class="px-4 py-2.5">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($history as $item)
          <tr
            class="cursor-pointer border-b border-[#E5EEF8] text-[#475569] transition hover:bg-[#F8FAFC]"
            x-on:click="openHistoryId = openHistoryId === {{ $item['id'] }} ? null : {{ $item['id'] }}"
          >
            <td class="whitespace-nowrap px-4 py-3 font-medium text-[#102A43]">{{ $item['timestamp'] }}</td>
            <td class="px-4 py-3">
              <span class="{{ $item['result'] === 'Churn' ? 'border-[#F1B8B8] bg-[#FFF5F5] text-[#B73535]' : 'border-[#F6C7DA] bg-[#FFF0F6] text-[#DB5A8D]' }} rounded border px-2 py-0.5 text-[11px] font-semibold">{{ $item['result'] }}</span>
            </td>
            <td class="px-4 py-3 font-semibold text-[#102A43]">{{ $item['probability'] }}%</td>
            <td class="px-4 py-3">{{ $item['risiko'] }}</td>
            <td class="px-4 py-3">{{ $item['area_code'] }}</td>
            <td class="px-4 py-3">{{ $item['customer_service_calls'] }}</td>
            <td class="px-4 py-3" x-on:click.stop>
              <div class="flex items-center gap-2">
                <button
                  type="button"
                  class="inline-flex h-8 items-center gap-1.5 rounded-md border border-[#D8E2EC] bg-white px-3 text-[11px] font-semibold text-[#475569] transition hover:bg-[#F8FAFC]"
                  x-on:click="openHistoryId = openHistoryId === {{ $item['id'] }} ? null : {{ $item['id'] }}"
                >
                  <i data-lucide="chevrons-up-down" class="h-3.5 w-3.5"></i>
                  Lihat Hasil
                </button>
                <form method="POST" action="{{ route('prediction.history.delete', $item['id']) }}" onsubmit="return confirm('Hapus riwayat klasifikasi ini?');">
                  @csrf
                  <button type="submit" class="inline-flex h-8 items-center gap-1.5 rounded-md border border-[#e7b7b7] bg-white px-3 text-[11px] font-semibold text-[#b73535] transition hover:bg-[#fff4f4]">
                    <i data-lucide="trash-2" class="h-3.5 w-3.5"></i>
                    Hapus
                  </button>
                </form>
              </div>
            </td>
          </tr>
          <tr x-show="openHistoryId === {{ $item['id'] }}" x-transition class="border-b border-[#E5EEF8] bg-[#FCFDFE] last:border-b-0">
            <td colspan="7" class="px-4 py-4">
              <div class="grid gap-4 lg:grid-cols-[220px_1fr]">
                <div class="rounded-md border border-[#D8E2EC] bg-white p-4">
                  <p class="text-[11px] font-semibold uppercase tracking-[0.12em] text-[#64748B]">Hasil Klasifikasi</p>
                  <div class="mt-3 flex items-center gap-3">
                    <span class="{{ $item['result'] === 'Churn' ? 'border-[#F1B8B8] bg-[#FFF5F5] text-[#B73535]' : 'border-[#F6C7DA] bg-[#FFF0F6] text-[#DB5A8D]' }} inline-flex rounded border px-2.5 py-1 text-xs font-semibold">{{ $item['result'] }}</span>
                    <span class="text-sm font-semibold text-[#102A43]">{{ $item['probability'] }}%</span>
                  </div>
                  <p class="mt-3 text-sm leading-6 text-[#475569]">{{ $item['description'] }}</p>
                  <div class="mt-3 space-y-1 text-[12px] text-[#64748B]">
                    <p><span class="font-semibold text-[#102A43]">Risiko:</span> {{ $item['risiko'] }}</p>
                    <p><span class="font-semibold text-[#102A43]">Confidence:</span> {{ $item['confidence_label'] }}</p>
                  </div>
                </div>

                <div class="rounded-md border border-[#D8E2EC] bg-white p-4">
                  <p class="text-[11px] font-semibold uppercase tracking-[0.12em] text-[#64748B]">Ringkasan Input</p>
                  <div class="mt-3 grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
                    <div class="rounded-md border border-[#E5EEF8] bg-[#F8FAFC] px-3 py-2">
                      <p class="text-[11px] text-[#64748B]">Account Length</p>
                      <p class="mt-1 text-sm font-semibold text-[#102A43]">{{ $item['account_length'] }}</p>
                    </div>
                    <div class="rounded-md border border-[#E5EEF8] bg-[#F8FAFC] px-3 py-2">
                      <p class="text-[11px] text-[#64748B]">International Plan</p>
                      <p class="mt-1 text-sm font-semibold text-[#102A43]">{{ $item['international_plan'] }}</p>
                    </div>
                    <div class="rounded-md border border-[#E5EEF8] bg-[#F8FAFC] px-3 py-2">
                      <p class="text-[11px] text-[#64748B]">Voice Mail Plan</p>
                      <p class="mt-1 text-sm font-semibold text-[#102A43]">{{ $item['voice_mail_plan'] }}</p>
                    </div>
                    <div class="rounded-md border border-[#E5EEF8] bg-[#F8FAFC] px-3 py-2">
                      <p class="text-[11px] text-[#64748B]">Total Day Minutes</p>
                      <p class="mt-1 text-sm font-semibold text-[#102A43]">{{ $item['total_day_minutes'] }}</p>
                    </div>
                    <div class="rounded-md border border-[#E5EEF8] bg-[#F8FAFC] px-3 py-2">
                      <p class="text-[11px] text-[#64748B]">Total Eve Minutes</p>
                      <p class="mt-1 text-sm font-semibold text-[#102A43]">{{ $item['total_eve_minutes'] }}</p>
                    </div>
                    <div class="rounded-md border border-[#E5EEF8] bg-[#F8FAFC] px-3 py-2">
                      <p class="text-[11px] text-[#64748B]">Total Night Minutes</p>
                      <p class="mt-1 text-sm font-semibold text-[#102A43]">{{ $item['total_night_minutes'] }}</p>
                    </div>
                    <div class="rounded-md border border-[#E5EEF8] bg-[#F8FAFC] px-3 py-2">
                      <p class="text-[11px] text-[#64748B]">Total Intl Minutes</p>
                      <p class="mt-1 text-sm font-semibold text-[#102A43]">{{ $item['total_intl_minutes'] }}</p>
                    </div>
                    <div class="rounded-md border border-[#E5EEF8] bg-[#F8FAFC] px-3 py-2">
                      <p class="text-[11px] text-[#64748B]">Area Code</p>
                      <p class="mt-1 text-sm font-semibold text-[#102A43]">{{ $item['area_code'] }}</p>
                    </div>
                    <div class="rounded-md border border-[#E5EEF8] bg-[#F8FAFC] px-3 py-2">
                      <p class="text-[11px] text-[#64748B]">Customer Service Calls</p>
                      <p class="mt-1 text-sm font-semibold text-[#102A43]">{{ $item['customer_service_calls'] }}</p>
                    </div>
                  </div>
                </div>
              </div>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@else
  <div class="m-4 grid min-h-28 place-items-center rounded-md border border-dashed border-[#D8E2EC] bg-[#F8FAFC] p-6 text-center text-sm font-medium text-[#64748B]">
    Belum ada riwayat klasifikasi.
  </div>
@endif
