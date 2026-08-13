<div class="flex h-full min-h-screen cursor-pointer flex-col bg-white text-[#102A43]" x-on:click.self="sidebarCollapsed = !sidebarCollapsed" title="Klik area kosong sidebar untuk sembunyikan/tampilkan">
  @php
    $authUser = auth()->user();
    $currentUser = [
      'name' => $authUser?->name ?? 'Guest User',
      'email' => $authUser?->email ?? 'guest@churnpredict.local',
      'role' => $authUser ? ($authUser->email === 'admin@gmail.com' ? 'Administrator' : 'Pengguna') : 'Viewer',
      'photo_url' => null,
    ];

    if ($authUser) {
      $photoMatches = glob(public_path(sprintf('profile-photos/user-%d.*', $authUser->id))) ?: [];
      $currentUser['photo_url'] = $photoMatches ? asset('profile-photos/' . basename($photoMatches[0])) : null;
    }

    $userInitial = strtoupper(substr($currentUser['name'], 0, 1));
    $userPhoto = $currentUser['photo_url'];
  @endphp

  <div class="border-b border-[#D8E2EC] px-4 py-4" x-bind:class="sidebarCollapsed ? 'px-2' : 'px-4'" x-on:click.self="sidebarCollapsed = !sidebarCollapsed">
    <a href="{{ route('profile.page') }}" class="sidebar-expanded-only flex min-w-0 items-center gap-3" x-bind:class="sidebarCollapsed ? 'hidden' : ''" x-on:click.stop>
      @if ($userPhoto)
        <img src="{{ $userPhoto }}" alt="{{ $currentUser['name'] }}" class="h-8 w-8 shrink-0 rounded-md object-cover">
      @else
        <span class="grid h-8 w-8 shrink-0 place-items-center rounded-md bg-[#FFF0F6] text-xs font-bold text-[#DB5A8D]">{{ $userInitial }}</span>
      @endif
      <span class="min-w-0">
        <span class="block truncate text-[13px] font-bold">{{ $currentUser['name'] }}</span>
        <span class="block truncate text-xs text-[#64748B]">{{ $currentUser['role'] }}</span>
      </span>
    </a>
    <a href="{{ route('profile.page') }}" x-show="sidebarCollapsed" class="sidebar-collapsed-only mx-auto grid h-8 w-8 place-items-center rounded-md bg-[#FFF0F6] text-xs font-bold text-[#DB5A8D]" x-on:click.stop>
      @if ($userPhoto)
        <img src="{{ $userPhoto }}" alt="{{ $currentUser['name'] }}" class="h-8 w-8 rounded-md object-cover">
      @else
        {{ $userInitial }}
      @endif
    </a>

  </div>

  <nav class="space-y-0.5 px-3 py-3" x-bind:class="sidebarCollapsed ? 'px-2' : 'px-3'" x-on:click.self="sidebarCollapsed = !sidebarCollapsed">
    @foreach ($navigation as $item)
      @if ($item['key'] === 'profile')
        <div class="my-3 border-t border-[#E5EEF8] pt-3" x-bind:class="sidebarCollapsed ? 'mx-1' : ''"></div>
      @endif
      <a href="{{ route($item['route']) }}" class="{{ $activePage === $item['key'] ? 'border-l-2 border-[#DB5A8D] bg-[#FFF6FA] text-[#DB5A8D]' : 'border-l-2 border-transparent text-[#475569] hover:bg-[#F8FAFC] hover:text-[#102A43]' }} flex h-9 items-center gap-3 rounded-r-md px-3 text-[13px] font-medium transition" x-bind:class="sidebarCollapsed ? 'justify-center px-0 rounded-md border-l-0' : ''" title="{{ $item['label'] }}" x-on:click.stop>
        <span class="{{ $activePage === $item['key'] ? 'text-[#DB5A8D]' : 'text-[#64748B]' }} grid h-5 w-5 place-items-center transition">
          <i data-lucide="{{ $item['icon'] }}" class="h-4 w-4" stroke-width="1.9"></i>
        </span>
        <span class="sidebar-expanded-only" x-show="!sidebarCollapsed" x-transition.opacity>{{ $item['label'] }}</span>
      </a>
    @endforeach
  </nav>

  <form method="POST" action="{{ route('logout') }}" class="sidebar-expanded-only mt-auto border-t border-[#D8E2EC] p-4" x-show="!sidebarCollapsed" x-transition.opacity x-on:click.stop>
    @csrf
    <button type="submit" class="inline-flex h-8 w-full items-center justify-center gap-2 rounded-md border border-[#D8E2EC] bg-white text-[11px] font-semibold text-[#475569] transition hover:bg-[#F8FAFC]">
      <i data-lucide="log-out" class="h-3.5 w-3.5"></i>
      Logout
    </button>
  </form>
</div>
