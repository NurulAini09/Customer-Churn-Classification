@if (count($history))
  <div class="overflow-x-auto">
    <table class="min-w-full border-collapse text-[13px]">
      <thead>
        <tr class="border-b border-[#D8E2EC] bg-[#F8FAFC] text-left text-[10px] font-semibold uppercase tracking-[0.12em] text-[#64748B]">
          <th class="px-4 py-2.5">Waktu</th>
          <th class="px-4 py-2.5">Status</th>
          <th class="px-4 py-2.5">Probabilitas</th>
          <th class="px-4 py-2.5">Risiko</th>
          <th class="px-4 py-2.5">Area Code</th>
          <th class="px-4 py-2.5">Service Calls</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($history as $item)
          <tr class="border-b border-[#E5EEF8] text-[#475569] transition last:border-b-0 hover:bg-[#F8FAFC]">
            <td class="whitespace-nowrap px-4 py-3 font-medium text-[#102A43]">{{ $item['timestamp'] }}</td>
            <td class="px-4 py-3">
              <span class="{{ $item['result'] === 'Churn' ? 'border-[#F1B8B8] bg-[#FFF5F5] text-[#B73535]' : 'border-[#F6C7DA] bg-[#FFF0F6] text-[#DB5A8D]' }} rounded border px-2 py-0.5 text-[11px] font-semibold">{{ $item['result'] }}</span>
            </td>
            <td class="px-4 py-3 font-semibold text-[#102A43]">{{ $item['probability'] }}%</td>
            <td class="px-4 py-3">{{ $item['risiko'] }}</td>
            <td class="px-4 py-3">{{ $item['area_code'] }}</td>
            <td class="px-4 py-3">{{ $item['customer_service_calls'] }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@else
  <div class="m-4 grid min-h-28 place-items-center rounded-md border border-dashed border-[#D8E2EC] bg-[#F8FAFC] p-6 text-center text-sm font-medium text-[#64748B]">
    Belum ada riwayat prediksi.
  </div>
@endif
