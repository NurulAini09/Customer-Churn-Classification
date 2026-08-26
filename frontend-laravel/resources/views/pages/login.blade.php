@extends('layouts.guest')

@section('title', 'Masuk - Klasifikasi Churn Pelanggan')

@section('content')
  <main class="grid min-h-screen place-items-center px-4 py-10 relative overflow-hidden" style="background: #F5F7FA;">

    {{-- Subtle background ambient glow --}}
    <div class="pointer-events-none absolute inset-0" style="background: radial-gradient(circle at 50% 20%, rgba(79,70,229,0.04), transparent 60%), radial-gradient(circle at 70% 80%, rgba(59,130,246,0.03), transparent 50%);"></div>

    <section class="relative w-full max-w-[400px]" x-data="{ showPassword: false }">

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

        {{-- Branding: Icon + App Name + Subtitle --}}
        <div style="text-align: center; margin-bottom: 32px;">
          {{-- Logo Icon --}}
          <div style="display: inline-flex; align-items: center; justify-content: center; width: 56px; height: 56px; border-radius: 18px; background: linear-gradient(135deg, #2563EB, #3B82F6); box-shadow: 0 8px 24px rgba(37,99,235,0.25); margin-bottom: 14px;">
            <i data-lucide="activity" style="width: 28px; height: 28px; color: #ffffff;"></i>
          </div>
          <h2 style="font-family: 'Poppins', sans-serif; font-size: 18px; font-weight: 700; color: #1E293B; letter-spacing: -0.3px; margin: 0;">
            Klasifikasi Churn
          </h2>
          <p style="font-family: 'Poppins', sans-serif; font-size: 12px; font-weight: 400; color: #94A3B8; margin-top: 4px;">
            Sistem Klasifikasi Churn Pelanggan
          </p>
        </div>

        {{-- Sign In Title --}}
        <h1 style="font-family: 'Poppins', sans-serif; font-size: 20px; font-weight: 600; color: #1E293B; margin-bottom: 24px;">
          Sign In
        </h1>

        {{-- Flash Messages --}}
        @if (session('success_message'))
          <div style="display: flex; align-items: flex-start; gap: 10px; padding: 12px 14px; border-radius: 14px; background: #F0FDF4; border: 1px solid #BBF7D0; margin-bottom: 20px;">
            <i data-lucide="check-circle-2" style="width: 16px; height: 16px; color: #22C55E; flex-shrink: 0; margin-top: 1px;"></i>
            <span style="font-family: 'Poppins', sans-serif; font-size: 12px; font-weight: 500; color: #166534;">{{ session('success_message') }}</span>
          </div>
        @endif

        @if ($errors->any())
          <div style="display: flex; align-items: flex-start; gap: 10px; padding: 12px 14px; border-radius: 14px; background: #FFF7ED; border: 1px solid #FED7AA; margin-bottom: 20px;">
            <i data-lucide="alert-circle" style="width: 16px; height: 16px; color: #F59E0B; flex-shrink: 0; margin-top: 1px;"></i>
            <span style="font-family: 'Poppins', sans-serif; font-size: 12px; font-weight: 500; color: #92400E;">{{ $errors->first() ?? 'Email atau password tidak sesuai.' }}</span>
          </div>
        @endif

        {{-- Login Form --}}
        <form method="POST" action="{{ route('login.attempt') }}" id="loginForm">
          @csrf

          {{-- Email Field --}}
          <div style="margin-bottom: 18px;">
            <label for="emailInput" style="font-family: 'Poppins', sans-serif; display: block; font-size: 13px; font-weight: 500; color: #475569; margin-bottom: 8px;">
              Username / Email
            </label>
            <input
              id="emailInput"
              name="email"
              type="email"
              value="{{ old('email') }}"
              required
              autocomplete="email"
              placeholder="admin atau email@domain.com"
              style="font-family: 'Poppins', sans-serif; width: 100%; height: 48px; border-radius: 50px; border: 1.5px solid #DBEAFE; background: #F8FAFF; padding: 0 20px; font-size: 13px; color: #334155; outline: none; transition: all 0.2s ease; box-sizing: border-box;"
              onfocus="this.style.borderColor='#3B82F6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)'; this.style.background='#FFFFFF';"
              onblur="this.style.borderColor='#DBEAFE'; this.style.boxShadow='none'; this.style.background='#F8FAFF';"
            >
          </div>

          {{-- Password Field --}}
          <div style="margin-bottom: 6px;">
            <label for="passwordInput" style="font-family: 'Poppins', sans-serif; display: block; font-size: 13px; font-weight: 500; color: #475569; margin-bottom: 8px;">
              Password
            </label>
            <div style="position: relative;">
              <input
                id="passwordInput"
                name="password"
                x-bind:type="showPassword ? 'text' : 'password'"
                required
                autocomplete="current-password"
                placeholder="••••••••"
                style="font-family: 'Poppins', sans-serif; width: 100%; height: 48px; border-radius: 50px; border: 1.5px solid #DBEAFE; background: #F8FAFF; padding: 0 50px 0 20px; font-size: 13px; color: #334155; outline: none; transition: all 0.2s ease; box-sizing: border-box;"
                onfocus="this.style.borderColor='#3B82F6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)'; this.style.background='#FFFFFF';"
                onblur="this.style.borderColor='#DBEAFE'; this.style.boxShadow='none'; this.style.background='#F8FAFF';"
              >
              {{-- Toggle password visibility --}}
              <button
                type="button"
                x-on:click="showPassword = !showPassword"
                style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; padding: 4px; color: #94A3B8; transition: color 0.2s;"
                onmouseover="this.style.color='#64748B'"
                onmouseout="this.style.color='#94A3B8'"
                title="Tampilkan / sembunyikan password"
              >
                <i x-show="!showPassword" data-lucide="eye" style="width: 18px; height: 18px;"></i>
                <i x-show="showPassword" data-lucide="eye-off" style="width: 18px; height: 18px;" style="display: none;"></i>
              </button>
            </div>
          </div>

          {{-- Forgot Password Link --}}
          <div style="text-align: right; margin-bottom: 24px;">
            <a
              href="{{ route('password.request') }}"
              style="font-family: 'Poppins', sans-serif; font-size: 12px; font-weight: 500; color: #64748B; text-decoration: none; transition: color 0.2s;"
              onmouseover="this.style.color='#2563EB'"
              onmouseout="this.style.color='#64748B'"
            >
              Lupa Password?
            </a>
          </div>

          {{-- Sign In Button --}}
          <button
            type="submit"
            style="font-family: 'Poppins', sans-serif; width: 100%; height: 48px; border-radius: 50px; border: none; background: linear-gradient(135deg, #2563EB, #3B82F6); color: #FFFFFF; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.25s ease; box-shadow: 0 6px 20px rgba(37,99,235,0.25); letter-spacing: 0.2px;"
            onmouseover="this.style.background='linear-gradient(135deg, #1D4ED8, #2563EB)'; this.style.boxShadow='0 8px 25px rgba(37,99,235,0.35)'; this.style.transform='translateY(-1px)';"
            onmouseout="this.style.background='linear-gradient(135deg, #2563EB, #3B82F6)'; this.style.boxShadow='0 6px 20px rgba(37,99,235,0.25)'; this.style.transform='translateY(0)';"
            onmousedown="this.style.transform='scale(0.99)'"
            onmouseup="this.style.transform='translateY(-1px)'"
          >
            Sign In
          </button>
        </form>

        {{-- Register Link --}}
        <div style="margin-top: 24px; padding-top: 20px; border-top: 1px solid #F1F5F9; text-align: center;">
          <span style="font-family: 'Poppins', sans-serif; font-size: 12px; color: #94A3B8;">Belum punya akun?</span>
          <a
            href="{{ route('register') }}"
            style="font-family: 'Poppins', sans-serif; font-size: 12px; font-weight: 600; color: #2563EB; text-decoration: none; margin-left: 4px; transition: color 0.2s;"
            onmouseover="this.style.color='#1D4ED8'"
            onmouseout="this.style.color='#2563EB'"
          >
            Daftar sekarang
          </a>
        </div>
      </div>

      {{-- Demo Account Quick-Fill --}}
      <div style="margin-top: 16px; text-align: center;">
        <button
          type="button"
          onclick="fillDemoAccount()"
          style="font-family: 'Poppins', sans-serif; display: inline-flex; align-items: center; gap: 6px; font-size: 11px; font-weight: 500; color: #94A3B8; background: none; border: none; cursor: pointer; padding: 6px 12px; border-radius: 20px; transition: all 0.2s;"
          onmouseover="this.style.color='#64748B'; this.style.background='rgba(255,255,255,0.6)'"
          onmouseout="this.style.color='#94A3B8'; this.style.background='none'"
        >
          <i data-lucide="key" style="width: 12px; height: 12px;"></i>
          <span>Isi otomatis akun demo</span>
        </button>
      </div>

      {{-- Server Info --}}
      <div style="margin-top: 8px; text-align: center;">
        <span style="font-family: 'Poppins', sans-serif; font-size: 11px; font-weight: 400; color: #CBD5E1;">
          Server: http://127.0.0.1:8000
        </span>
      </div>
    </section>
  </main>

  <script>
    function fillDemoAccount() {
      const emailEl = document.getElementById('emailInput');
      const passEl = document.getElementById('passwordInput');
      if (emailEl && passEl) {
        emailEl.value = 'admin@gmail.com';
        passEl.value = 'admin12345678';
        emailEl.focus();
      }
    }
  </script>
@endsection
