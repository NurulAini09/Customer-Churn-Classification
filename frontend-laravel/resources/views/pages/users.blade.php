@extends('layouts.app')

@section('title', 'Users - ChurnPredict AI')
@section('page-title', 'Users')

@section('content')
  <div class="sticky top-0 z-20 -mx-5 mb-3 flex flex-col gap-3 bg-[#F3F6FA] px-5 py-3 sm:-mx-6 sm:flex-row sm:items-center sm:justify-between sm:px-6">
    <div>
      <h2 class="text-xl font-bold text-[#102A43]">Users</h2>
      <p class="mt-1 text-xs font-medium text-[#64748B]">Daftar pengguna yang dapat mengakses sistem.</p>
    </div>
    <a href="{{ route('profile.page') }}" class="inline-flex h-9 items-center gap-2 rounded-md border border-[#D8E2EC] bg-white px-3.5 text-xs font-semibold text-[#475569] transition hover:bg-[#F8FAFC]">
      <i data-lucide="user" class="h-3.5 w-3.5"></i>
      Lihat Profile
    </a>
  </div>

  <section class="rounded-md border border-[#D8E2EC] bg-white">
    <div class="border-b border-[#D8E2EC] px-4 py-3">
      <h3 class="text-base font-semibold text-[#102A43]">User Management</h3>
      <p class="mt-1 text-xs text-[#64748B]">Data pengguna tersimpan pada tabel users aplikasi.</p>
    </div>

    <div class="overflow-x-auto">
      <table class="min-w-full border-collapse text-[13px]">
        <thead>
          <tr class="border-b border-[#D8E2EC] bg-[#F8FAFC] text-left text-[10px] font-semibold uppercase tracking-[0.12em] text-[#64748B]">
            <th class="px-4 py-2.5">Nama</th>
            <th class="px-4 py-2.5">Email</th>
            <th class="px-4 py-2.5">Role</th>
            <th class="px-4 py-2.5">Status</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($users as $user)
            <tr class="border-b border-[#E5EEF8] text-[#475569] transition last:border-b-0 hover:bg-[#F8FAFC]">
              <td class="whitespace-nowrap px-4 py-3 font-semibold text-[#102A43]">{{ $user['name'] }}</td>
              <td class="whitespace-nowrap px-4 py-3">{{ $user['email'] }}</td>
              <td class="px-4 py-3">{{ $user['role'] }}</td>
              <td class="px-4 py-3">
                <span class="rounded border border-[#F6C7DA] bg-[#FFF0F6] px-2 py-0.5 text-[11px] font-semibold text-[#DB5A8D]">{{ $user['status'] }}</span>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="px-4 py-6 text-center text-sm text-[#64748B]">Belum ada pengguna yang terdaftar.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </section>
@endsection
