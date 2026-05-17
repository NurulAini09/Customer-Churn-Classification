<div class="flex h-full min-h-screen cursor-pointer flex-col bg-white text-[#102A43]" x-on:click.self="sidebarCollapsed = !sidebarCollapsed" title="Klik area kosong sidebar untuk sembunyikan/tampilkan">
  <div class="flex h-14 items-center border-b border-[#D8E2EC] px-4" x-bind:class="sidebarCollapsed ? 'justify-center px-0' : ''" x-on:click.self="sidebarCollapsed = !sidebarCollapsed">
    <a href="{{ route('prediction.index') }}" class="sidebar-expanded-only flex min-w-0 items-center gap-3" x-bind:class="sidebarCollapsed ? 'hidden' : ''" x-on:click.stop>
      <span class="grid h-8 w-8 shrink-0 place-items-center rounded-md bg-[#DB5A8D] text-xs font-bold text-white">CP</span>
      <span class="min-w-0">
        <span class="block truncate text-[13px] font-bold">ChurnPredict</span>
        <span class="block truncate text-xs text-[#64748B]">Customer analytics</span>
      </span>
    </a>
    <a href="{{ route('prediction.index') }}" x-show="sidebarCollapsed" class="sidebar-collapsed-only grid h-8 w-8 place-items-center rounded-md bg-[#DB5A8D] text-xs font-bold text-white" x-on:click.stop>CP</a>
  </div>

  <nav class="space-y-0.5 px-3 py-3" x-bind:class="sidebarCollapsed ? 'px-2' : 'px-3'" x-on:click.self="sidebarCollapsed = !sidebarCollapsed">
    @foreach ($navigation as $item)
      <a href="{{ route($item['route']) }}" class="{{ $activePage === $item['key'] ? 'border-l-2 border-[#DB5A8D] bg-[#FFF6FA] text-[#DB5A8D]' : 'border-l-2 border-transparent text-[#475569] hover:bg-[#F8FAFC] hover:text-[#102A43]' }} flex h-9 items-center gap-3 rounded-r-md px-3 text-[13px] font-medium transition" x-bind:class="sidebarCollapsed ? 'justify-center px-0 rounded-md border-l-0' : ''" title="{{ $item['label'] }}" x-on:click.stop>
        <span class="{{ $activePage === $item['key'] ? 'text-[#DB5A8D]' : 'text-[#64748B]' }} grid h-5 w-5 place-items-center transition">
          <i data-lucide="{{ $item['icon'] }}" class="h-4 w-4" stroke-width="1.9"></i>
        </span>
        <span class="sidebar-expanded-only" x-show="!sidebarCollapsed" x-transition.opacity>{{ $item['label'] }}</span>
      </a>
    @endforeach
  </nav>

  <div x-show="!sidebarCollapsed" x-transition.opacity class="sidebar-expanded-only mt-auto border-t border-[#D8E2EC] p-4 text-[11px] leading-5 text-[#64748B]" x-on:click.self="sidebarCollapsed = !sidebarCollapsed">
    Laravel frontend + FastAPI prediction service.
  </div>
</div>
