<div class="flex h-full min-h-screen flex-col bg-white text-slate-800 dark:bg-slate-900 dark:text-slate-100" x-on:click.self="sidebarCollapsed = !sidebarCollapsed" title="Klik area kosong sidebar untuk memperkecil/memperbesar">
  <!-- Top App Brand / Header (Clean White Box) -->
  <div
    class="flex h-16 shrink-0 items-center border-b border-slate-200/80 bg-white px-4 dark:border-slate-800 dark:bg-slate-900"
    x-bind:class="sidebarCollapsed ? 'justify-center px-2' : 'px-4'"
  >
    <div class="flex items-center gap-3 overflow-hidden">
      <div class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-blue-600 text-white shadow-sm">
        <i data-lucide="brain-circuit" class="h-5 w-5"></i>
      </div>
      <div class="sidebar-expanded-only min-w-0" x-show="!sidebarCollapsed">
        <span class="block truncate text-sm font-extrabold tracking-tight text-slate-700 dark:text-white">ChurnPredict</span>
        <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">AI Analytics</span>
      </div>
    </div>
  </div>

  <!-- Navigation Links -->
  @php
    $groups = $menuGroups ?? [
      [
        'title' => 'Menu Utama',
        'items' => $navigation ?? []
      ]
    ];
  @endphp

  <nav class="flex-1 space-y-4 overflow-y-auto bg-white px-3 py-4 dark:bg-slate-900" x-bind:class="sidebarCollapsed ? 'px-2' : 'px-3'" x-on:click.self="sidebarCollapsed = !sidebarCollapsed">
    @foreach ($groups as $groupIndex => $group)
      <div class="space-y-1.5">
        @if (!empty($group['title']))
          <div class="sidebar-expanded-only {{ $groupIndex > 0 ? 'mt-4 pt-2 border-t border-slate-100 dark:border-slate-800' : '' }} mb-2 px-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500" x-show="!sidebarCollapsed">
            {{ $group['title'] }}
          </div>
          @if ($groupIndex > 0)
            <div class="sidebar-collapsed-only my-2 border-t border-slate-100 dark:border-slate-800" x-show="sidebarCollapsed"></div>
          @endif
        @endif

        @foreach ($group['items'] as $item)
          <a
            href="{{ route($item['route']) }}"
            class="group flex h-10.5 items-center gap-3 rounded-xl px-3.5 text-xs transition duration-150 {{ $activePage === $item['key'] ? 'bg-blue-50 font-bold text-blue-600 border border-blue-100/80 shadow-2xs dark:bg-blue-950/60 dark:text-blue-400 dark:border-blue-900/60' : 'font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800/60 dark:hover:text-white' }}"
            x-bind:class="sidebarCollapsed ? 'justify-center px-0' : ''"
            title="{{ $item['label'] }}"
            x-on:click.stop
          >
            <span class="grid h-4.5 w-4.5 shrink-0 place-items-center transition {{ $activePage === $item['key'] ? 'text-blue-600 dark:text-blue-400' : 'text-slate-400 group-hover:text-slate-700 dark:text-slate-500 dark:group-hover:text-slate-300' }}">
              <i data-lucide="{{ $item['icon'] }}" class="h-4.5 w-4.5" stroke-width="{{ $activePage === $item['key'] ? '2.5' : '2' }}"></i>
            </span>
            <span class="sidebar-expanded-only truncate" x-show="!sidebarCollapsed" x-transition.opacity>{{ $item['label'] }}</span>

            <!-- Chevron Right Icon -->
            <span class="sidebar-expanded-only ml-auto text-xs" x-show="!sidebarCollapsed">
              <i data-lucide="chevron-right" class="h-3.5 w-3.5 transition {{ $activePage === $item['key'] ? 'text-blue-600 opacity-100 dark:text-blue-400' : 'text-slate-300 opacity-0 group-hover:opacity-100 dark:text-slate-600' }}"></i>
            </span>
          </a>
        @endforeach
      </div>
    @endforeach
  </nav>

  <!-- Sidebar Footer / Collapse Toggle -->
  <div class="sidebar-expanded-only mt-auto border-t border-slate-200/80 bg-slate-50/60 p-3 dark:border-slate-800 dark:bg-slate-900/80" x-show="!sidebarCollapsed" x-transition.opacity x-on:click.stop>
    <button
      type="button"
      x-on:click="sidebarCollapsed = true"
      class="flex w-full items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold text-slate-600 transition hover:bg-slate-100 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-200"
    >
      <div class="flex items-center gap-2">
        <i data-lucide="panel-left-close" class="h-4 w-4"></i>
        <span>Ciutkan navigasi</span>
      </div>
      <i data-lucide="chevron-left" class="h-3.5 w-3.5 opacity-60"></i>
    </button>
  </div>

  <div class="sidebar-collapsed-only mt-auto border-t border-slate-200/80 bg-slate-50/60 p-2 dark:border-slate-800 dark:bg-slate-900/80" x-show="sidebarCollapsed" x-on:click.stop>
    <button
      type="button"
      x-on:click="sidebarCollapsed = false"
      title="Perbesar navigasi"
      class="flex h-9 w-full items-center justify-center rounded-xl text-slate-500 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white"
    >
      <i data-lucide="panel-left-open" class="h-4 w-4"></i>
    </button>
  </div>
</div>


