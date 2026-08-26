@if (count($history))
  <div
    class="overflow-hidden"
    x-data="{
      rawItems: {{ json_encode($history) }},
      openHistoryId: null,
      searchQuery: '',
      riskFilter: 'all',
      statusFilter: 'all',
      perPage: 10,
      currentPage: 1,

      filteredItems() {
        const query = this.searchQuery.toLowerCase().trim();
        return this.rawItems.filter(item => {
          const matchesQuery = !query || 
            String(item.id).includes(query) ||
            String(item.area_code).includes(query) ||
            (item.result && item.result.toLowerCase().includes(query)) ||
            (item.risiko && item.risiko.toLowerCase().includes(query)) ||
            (item.timestamp && item.timestamp.toLowerCase().includes(query)) ||
            (item.description && item.description.toLowerCase().includes(query));

          const matchesRisk = this.riskFilter === 'all' || 
            (item.risiko && item.risiko.toLowerCase() === this.riskFilter.toLowerCase());
          
          const matchesStatus = this.statusFilter === 'all' || 
            (item.result && item.result.toLowerCase() === this.statusFilter.toLowerCase());

          return matchesQuery && matchesRisk && matchesStatus;
        });
      },

      paginatedItems() {
        const filtered = this.filteredItems();
        const start = (this.currentPage - 1) * this.perPage;
        return filtered.slice(start, start + this.perPage);
      },

      totalPages() {
        return Math.max(1, Math.ceil(this.filteredItems().length / this.perPage));
      },

      startIndex() {
        const filtered = this.filteredItems();
        if (filtered.length === 0) return 0;
        return (this.currentPage - 1) * this.perPage + 1;
      },

      endIndex() {
        const filtered = this.filteredItems();
        return Math.min(this.currentPage * this.perPage, filtered.length);
      },

      totalCount() {
        return this.filteredItems().length;
      },

      prevPage() {
        if (this.currentPage > 1) {
          this.currentPage--;
          this.$nextTick(() => { if (window.lucide) window.lucide.createIcons(); });
        }
      },

      nextPage() {
        if (this.currentPage < this.totalPages()) {
          this.currentPage++;
          this.$nextTick(() => { if (window.lucide) window.lucide.createIcons(); });
        }
      },

      goToPage(page) {
        this.currentPage = page;
        this.$nextTick(() => { if (window.lucide) window.lucide.createIcons(); });
      },

      toggleDetails(id) {
        this.openHistoryId = this.openHistoryId === id ? null : id;
        this.$nextTick(() => { if (window.lucide) window.lucide.createIcons(); });
      }
    }"
    x-init="
      $watch('searchQuery', () => { currentPage = 1; $nextTick(() => { if (window.lucide) window.lucide.createIcons(); }); });
      $watch('perPage', () => { currentPage = 1; $nextTick(() => { if (window.lucide) window.lucide.createIcons(); }); });
      window.addEventListener('history-updated', (e) => {
        rawItems = e.detail.history;
        currentPage = 1;
        openHistoryId = null;
        $nextTick(() => { if (window.lucide) window.lucide.createIcons(); });
      });
    "
  >
    <!-- Table Controls / Toolbar (Image 2 Aesthetic) -->
    <div class="flex flex-col gap-4 border-b border-slate-200/80 p-5 sm:flex-row sm:items-center sm:justify-between dark:border-slate-800">
      <!-- Show Entries Selector -->
      <div style="display:flex; align-items:center; gap:8px; font-size:12px; font-family:'Poppins',sans-serif; color:#475569; white-space:nowrap;">
        <span>Show</span>
        <select
          x-model.number="perPage"
          style="height:32px; min-width:70px; padding:0 8px 0 8px; border-radius:8px; border:1px solid #e2e8f0; background:#fff; font-size:12px; font-family:'Poppins',sans-serif; font-weight:400; color:#475569; outline:none; -webkit-appearance:auto; -moz-appearance:auto; appearance:auto; cursor:pointer;"
        >
          <option value="5">5</option>
          <option value="10">10</option>
          <option value="25">25</option>
          <option value="50">50</option>
        </select>
        <span>entries</span>
      </div>

      <!-- Right: Search Input & Quick Filter -->
      <div class="flex flex-col gap-2.5 sm:flex-row sm:items-center">
        <!-- Quick Filter Pills -->
        <div class="flex flex-wrap items-center gap-1.5 text-xs">
          <button
            type="button"
            x-on:click="riskFilter = 'all'; statusFilter = 'all'; currentPage = 1;"
            x-bind:class="(riskFilter === 'all' && statusFilter === 'all') ? 'bg-blue-600 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300'"
            class="rounded-lg px-2.5 py-1 text-[11px] font-medium transition"
          >Semua</button>
          <button
            type="button"
            x-on:click="statusFilter = 'Churn'; riskFilter = 'all'; currentPage = 1;"
            x-bind:class="statusFilter === 'Churn' ? 'bg-rose-600 text-white shadow-xs' : 'bg-rose-50 text-rose-700 hover:bg-rose-100 dark:bg-rose-950/40 dark:text-rose-400'"
            class="rounded-lg px-2.5 py-1 text-[11px] font-medium transition"
          >Churn</button>
          <button
            type="button"
            x-on:click="statusFilter = 'Tidak Churn'; riskFilter = 'all'; currentPage = 1;"
            x-bind:class="statusFilter === 'Tidak Churn' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100 dark:bg-emerald-950/40 dark:text-emerald-400'"
            class="rounded-lg px-2.5 py-1 text-[11px] font-medium transition"
          >Tidak Churn</button>
        </div>

        <!-- Search Input with Magnifier Icon -->
        <div class="relative min-w-[220px]">
          <input
            type="text"
            x-model="searchQuery"
            placeholder="Search..."
            class="h-8.5 w-full rounded-xl border border-slate-200/80 bg-white pl-3.5 pr-8 text-xs text-slate-800 placeholder-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
          />
          <i data-lucide="search" class="pointer-events-none absolute right-2.5 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-slate-400"></i>
        </div>
      </div>
    </div>

    <!-- Table Container (Image 2 Style) -->
    <div class="overflow-x-auto">
      <table class="min-w-full border-collapse text-[13px]">
        <thead>
          <tr class="border-b border-slate-200/80 bg-slate-50/80 text-left text-[11px] font-medium uppercase tracking-wider text-slate-500 dark:border-slate-800 dark:bg-slate-800/50 dark:text-slate-400">
            <th class="w-12 px-5 py-3.5 text-center">No.</th>
            <th class="px-5 py-3.5">Nama / ID Pelanggan</th>
            <th class="px-5 py-3.5">Alamat / Wilayah</th>
            <th class="px-5 py-3.5">Status</th>
            <th class="px-5 py-3.5">Probabilitas & Risiko</th>
            <th class="px-5 py-3.5">Terakhir Update</th>
            <th class="px-5 py-3.5 text-center">Aksi</th>
          </tr>
        </thead>
          <template x-for="(item, idx) in paginatedItems()" :key="item.id">
            <tbody>
            <!-- Row Container -->
            <tr class="transition hover:bg-slate-50/60 dark:hover:bg-slate-800/40">
              <!-- Column 1: Row Number -->
              <td class="whitespace-nowrap px-5 py-4 text-center font-normal text-slate-400 dark:text-slate-500" x-text="(currentPage - 1) * perPage + idx + 1">
              </td>

              <!-- Column 2: Avatar + Name / ID -->
              <td class="whitespace-nowrap px-5 py-4">
                <div class="flex items-center gap-3">
                  <span
                    class="grid h-9 w-9 shrink-0 place-items-center rounded-xl font-normal transition"
                    x-bind:class="item.result === 'Churn' ? 'bg-rose-50 text-rose-600 dark:bg-rose-950/60 dark:text-rose-400' : 'bg-blue-50 text-blue-600 dark:bg-blue-950/60 dark:text-blue-400'"
                  >
                    <i data-lucide="user" class="h-4 w-4" x-show="item.result !== 'Churn'"></i>
                    <i data-lucide="user-x" class="h-4 w-4" x-show="item.result === 'Churn'"></i>
                  </span>
                  <div>
                    <div class="font-medium text-slate-700 dark:text-slate-200 flex items-center gap-1.5">
                      <span>Pelanggan #<span x-text="item.id"></span></span>
                    </div>
                    <div class="text-[11px] font-normal text-slate-400">
                      <span x-text="item.account_length || '0'"></span> hari berlangganan
                    </div>
                  </div>
                </div>
              </td>

              <!-- Column 3: Alamat / Kode Area -->
              <td class="whitespace-nowrap px-5 py-4">
                <span class="inline-flex items-center gap-1 font-normal text-blue-600 dark:text-blue-400">
                  <span>Area Code <span x-text="item.area_code"></span></span>
                  <i data-lucide="external-link" class="h-3 w-3"></i>
                </span>
                <div class="text-[11px] text-slate-400">
                  <span x-text="item.customer_service_calls"></span>x CS Call
                </div>
              </td>

              <!-- Column 4: Status Badge -->
              <td class="whitespace-nowrap px-5 py-4">
                <template x-if="item.result === 'Churn'">
                  <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-50 px-3 py-1 text-xs font-normal text-rose-700 dark:bg-rose-950/60 dark:text-rose-300">
                    <span class="h-2 w-2 rounded-full bg-rose-500"></span>
                    Tidak Aktif (Churn)
                  </span>
                </template>
                <template x-if="item.result !== 'Churn'">
                  <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-normal text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300">
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                    Aktif (Loyal)
                  </span>
                </template>
              </td>

              <!-- Column 5: Probabilitas & Risiko -->
              <td class="whitespace-nowrap px-5 py-4">
                <div class="flex items-center gap-2">
                  <span class="font-normal text-slate-600 dark:text-slate-300" x-text="item.probability + '%'"></span>
                  <span
                    class="rounded-md px-2 py-0.5 text-[10px] font-normal uppercase tracking-wider"
                    x-bind:class="item.risiko === 'Tinggi' ? 'bg-rose-100 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300' : (item.risiko === 'Sedang' ? 'bg-amber-100 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300' : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300')"
                    x-text="item.risiko"
                  ></span>
                </div>
                <div class="mt-1 h-1.5 w-24 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
                  <div
                    class="h-full rounded-full transition-all duration-300"
                    x-bind:class="(parseFloat(item.probability) >= 70) ? 'bg-rose-500' : ((parseFloat(item.probability) >= 40) ? 'bg-amber-500' : 'bg-emerald-500')"
                    x-bind:style="'width: ' + Math.min(100, Math.max(5, parseFloat(item.probability))) + '%'"
                  ></div>
                </div>
              </td>

              <!-- Column 6: Terakhir Update -->
              <td class="whitespace-nowrap px-5 py-4">
                <div class="font-normal text-slate-500 dark:text-slate-400" x-text="item.timestamp"></div>
                <div class="text-[11px] text-slate-400">Tersimpan Otomatis</div>
              </td>

              <!-- Column 7: Action Buttons -->
              <td class="whitespace-nowrap px-5 py-4 text-center">
                <div class="inline-flex items-center justify-center gap-2">
                  <!-- Detail Button -->
                  <button
                    type="button"
                    x-on:click="toggleDetails(item.id)"
                    x-bind:class="openHistoryId === item.id ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-blue-600 border-blue-200 hover:bg-blue-50 dark:bg-slate-900 dark:text-blue-400 dark:border-blue-800/80 dark:hover:bg-blue-950/40'"
                    class="inline-flex h-8 items-center gap-1.5 rounded-lg border px-3 text-xs font-normal transition"
                  >
                    <i data-lucide="eye" class="h-3.5 w-3.5"></i>
                    <span x-text="openHistoryId === item.id ? 'Tutup' : 'Detail'"></span>
                  </button>

                  <!-- Delete Button -->
                  <form
                    method="POST"
                    x-bind:action="'/history/' + item.id + '/delete'"
                    onsubmit="return confirm('Hapus riwayat klasifikasi ini?');"
                    class="m-0 inline"
                  >
                    @csrf
                    <button
                      type="submit"
                      class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-rose-200 bg-white text-rose-600 transition hover:bg-rose-50 dark:border-rose-800/80 dark:bg-slate-900 dark:text-rose-400 dark:hover:bg-rose-950/40"
                      title="Hapus"
                    >
                      <i data-lucide="trash-2" class="h-3.5 w-3.5"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>

            <!-- Expanded Details Accordion Row -->
            <tr
              x-show="openHistoryId === item.id"
              x-transition:enter="transition ease-out duration-200"
              x-transition:enter-start="opacity-0 -translate-y-1"
              x-transition:enter-end="opacity-100 translate-y-0"
              x-transition:leave="transition ease-in duration-150"
              x-transition:leave-start="opacity-100 translate-y-0"
              x-transition:leave-end="opacity-0 -translate-y-1"
              class="border-b border-slate-200/80 bg-slate-50/70 dark:border-slate-800 dark:bg-slate-900/60"
            >
              <td colspan="7" class="p-5">
                <div class="grid gap-4 md:grid-cols-[280px_1fr]">
                  <!-- Summary Card -->
                  <div class="rounded-xl border border-slate-200/80 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex items-center justify-between">
                      <p class="text-[11px] font-normal uppercase tracking-wider text-slate-400 dark:text-slate-500">Hasil Analisis Model</p>
                      <span class="rounded bg-slate-100 px-2 py-0.5 text-[10px] font-mono text-slate-600 dark:bg-slate-800 dark:text-slate-400" x-text="'ID: #' + item.id"></span>
                    </div>
                    <div class="mt-3 flex items-baseline gap-2">
                      <span
                        class="inline-flex rounded-md px-2.5 py-1 text-xs font-normal"
                        x-bind:class="item.result === 'Churn' ? 'bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300' : 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300'"
                        x-text="item.result"
                      ></span>
                      <span class="text-base font-normal text-slate-600 dark:text-slate-300" x-text="item.probability + '%'"></span>
                    </div>
                    <p class="mt-2.5 text-xs leading-relaxed text-slate-600 dark:text-slate-300" x-text="item.description || '-'"></p>

                    <div class="mt-3 divide-y divide-slate-100 border-t border-slate-100 pt-2 text-[11px] text-slate-500 dark:divide-slate-800 dark:border-slate-800 dark:text-slate-400">
                      <div class="flex justify-between py-1">
                        <span>Level Risiko:</span>
                        <span class="font-normal text-slate-800 dark:text-slate-200" x-text="item.risiko || '-'"></span>
                      </div>
                      <div class="flex justify-between py-1">
                        <span>Tingkat Keyakinan:</span>
                        <span class="font-normal text-slate-800 dark:text-slate-200" x-text="item.confidence_label || '-'"></span>
                      </div>
                    </div>
                  </div>

                  <!-- Detailed Parameters Grid -->
                  <div class="rounded-xl border border-slate-200/80 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <p class="text-[11px] font-normal uppercase tracking-wider text-slate-500 dark:text-slate-400">Variabel Parameter Pelanggan</p>
                    <div class="mt-3 grid gap-2.5 grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 text-xs">
                      <div class="rounded-lg border border-slate-100 bg-slate-50/60 p-2.5 dark:border-slate-800 dark:bg-slate-800/40">
                        <span class="text-[10px] uppercase font-semibold text-slate-400">Masa Langganan</span>
                        <p class="mt-1 font-medium text-slate-600 dark:text-slate-300" x-text="(item.account_length || 0) + ' hari'"></p>
                      </div>
                      <div class="rounded-lg border border-slate-100 bg-slate-50/60 p-2.5 dark:border-slate-800 dark:bg-slate-800/40">
                        <span class="text-[10px] uppercase font-semibold text-slate-400">Paket Internasional</span>
                        <p class="mt-1 font-medium text-slate-600 dark:text-slate-300" x-text="item.international_plan || '-'"></p>
                      </div>
                      <div class="rounded-lg border border-slate-100 bg-slate-50/60 p-2.5 dark:border-slate-800 dark:bg-slate-800/40">
                        <span class="text-[10px] uppercase font-semibold text-slate-400">Paket Voicemail</span>
                        <p class="mt-1 font-medium text-slate-600 dark:text-slate-300" x-text="item.voice_mail_plan || '-'"></p>
                      </div>
                      <div class="rounded-lg border border-slate-100 bg-slate-50/60 p-2.5 dark:border-slate-800 dark:bg-slate-800/40">
                        <span class="text-[10px] uppercase font-semibold text-slate-400">Panggilan CS</span>
                        <p class="mt-1 font-medium" x-bind:class="(parseInt(item.customer_service_calls) >= 4) ? 'text-rose-600 dark:text-rose-400' : 'text-slate-600 dark:text-slate-300'" x-text="(item.customer_service_calls || 0) + ' kali'"></p>
                      </div>
                      <div class="rounded-lg border border-slate-100 bg-slate-50/60 p-2.5 dark:border-slate-800 dark:bg-slate-800/40">
                        <span class="text-[10px] uppercase font-semibold text-slate-400">Menit Siang</span>
                        <p class="mt-1 font-medium text-slate-600 dark:text-slate-300" x-text="(item.total_day_minutes || 0) + ' min'"></p>
                      </div>
                      <div class="rounded-lg border border-slate-100 bg-slate-50/60 p-2.5 dark:border-slate-800 dark:bg-slate-800/40">
                        <span class="text-[10px] uppercase font-semibold text-slate-400">Menit Sore</span>
                        <p class="mt-1 font-medium text-slate-600 dark:text-slate-300" x-text="(item.total_eve_minutes || 0) + ' min'"></p>
                      </div>
                      <div class="rounded-lg border border-slate-100 bg-slate-50/60 p-2.5 dark:border-slate-800 dark:bg-slate-800/40">
                        <span class="text-[10px] uppercase font-semibold text-slate-400">Menit Malam</span>
                        <p class="mt-1 font-medium text-slate-600 dark:text-slate-300" x-text="(item.total_night_minutes || 0) + ' min'"></p>
                      </div>
                      <div class="rounded-lg border border-slate-100 bg-slate-50/60 p-2.5 dark:border-slate-800 dark:bg-slate-800/40">
                        <span class="text-[10px] uppercase font-semibold text-slate-400">Menit Internasional</span>
                        <p class="mt-1 font-medium text-slate-600 dark:text-slate-300" x-text="(item.total_intl_minutes || 0) + ' min'"></p>
                      </div>
                    </div>
                  </div>
                </div>
              </td>
            </tr>
            </tbody>
          </template>

          <!-- Empty filtered state -->
          <tbody x-show="filteredItems().length === 0">
            <tr>
              <td colspan="7" class="p-8 text-center text-xs text-slate-400">
                Tidak ada data yang cocok dengan kriteria pencarian / filter.
              </td>
            </tr>
          </tbody>
      </table>
    </div>

    <!-- Table Footer & Pagination (Image 2 Aesthetic) -->
    <div class="flex flex-col gap-3 border-t border-slate-200/80 p-5 sm:flex-row sm:items-center sm:justify-between dark:border-slate-800">
      <!-- Showing X to Y of Z entries -->
      <div class="text-xs text-slate-500 dark:text-slate-400">
        Showing <span class="font-medium text-slate-500 dark:text-slate-400" x-text="startIndex()"></span> to <span class="font-medium text-slate-500 dark:text-slate-400" x-text="endIndex()"></span> of <span class="font-medium text-slate-500 dark:text-slate-400" x-text="totalCount()"></span> entries
      </div>

      <!-- Pagination Buttons -->
      <div class="flex items-center gap-1.5">
        <!-- Previous Button -->
        <button
          type="button"
          x-on:click="prevPage()"
          x-bind:disabled="currentPage === 1"
          class="inline-flex h-8.5 items-center justify-center rounded-lg border border-slate-200/80 bg-white px-3 text-xs font-normal text-slate-500 transition hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-40 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-slate-700"
        >
          Previous
        </button>

        <!-- Page Numbers -->
        <template x-for="p in totalPages()" :key="p">
          <button
            type="button"
            x-on:click="goToPage(p)"
            x-bind:class="currentPage === p ? 'bg-blue-600 text-white shadow-xs' : 'border border-slate-200/80 bg-white text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700'"
            class="inline-flex h-8.5 min-w-[34px] items-center justify-center rounded-lg px-2 text-xs font-medium transition"
            x-text="p"
          ></button>
        </template>

        <!-- Next Button -->
        <button
          type="button"
          x-on:click="nextPage()"
          x-bind:disabled="currentPage === totalPages()"
          class="inline-flex h-8.5 items-center justify-center rounded-lg border border-slate-200/80 bg-white px-3 text-xs font-normal text-slate-500 transition hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-40 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-slate-700"
        >
          Next
        </button>
      </div>
    </div>
  </div>
@else
  <div class="m-5 grid min-h-32 place-items-center rounded-2xl border border-dashed border-slate-200/80 bg-slate-50/40 p-8 text-center dark:border-slate-800 dark:bg-slate-900/50">
    <div>
      <div class="mx-auto grid h-10 w-10 place-items-center rounded-xl bg-blue-50 text-blue-600 dark:bg-blue-950/60 dark:text-blue-400">
        <i data-lucide="layers" class="h-5 w-5"></i>
      </div>
      <p class="mt-2 text-sm font-semibold text-slate-900 dark:text-white">Belum ada riwayat klasifikasi</p>
      <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Mulai dengan memasukkan data pelanggan baru di menu klasifikasi.</p>
      <a href="{{ route('prediction.page') }}" class="mt-3 inline-flex items-center gap-1.5 rounded-xl bg-blue-600 px-4 py-2 text-xs font-semibold text-white shadow-xs transition hover:bg-blue-700 active:scale-[0.99] dark:shadow-none">
        <i data-lucide="plus" class="h-3.5 w-3.5"></i>
        Klasifikasi Sekarang
      </a>
    </div>
  </div>
@endif
