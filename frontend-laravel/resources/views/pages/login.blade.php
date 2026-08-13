@extends('layouts.guest')

@section('title', 'Login - ChurnPredict AI')

@section('content')
  <main class="grid min-h-screen place-items-center px-5 py-8">
    <section class="w-full max-w-[420px] rounded-md border border-[#D8E2EC] bg-white shadow-[0_24px_80px_rgba(15,23,42,0.08)]">
      <div class="border-b border-[#D8E2EC] px-6 py-5">
        <div class="mb-4 grid h-10 w-10 place-items-center rounded-md bg-[#DB5A8D] text-sm font-bold text-white">CP</div>
        <h1 class="text-[22px] font-bold text-[#102A43]">Masuk ke ChurnPredict</h1>
        <p class="mt-1 text-[13px] leading-6 text-[#64748B]">Gunakan akun yang sudah terdaftar untuk mengakses dashboard klasifikasi churn.</p>
      </div>

      <div class="p-6">
        @if (session('success_message'))
          <div class="mb-4 rounded-md border border-[#F6C7DA] bg-[#FFF0F6] px-3 py-2 text-[13px] font-medium text-[#DB5A8D]">
            {{ session('success_message') }}
          </div>
        @endif

        @if ($errors->any())
          <div class="mb-4 rounded-md border border-[#e7d599] bg-[#fff9df] px-3 py-2 text-[13px] font-medium text-[#886b14]">
            Email dan password wajib diisi dengan benar.
          </div>
        @endif

        <form method="POST" action="{{ route('login.attempt') }}" class="space-y-4">
          @csrf
          <label class="block">
            <span class="mb-1.5 block text-xs font-semibold text-[#475569]">Email</span>
            <span class="flex h-10 items-center gap-2 rounded-md border border-[#D8E2EC] bg-white px-3 transition focus-within:border-[#DB5A8D]">
              <i data-lucide="mail" class="h-4 w-4 shrink-0 text-[#64748B]"></i>
              <input name="email" type="email" value="{{ old('email') }}" required autocomplete="email" class="h-full min-w-0 flex-1 border-0 bg-transparent p-0 text-sm font-medium text-[#102A43] outline-none" placeholder="admin@gmail.com">
            </span>
          </label>

          <label class="block">
            <span class="mb-1.5 block text-xs font-semibold text-[#475569]">Password</span>
            <span class="flex h-10 items-center gap-2 rounded-md border border-[#D8E2EC] bg-white px-3 transition focus-within:border-[#DB5A8D]">
              <i data-lucide="lock" class="h-4 w-4 shrink-0 text-[#64748B]"></i>
              <input name="password" type="password" required autocomplete="current-password" class="h-full min-w-0 flex-1 border-0 bg-transparent p-0 text-sm font-medium text-[#102A43] outline-none" placeholder="Minimal 8 karakter">
            </span>
          </label>

          <button type="submit" class="inline-flex h-10 w-full items-center justify-center gap-2 rounded-md bg-[#DB5A8D] text-sm font-semibold text-white transition hover:bg-[#C84A7B]">
            <i data-lucide="log-in" class="h-4 w-4"></i>
            Login
          </button>
        </form>

        <div class="mt-5 flex flex-col gap-2 border-t border-[#E5EEF8] pt-4 text-center text-[13px] font-medium sm:flex-row sm:items-center sm:justify-between">
          <a href="{{ route('password.request') }}" class="text-[#DB5A8D] transition hover:text-[#C84A7B]">Reset password</a>
          <a href="{{ route('register') }}" class="text-[#475569] transition hover:text-[#102A43]">Daftar akun</a>
        </div>

        <div class="mt-4 rounded-md border border-[#D8E2EC] bg-[#F8FAFC] px-3 py-2 text-[12px] leading-5 text-[#64748B]">
          Akun default sistem: <span class="font-semibold text-[#102A43]">admin@gmail.com</span> / <span class="font-semibold text-[#102A43]">admin12345678</span>
        </div>
      </div>
    </section>
  </main>
@endsection
