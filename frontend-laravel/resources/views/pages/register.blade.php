@extends('layouts.guest')

@section('title', 'Daftar Akun - ChurnPredict AI')

@section('content')
  <main class="grid min-h-screen place-items-center px-4 py-10 relative overflow-hidden" style="background: #F5F7FA;">

    {{-- Subtle background ambient glow --}}
    <div class="pointer-events-none absolute inset-0" style="background: radial-gradient(circle at 50% 20%, rgba(37,99,235,0.04), transparent 60%), radial-gradient(circle at 70% 80%, rgba(59,130,246,0.03), transparent 50%);"></div>

    <section class="relative w-full max-w-[400px]" x-data="{ showPassword: false, showConfirm: false }">

      {{-- Back to Landing --}}
      <div style="text-align: center; margin-bottom: 20px;">
        <a
          href="{{ route('landing') }}"
          style="font-family: 'Poppins', sans-serif; display: inline-flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 500; color: #94A3B8; text-decoration: none; transition: color 0.2s;"
          onmouseover="this.style.color='#2563EB'"
          onmouseout="this.style.color='#94A3B8'"
        >
          <i data-lucide="arrow-left" style="width: 14px; height: 14px;"></i>
          Kembali ke Beranda
        </a>
      </div>

      {{-- Card Container --}}
      <div style="border-radius: 24px; box-shadow: 0 12px 48px rgba(15,23,42,0.07), 0 2px 8px rgba(15,23,42,0.04); background: #ffffff; padding: 40px 32px 36px;">

        {{-- Branding: Icon + Title + Subtitle --}}
        <div style="text-align: center; margin-bottom: 32px;">
          <div style="display: inline-flex; align-items: center; justify-content: center; width: 56px; height: 56px; border-radius: 18px; background: linear-gradient(135deg, #2563EB, #3B82F6); box-shadow: 0 8px 24px rgba(37,99,235,0.25); margin-bottom: 14px;">
            <i data-lucide="user-plus" style="width: 28px; height: 28px; color: #ffffff;"></i>
          </div>
          <h1 style="font-family: 'Poppins', sans-serif; font-size: 20px; font-weight: 600; color: #1E293B; letter-spacing: -0.3px; margin: 0;">
            Buat Akun Baru
          </h1>
          <p style="font-family: 'Poppins', sans-serif; font-size: 12px; font-weight: 400; color: #94A3B8; margin-top: 4px;">
            Daftar untuk mengakses sistem klasifikasi churn
          </p>
        </div>

        {{-- Validation Error Alert --}}
        @if ($errors->any())
          <div style="display: flex; align-items: flex-start; gap: 10px; padding: 12px 14px; border-radius: 14px; background: #FFF7ED; border: 1px solid #FED7AA; margin-bottom: 20px;">
            <i data-lucide="alert-circle" style="width: 16px; height: 16px; color: #F59E0B; flex-shrink: 0; margin-top: 1px;"></i>
            <span style="font-family: 'Poppins', sans-serif; font-size: 12px; font-weight: 500; color: #92400E;">{{ $errors->first() }}</span>
          </div>
        @endif

        {{-- Register Form --}}
        <form method="POST" action="{{ route('register.store') }}" id="registerForm">
          @csrf

          {{-- Nama Lengkap --}}
          <div style="margin-bottom: 16px;">
            <label for="nameInput" style="font-family: 'Poppins', sans-serif; display: block; font-size: 13px; font-weight: 500; color: #475569; margin-bottom: 8px;">
              Nama Lengkap
            </label>
            <input
              id="nameInput"
              name="name"
              type="text"
              value="{{ old('name') }}"
              required
              autocomplete="name"
              placeholder="Nama Anda"
              style="font-family: 'Poppins', sans-serif; width: 100%; height: 48px; border-radius: 50px; border: 1.5px solid #DBEAFE; background: #F8FAFF; padding: 0 20px; font-size: 13px; color: #334155; outline: none; transition: all 0.2s ease; box-sizing: border-box;"
              onfocus="this.style.borderColor='#3B82F6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)'; this.style.background='#FFFFFF';"
              onblur="this.style.borderColor='#DBEAFE'; this.style.boxShadow='none'; this.style.background='#F8FAFF';"
            >
          </div>

          {{-- Email --}}
          <div style="margin-bottom: 16px;">
            <label for="emailInput" style="font-family: 'Poppins', sans-serif; display: block; font-size: 13px; font-weight: 500; color: #475569; margin-bottom: 8px;">
              Email
            </label>
            <input
              id="emailInput"
              name="email"
              type="email"
              value="{{ old('email') }}"
              required
              autocomplete="email"
              placeholder="nama@email.com"
              style="font-family: 'Poppins', sans-serif; width: 100%; height: 48px; border-radius: 50px; border: 1.5px solid #DBEAFE; background: #F8FAFF; padding: 0 20px; font-size: 13px; color: #334155; outline: none; transition: all 0.2s ease; box-sizing: border-box;"
              onfocus="this.style.borderColor='#3B82F6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)'; this.style.background='#FFFFFF';"
              onblur="this.style.borderColor='#DBEAFE'; this.style.boxShadow='none'; this.style.background='#F8FAFF';"
            >
          </div>

          {{-- Password --}}
          <div style="margin-bottom: 16px;">
            <label for="passwordInput" style="font-family: 'Poppins', sans-serif; display: block; font-size: 13px; font-weight: 500; color: #475569; margin-bottom: 8px;">
              Password
            </label>
            <div style="position: relative;">
              <input
                id="passwordInput"
                name="password"
                x-bind:type="showPassword ? 'text' : 'password'"
                required
                autocomplete="new-password"
                placeholder="Minimal 8 karakter"
                style="font-family: 'Poppins', sans-serif; width: 100%; height: 48px; border-radius: 50px; border: 1.5px solid #DBEAFE; background: #F8FAFF; padding: 0 50px 0 20px; font-size: 13px; color: #334155; outline: none; transition: all 0.2s ease; box-sizing: border-box;"
                onfocus="this.style.borderColor='#3B82F6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)'; this.style.background='#FFFFFF';"
                onblur="this.style.borderColor='#DBEAFE'; this.style.boxShadow='none'; this.style.background='#F8FAFF';"
              >
              <button
                type="button"
                x-on:click="showPassword = !showPassword"
                style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; padding: 4px; color: #94A3B8; transition: color 0.2s;"
                onmouseover="this.style.color='#64748B'"
                onmouseout="this.style.color='#94A3B8'"
                title="Tampilkan / sembunyikan password"
              >
                <i x-show="!showPassword" data-lucide="eye" style="width: 18px; height: 18px;"></i>
                <i x-show="showPassword" data-lucide="eye-off" style="width: 18px; height: 18px;"></i>
              </button>
            </div>
          </div>

          {{-- Konfirmasi Password --}}
          <div style="margin-bottom: 24px;">
            <label for="passwordConfirmationInput" style="font-family: 'Poppins', sans-serif; display: block; font-size: 13px; font-weight: 500; color: #475569; margin-bottom: 8px;">
              Konfirmasi Password
            </label>
            <div style="position: relative;">
              <input
                id="passwordConfirmationInput"
                name="password_confirmation"
                x-bind:type="showConfirm ? 'text' : 'password'"
                required
                autocomplete="new-password"
                placeholder="Ulangi password"
                style="font-family: 'Poppins', sans-serif; width: 100%; height: 48px; border-radius: 50px; border: 1.5px solid #DBEAFE; background: #F8FAFF; padding: 0 50px 0 20px; font-size: 13px; color: #334155; outline: none; transition: all 0.2s ease; box-sizing: border-box;"
                onfocus="this.style.borderColor='#3B82F6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)'; this.style.background='#FFFFFF';"
                onblur="this.style.borderColor='#DBEAFE'; this.style.boxShadow='none'; this.style.background='#F8FAFF';"
              >
              <button
                type="button"
                x-on:click="showConfirm = !showConfirm"
                style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; padding: 4px; color: #94A3B8; transition: color 0.2s;"
                onmouseover="this.style.color='#64748B'"
                onmouseout="this.style.color='#94A3B8'"
                title="Tampilkan / sembunyikan password"
              >
                <i x-show="!showConfirm" data-lucide="eye" style="width: 18px; height: 18px;"></i>
                <i x-show="showConfirm" data-lucide="eye-off" style="width: 18px; height: 18px;"></i>
              </button>
            </div>
          </div>

          {{-- Daftar Button --}}
          <button
            type="submit"
            style="font-family: 'Poppins', sans-serif; width: 100%; height: 48px; border-radius: 50px; border: none; background: linear-gradient(135deg, #2563EB, #3B82F6); color: #FFFFFF; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.25s ease; box-shadow: 0 6px 20px rgba(37,99,235,0.25); letter-spacing: 0.2px;"
            onmouseover="this.style.background='linear-gradient(135deg, #1D4ED8, #2563EB)'; this.style.boxShadow='0 8px 25px rgba(37,99,235,0.35)'; this.style.transform='translateY(-1px)';"
            onmouseout="this.style.background='linear-gradient(135deg, #2563EB, #3B82F6)'; this.style.boxShadow='0 6px 20px rgba(37,99,235,0.25)'; this.style.transform='translateY(0)';"
            onmousedown="this.style.transform='scale(0.99)'"
            onmouseup="this.style.transform='translateY(-1px)'"
          >
            Daftar Akun
          </button>
        </form>

        {{-- Login Link --}}
        <div style="margin-top: 24px; padding-top: 20px; border-top: 1px solid #F1F5F9; text-align: center;">
          <span style="font-family: 'Poppins', sans-serif; font-size: 12px; color: #94A3B8;">Sudah memiliki akun?</span>
          <a
            href="{{ route('login') }}"
            style="font-family: 'Poppins', sans-serif; font-size: 12px; font-weight: 600; color: #2563EB; text-decoration: none; margin-left: 4px; transition: color 0.2s;"
            onmouseover="this.style.color='#1D4ED8'"
            onmouseout="this.style.color='#2563EB'"
          >
            Masuk sekarang
          </a>
        </div>
      </div>

      {{-- Server Info --}}
      <div style="margin-top: 16px; text-align: center;">
        <span style="font-family: 'Poppins', sans-serif; font-size: 11px; font-weight: 400; color: #CBD5E1;">
          Server: http://127.0.0.1:8000
        </span>
      </div>
    </section>
  </main>
@endsection
