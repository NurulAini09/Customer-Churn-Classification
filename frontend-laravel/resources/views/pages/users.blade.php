@extends('layouts.app')

@section('title', 'Manajemen User - ChurnPredict AI')
@section('page-title', 'Manajemen User')

@section('content')
  <section class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-xs dark:border-slate-800 dark:bg-slate-900">
    <!-- Header (Live Monitor style like Image 2) -->
    <div class="flex flex-col justify-between gap-3 border-b border-slate-200/80 p-6 sm:flex-row sm:items-center dark:border-slate-800">
      <div>
        <h1 class="text-xl font-semibold tracking-tight text-slate-700 dark:text-white">Manajemen User</h1>
        <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">Daftar pengguna terdaftar yang memiliki hak akses sistem.</p>
      </div>

      <a href="{{ route('profile.page') }}" class="inline-flex h-9 items-center gap-2 rounded-xl bg-blue-600 px-4 text-xs font-semibold text-white shadow-xs transition hover:bg-blue-700 active:scale-[0.99] dark:shadow-none">
        <i data-lucide="user" class="h-3.5 w-3.5"></i>
        Lihat Profil Akun
      </a>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
      <table class="min-w-full border-collapse text-[13px]">
        <thead>
          <tr class="border-b border-slate-200/80 bg-slate-50/80 text-left text-[11px] font-bold uppercase tracking-wider text-slate-600 dark:border-slate-800 dark:bg-slate-800/50 dark:text-slate-400">
            <th class="w-12 px-5 py-3.5 text-center">No.</th>
            <th class="px-5 py-3.5">Nama Pengguna</th>
            <th class="px-5 py-3.5">Alamat Email</th>
            <th class="px-5 py-3.5">Peran (Role)</th>
            <th class="px-5 py-3.5">Status Akun</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
          @forelse ($users as $index => $user)
            <tr class="transition hover:bg-slate-50/60 dark:hover:bg-slate-800/40">
              <td class="whitespace-nowrap px-5 py-4 text-center font-bold text-slate-500 dark:text-slate-400">
                {{ $index + 1 }}
              </td>
              <td class="whitespace-nowrap px-5 py-4">
                <div class="flex items-center gap-3">
                  <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-blue-50 text-xs font-bold text-blue-600 dark:bg-blue-950/60 dark:text-blue-400">
                    {{ strtoupper(substr($user['name'] ?? 'U', 0, 1)) }}
                  </span>
                  <div>
                    <div class="font-bold text-slate-900 dark:text-white">{{ $user['name'] }}</div>
                    <div class="text-[11px] font-medium text-slate-400">ID Pengguna: #{{ $user['id'] ?? ($index + 1) }}</div>
                  </div>
                </div>
              </td>
              <td class="whitespace-nowrap px-5 py-4">
                <span class="inline-flex items-center gap-1 font-semibold text-blue-600 dark:text-blue-400 hover:underline">
                  <span>{{ $user['email'] }}</span>
                  <i data-lucide="mail" class="h-3 w-3"></i>
                </span>
              </td>
              <td class="whitespace-nowrap px-5 py-4">
                <span class="inline-flex items-center rounded-lg bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700 dark:bg-slate-800 dark:text-slate-300">
                  {{ $user['role'] }}
                </span>
              </td>
              <td class="whitespace-nowrap px-5 py-4">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300">
                  <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                  {{ $user['status'] }}
                </span>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="p-8 text-center text-xs text-slate-400">Belum ada pengguna yang terdaftar.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </section>
@endsection

