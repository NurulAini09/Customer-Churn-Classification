@extends('layouts.guest')

@section('title', 'Reset Password - ChurnPredict AI')

@section('content')
  <main class="grid min-h-screen place-items-center px-5 py-8">
    <section class="w-full max-w-[420px] rounded-md border border-[#D8E2EC] bg-white shadow-[0_24px_80px_rgba(15,23,42,0.08)]">
      <div class="border-b border-[#D8E2EC] px-6 py-5">
        <div class="mb-4 grid h-10 w-10 place-items-center rounded-md bg-[#DB5A8D] text-sm font-bold text-white">CP</div>
        <h1 class="text-[22px] font-bold text-[#102A43]">Reset Password</h1>
        <p class="mt-1 text-[13px] leading-6 text-[#64748B]">Masukkan email dan password baru untuk akun Anda.</p>
      </div>

      <div class="p-6">
        @if ($errors->any())
          <div class="mb-4 rounded-md border border-[#e7d599] bg-[#fff9df] px-3 py-2 text-[13px] font-medium text-[#886b14]">
            Pastikan email dan konfirmasi password sudah benar.
          </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
          @csrf
          <label class="block">
            <span class="mb-1.5 block text-xs font-semibold text-[#475569]">Email</span>
            <span class="flex h-10 items-center gap-2 rounded-md border border-[#D8E2EC] bg-white px-3 transition focus-within:border-[#DB5A8D]">
              <i data-lucide="mail" class="h-4 w-4 shrink-0 text-[#64748B]"></i>
              <input name="email" type="email" value="{{ old('email') }}" required autocomplete="email" class="h-full min-w-0 flex-1 border-0 bg-transparent p-0 text-sm font-medium text-[#102A43] outline-none" placeholder="nama@example.com">
            </span>
          </label>

          <label class="block">
            <span class="mb-1.5 block text-xs font-semibold text-[#475569]">Password Baru</span>
            <span class="flex h-10 items-center gap-2 rounded-md border border-[#D8E2EC] bg-white px-3 transition focus-within:border-[#DB5A8D]">
              <i data-lucide="lock" class="h-4 w-4 shrink-0 text-[#64748B]"></i>
              <input name="password" type="password" required autocomplete="new-password" class="h-full min-w-0 flex-1 border-0 bg-transparent p-0 text-sm font-medium text-[#102A43] outline-none" placeholder="Minimal 6 karakter">
            </span>
          </label>

          <label class="block">
            <span class="mb-1.5 block text-xs font-semibold text-[#475569]">Konfirmasi Password</span>
            <span class="flex h-10 items-center gap-2 rounded-md border border-[#D8E2EC] bg-white px-3 transition focus-within:border-[#DB5A8D]">
              <i data-lucide="lock" class="h-4 w-4 shrink-0 text-[#64748B]"></i>
              <input name="password_confirmation" type="password" required autocomplete="new-password" class="h-full min-w-0 flex-1 border-0 bg-transparent p-0 text-sm font-medium text-[#102A43] outline-none" placeholder="Ulangi password">
            </span>
          </label>

          <button type="submit" class="inline-flex h-10 w-full items-center justify-center gap-2 rounded-md bg-[#DB5A8D] text-sm font-semibold text-white transition hover:bg-[#C84A7B]">
            <i data-lucide="lock" class="h-4 w-4"></i>
            Reset Password
          </button>
        </form>

        <div class="mt-5 border-t border-[#E5EEF8] pt-4 text-center text-[13px] font-medium">
          <a href="{{ route('login') }}" class="text-[#DB5A8D] transition hover:text-[#C84A7B]">Kembali ke login</a>
        </div>
      </div>
    </section>
  </main>
@endsection
