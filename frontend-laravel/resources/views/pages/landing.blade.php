@extends('layouts.guest')

@section('title', 'Klasifikasi Churn Pelanggan - Random Forest + PSO')

@section('content')
  <div 
    x-data="{ 
      showScrollTop: false,
      scrollTo(id) {
        const el = document.getElementById(id);
        if (el) {
          el.scrollIntoView({ behavior: 'smooth' });
        }
      }
    }"
    x-init="$nextTick(() => { if (window.lucide) window.lucide.createIcons(); })"
    x-on:scroll.window="showScrollTop = (window.pageYOffset > 250)"
    class="relative min-h-screen overflow-x-hidden"
    style="font-family: 'Poppins', sans-serif; background-color: #FAFCFF; color: #334155;"
  >
    <!-- Organic Soft Ambient Glows -->
    <div style="position: absolute; left: -80px; top: -80px; width: 450px; height: 450px; border-radius: 50%; background: rgba(37,99,235,0.06); filter: blur(80px); pointer-events: none;"></div>
    <div style="position: absolute; right: 0; top: 0; width: 550px; height: 550px; border-radius: 50%; background: radial-gradient(circle, rgba(59,130,246,0.08) 0%, rgba(99,102,241,0.04) 50%, transparent 70%); filter: blur(90px); pointer-events: none;"></div>
    <div style="position: absolute; left: -100px; top: 1100px; width: 500px; height: 500px; border-radius: 50%; background: rgba(20,184,166,0.05); filter: blur(90px); pointer-events: none;"></div>

    <!-- Header Navigation -->
    <header style="max-width: 1140px; margin: 0 auto; padding: 24px 20px; display: flex; align-items: center; justify-content: space-between; position: relative; z-index: 20;">
      <a href="{{ route('landing') }}" style="display: flex; align-items: center; gap: 4px; text-decoration: none;">
        <span style="font-size: 22px; font-weight: 700; color: #1E293B; letter-spacing: -0.5px;">
          KlasifikasiChurn<span style="color: #2563EB;">.</span>
        </span>
      </a>

      <nav style="display: flex; align-items: center; gap: 24px;">
        <div style="display: flex; align-items: center; gap: 20px;" class="hidden sm:flex">
          <button type="button" x-on:click="scrollTo('hero')" style="background: none; border: none; font-size: 13px; font-weight: 500; color: #64748B; cursor: pointer; transition: color 0.2s;" onmouseover="this.style.color='#2563EB'" onmouseout="this.style.color='#64748B'">Beranda</button>
          <button type="button" x-on:click="scrollTo('layanan')" style="background: none; border: none; font-size: 13px; font-weight: 500; color: #64748B; cursor: pointer; transition: color 0.2s;" onmouseover="this.style.color='#2563EB'" onmouseout="this.style.color='#64748B'">Layanan</button>
          <button type="button" x-on:click="scrollTo('fitur')" style="background: none; border: none; font-size: 13px; font-weight: 500; color: #64748B; cursor: pointer; transition: color 0.2s;" onmouseover="this.style.color='#2563EB'" onmouseout="this.style.color='#64748B'">Fitur</button>
          <button type="button" x-on:click="scrollTo('keunggulan')" style="background: none; border: none; font-size: 13px; font-weight: 500; color: #64748B; cursor: pointer; transition: color 0.2s;" onmouseover="this.style.color='#2563EB'" onmouseout="this.style.color='#64748B'">Keunggulan</button>
        </div>
        <div style="display: flex; align-items: center; gap: 12px;">
          <a href="{{ route('login') }}" style="font-size: 13px; font-weight: 500; color: #475569; text-decoration: none; padding: 8px 12px; transition: color 0.2s;" onmouseover="this.style.color='#2563EB'" onmouseout="this.style.color='#475569'">Masuk</a>
          <a href="{{ route('register') }}" style="background: #2563EB; color: #ffffff; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; padding: 10px 22px; border-radius: 50px; text-decoration: none; box-shadow: 0 4px 14px rgba(37,99,235,0.25); transition: all 0.2s ease; display: inline-block;" onmouseover="this.style.background='#1D4ED8'; this.style.transform='translateY(-1px)';" onmouseout="this.style.background='#2563EB'; this.style.transform='translateY(0)';">
            Mulai Sekarang
          </a>
        </div>
      </nav>
    </header>

    <!-- Main Content -->
    <main style="position: relative; z-index: 10;">
      
      <!-- 1. Hero Section -->
      <section id="hero" style="max-width: 1140px; margin: 0 auto; padding: 30px 20px 80px;">
        <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 40px;">
          
          <!-- Left Column -->
          <div style="flex: 1 1 500px; min-width: 300px; max-width: 580px;">
            <h1 style="font-size: clamp(30px, 3.8vw, 44px); font-weight: 600; line-height: 1.22; color: #1E293B; letter-spacing: -0.5px; margin: 0;">
              Solusi Cerdas untuk Klasifikasi & Retensi Pelanggan
            </h1>

            <p style="font-size: 14px; line-height: 1.7; color: #64748B; margin-top: 18px; max-width: 480px;">
              Sistem Klasifikasi Churn adalah platform intelligence machine learning berbasis Random Forest + PSO untuk membantu mengklasifikasikan risiko churn dan menjaga loyalitas pelanggan bisnis Anda.
            </p>

            <!-- Pill Input Box -->
            <div style="margin-top: 32px; max-width: 440px;">
              <form action="{{ route('register') }}" method="GET" style="display: flex; align-items: center; background: #ffffff; border: 1.5px solid #E2E8F0; border-radius: 50px; padding: 5px 5px 5px 16px; box-shadow: 0 2px 10px rgba(15,23,42,0.04); transition: border-color 0.2s;" onfocuswithin="this.style.borderColor='#3B82F6'">
                <i data-lucide="mail" style="width: 16px; height: 16px; color: #94A3B8; flex-shrink: 0;"></i>
                <input 
                  type="email" 
                  name="email"
                  placeholder="Masukkan alamat email Anda" 
                  style="border: none; outline: none; background: transparent; padding: 8px 12px; font-size: 13px; font-family: 'Poppins', sans-serif; color: #334155; width: 100%;"
                />
                <button 
                  type="submit"
                  style="background: #2563EB; color: #ffffff; border: none; border-radius: 50px; padding: 10px 22px; font-size: 12px; font-weight: 600; font-family: 'Poppins', sans-serif; text-transform: uppercase; letter-spacing: 0.5px; cursor: pointer; flex-shrink: 0; transition: background 0.2s;"
                  onmouseover="this.style.background='#1D4ED8'"
                  onmouseout="this.style.background='#2563EB'"
                >
                  Mulai
                </button>
              </form>
              <span style="display: block; font-size: 11px; color: #94A3B8; margin-top: 8px; padding-left: 14px;">
                * Uji coba prediksi langsung tanpa instalasi rumit.
              </span>
            </div>
          </div>

          <!-- Right Column (Hero Graphic) -->
          <div style="flex: 1 1 440px; min-width: 300px; display: flex; justify-content: center;">
            <div style="width: 100%; max-width: 500px; background: #ffffff; padding: 8px; border-radius: 28px; box-shadow: 0 20px 50px rgba(37,99,235,0.08); border: 1px solid rgba(226,232,240,0.8);">
              <img 
                src="{{ asset('images/hero-illustration.jpg') }}" 
                alt="Analitik & Klasifikasi Churn Pelanggan AI" 
                style="width: 100%; height: auto; border-radius: 20px; display: block;"
              />
            </div>
          </div>

        </div>
      </section>

      <!-- 2. Section: LAYANAN (Our Offer) -->
      <section id="layanan" style="background: rgba(255,255,255,0.6); border-top: 1px solid #EEF2F6; border-bottom: 1px solid #EEF2F6; padding: 80px 20px;">
        <div style="max-width: 980px; margin: 0 auto; text-align: center;">
          <span style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 2px; color: #2563EB;">
            KEMAMPUAN SISTEM
          </span>
          <h2 style="font-size: 28px; font-weight: 600; color: #1E293B; margin-top: 8px; margin-bottom: 0;">
            Layanan & Kemampuan AI
          </h2>
          <p style="font-size: 13px; color: #64748B; margin-top: 10px; max-width: 540px; margin-left: auto; margin-right: auto; line-height: 1.6;">
            Platform kami dirancang untuk membantu tim bisnis dan analis data memahami pola perilaku pelanggan telekomunikasi secara komprehensif.
          </p>

          <!-- 3 Steps -->
          <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 32px; margin-top: 50px;">
            
            <!-- Step 1 -->
            <div style="flex: 1 1 260px; max-width: 300px; display: flex; flex-direction: column; align-items: center; text-align: center;">
              <div style="width: 64px; height: 64px; border-radius: 20px; background: #ffffff; border: 1.5px solid #E2E8F0; display: flex; align-items: center; justify-content: center; color: #2563EB; box-shadow: 0 4px 12px rgba(15,23,42,0.04);">
                <i data-lucide="bar-chart-3" style="width: 28px; height: 28px;"></i>
              </div>
              <h3 style="font-size: 16px; font-weight: 600; color: #1E293B; margin-top: 18px; margin-bottom: 6px;">Deteksi & Analisis</h3>
              <p style="font-size: 12px; color: #64748B; line-height: 1.6; margin: 0;">
                Pindai 18 variabel penggunaan layanan telepon dan interaksi pelanggan secara akurat.
              </p>
            </div>

            <!-- Step 2 -->
            <div style="flex: 1 1 260px; max-width: 300px; display: flex; flex-direction: column; align-items: center; text-align: center;">
              <div style="width: 64px; height: 64px; border-radius: 20px; background: #ffffff; border: 1.5px solid #E2E8F0; display: flex; align-items: center; justify-content: center; color: #2563EB; box-shadow: 0 4px 12px rgba(15,23,42,0.04);">
                <i data-lucide="cpu" style="width: 28px; height: 28px;"></i>
              </div>
              <h3 style="font-size: 16px; font-weight: 600; color: #1E293B; margin-top: 18px; margin-bottom: 6px;">Optimasi PSO</h3>
              <p style="font-size: 12px; color: #64748B; line-height: 1.6; margin: 0;">
                Tingkatkan presisi klasifikasi dengan penalaan Particle Swarm Optimization.
              </p>
            </div>

            <!-- Step 3 -->
            <div style="flex: 1 1 260px; max-width: 300px; display: flex; flex-direction: column; align-items: center; text-align: center;">
              <div style="width: 64px; height: 64px; border-radius: 20px; background: #ffffff; border: 1.5px solid #E2E8F0; display: flex; align-items: center; justify-content: center; color: #2563EB; box-shadow: 0 4px 12px rgba(15,23,42,0.04);">
                <i data-lucide="trending-up" style="width: 28px; height: 28px;"></i>
              </div>
              <h3 style="font-size: 16px; font-weight: 600; color: #1E293B; margin-top: 18px; margin-bottom: 6px;">Retensi & Pertumbuhan</h3>
              <p style="font-size: 12px; color: #64748B; line-height: 1.6; margin: 0;">
                Dapatkan rekomendasi tindakan pencegahan churn sebelum pelanggan berpindah.
              </p>
            </div>

          </div>
        </div>
      </section>

      <!-- 3. Section: FITUR (Features) -->
      <section id="fitur" style="max-width: 1140px; margin: 0 auto; padding: 90px 20px;">
        <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: flex-end; gap: 20px; margin-bottom: 40px;">
          <div>
            <span style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 2px; color: #2563EB;">
              FITUR UTAMA
            </span>
            <h2 style="font-size: 28px; font-weight: 600; color: #1E293B; margin-top: 6px; margin-bottom: 0;">
              Fitur Unggulan Sistem
            </h2>
            <p style="font-size: 13px; color: #64748B; margin-top: 8px; margin-bottom: 0;">
              Eksplorasi fitur-fitur intelligence yang mempermudah proses prediksi risiko pelanggan.
            </p>
          </div>
          <a href="{{ route('register') }}" style="font-size: 12px; font-weight: 600; color: #2563EB; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
            <span>Lihat Seluruh Fitur</span>
            <i data-lucide="arrow-right" style="width: 14px; height: 14px;"></i>
          </a>
        </div>

        <!-- 4 Cards Grid -->
        <div style="display: flex; flex-wrap: wrap; gap: 24px;">
          
          <!-- Card 1 -->
          <div style="flex: 1 1 240px; background: #ffffff; border: 1px solid #E2E8F0; border-radius: 20px; padding: 26px; box-shadow: 0 4px 20px rgba(15,23,42,0.03); display: flex; flex-direction: column; justify-content: space-between; transition: all 0.2s ease;" onmouseover="this.style.boxShadow='0 10px 30px rgba(37,99,235,0.08)'; this.style.transform='translateY(-2px)';" onmouseout="this.style.boxShadow='0 4px 20px rgba(15,23,42,0.03)'; this.style.transform='translateY(0)';">
            <div>
              <div style="width: 48px; height: 48px; border-radius: 14px; background: #EFF6FF; display: flex; align-items: center; justify-content: center; color: #2563EB;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="m12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.9a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83Z"/>
                  <path d="m22 17.65-9.17 4.16a2 2 0 0 1-1.66 0L2 17.65"/>
                  <path d="m22 12.65-9.17 4.16a2 2 0 0 1-1.66 0L2 12.65"/>
                </svg>
              </div>
              <h3 style="font-size: 15px; font-weight: 600; color: #1E293B; margin-top: 20px; margin-bottom: 8px;">18 Variabel Analisis</h3>
              <p style="font-size: 12px; color: #64748B; line-height: 1.6; margin: 0;">
                Pemrosesan komprehensif mulai dari total menit harian, paket internasional, hingga keluhan CS.
              </p>
            </div>
            <div style="margin-top: 20px; padding-top: 14px; border-top: 1px solid #F1F5F9;">
              <a href="{{ route('login') }}" style="font-size: 11px; font-weight: 600; color: #2563EB; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                <span>Pelajari Fitur</span>
                <i data-lucide="chevron-right" style="width: 14px; height: 14px;"></i>
              </a>
            </div>
          </div>

          <!-- Card 2 -->
          <div style="flex: 1 1 240px; background: #ffffff; border: 1px solid #E2E8F0; border-radius: 20px; padding: 26px; box-shadow: 0 4px 20px rgba(15,23,42,0.03); display: flex; flex-direction: column; justify-content: space-between; transition: all 0.2s ease;" onmouseover="this.style.boxShadow='0 10px 30px rgba(245,158,11,0.08)'; this.style.transform='translateY(-2px)';" onmouseout="this.style.boxShadow='0 4px 20px rgba(15,23,42,0.03)'; this.style.transform='translateY(0)';">
            <div>
              <div style="width: 48px; height: 48px; border-radius: 14px; background: #FFFBEB; display: flex; align-items: center; justify-content: center; color: #D97706;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <line x1="4" x2="4" y1="21" y2="14"/>
                  <line x1="4" x2="4" y1="10" y2="3"/>
                  <line x1="12" x2="12" y1="21" y2="12"/>
                  <line x1="12" x2="12" y1="8" y2="3"/>
                  <line x1="20" x2="20" y1="21" y2="16"/>
                  <line x1="20" x2="20" y1="12" y2="3"/>
                  <line x1="2" x2="6" y1="14" y2="14"/>
                  <line x1="10" x2="14" y1="8" y2="8"/>
                  <line x1="18" x2="22" y1="16" y2="16"/>
                </svg>
              </div>
              <h3 style="font-size: 15px; font-weight: 600; color: #1E293B; margin-top: 20px; margin-bottom: 8px;">Simulasi What-If</h3>
              <p style="font-size: 12px; color: #64748B; line-height: 1.6; margin: 0;">
                Uji perubahan parameter panggilan dan amati pergeseran skor risiko churn secara seketika.
              </p>
            </div>
            <div style="margin-top: 20px; padding-top: 14px; border-top: 1px solid #F1F5F9;">
              <a href="{{ route('login') }}" style="font-size: 11px; font-weight: 600; color: #D97706; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                <span>Pelajari Fitur</span>
                <i data-lucide="chevron-right" style="width: 14px; height: 14px;"></i>
              </a>
            </div>
          </div>

          <!-- Card 3 -->
          <div style="flex: 1 1 240px; background: #ffffff; border: 1px solid #E2E8F0; border-radius: 20px; padding: 26px; box-shadow: 0 4px 20px rgba(15,23,42,0.03); display: flex; flex-direction: column; justify-content: space-between; transition: all 0.2s ease;" onmouseover="this.style.boxShadow='0 10px 30px rgba(13,148,136,0.08)'; this.style.transform='translateY(-2px)';" onmouseout="this.style.boxShadow='0 4px 20px rgba(15,23,42,0.03)'; this.style.transform='translateY(0)';">
            <div>
              <div style="width: 48px; height: 48px; border-radius: 14px; background: #F0FDFA; display: flex; align-items: center; justify-content: center; color: #0D9488;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z"/>
                </svg>
              </div>
              <h3 style="font-size: 15px; font-weight: 600; color: #1E293B; margin-top: 20px; margin-bottom: 8px;">FastAPI Real-time</h3>
              <p style="font-size: 12px; color: #64748B; line-height: 1.6; margin: 0;">
                Microservice Python berkecepatan tinggi yang mengeksekusi inferensi model dalam hitungan milidetik.
              </p>
            </div>
            <div style="margin-top: 20px; padding-top: 14px; border-top: 1px solid #F1F5F9;">
              <a href="{{ route('login') }}" style="font-size: 11px; font-weight: 600; color: #0D9488; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                <span>Pelajari Fitur</span>
                <i data-lucide="chevron-right" style="width: 14px; height: 14px;"></i>
              </a>
            </div>
          </div>

          <!-- Card 4 -->
          <div style="flex: 1 1 240px; background: #ffffff; border: 1px solid #E2E8F0; border-radius: 20px; padding: 26px; box-shadow: 0 4px 20px rgba(15,23,42,0.03); display: flex; flex-direction: column; justify-content: space-between; transition: all 0.2s ease;" onmouseover="this.style.boxShadow='0 10px 30px rgba(99,102,241,0.08)'; this.style.transform='translateY(-2px)';" onmouseout="this.style.boxShadow='0 4px 20px rgba(15,23,42,0.03)'; this.style.transform='translateY(0)';">
            <div>
              <div style="width: 48px; height: 48px; border-radius: 14px; background: #EEF2FF; display: flex; align-items: center; justify-content: center; color: #4F46E5;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/>
                  <path d="M14 2v4a2 2 0 0 0 2 2h4"/>
                  <path d="M10 9H8"/>
                  <path d="M16 13H8"/>
                  <path d="M16 17H8"/>
                </svg>
              </div>
              <h3 style="font-size: 15px; font-weight: 600; color: #1E293B; margin-top: 20px; margin-bottom: 8px;">Laporan & Ekspor</h3>
              <p style="font-size: 12px; color: #64748B; line-height: 1.6; margin: 0;">
                Arsip riwayat prediksi terpusat lengkap dengan fitur pencarian dan unduh format CSV.
              </p>
            </div>
            <div style="margin-top: 20px; padding-top: 14px; border-top: 1px solid #F1F5F9;">
              <a href="{{ route('login') }}" style="font-size: 11px; font-weight: 600; color: #4F46E5; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                <span>Pelajari Fitur</span>
                <i data-lucide="chevron-right" style="width: 14px; height: 14px;"></i>
              </a>
            </div>
          </div>

        </div>
      </section>

      <!-- 4. Section: KEUNGGULAN (Benefits) -->
      <section id="keunggulan" style="background: rgba(255,255,255,0.6); border-top: 1px solid #EEF2F6; border-bottom: 1px solid #EEF2F6; padding: 80px 20px;">
        <div style="max-width: 1140px; margin: 0 auto;">
          <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 40px;">
            
            <!-- Left Column: Growth Roadmap Mountain Graphic -->
            <div style="flex: 1 1 440px; min-width: 300px; display: flex; justify-content: center;">
              <div style="width: 100%; max-width: 480px; background: #ffffff; padding: 8px; border-radius: 28px; box-shadow: 0 20px 50px rgba(37,99,235,0.08); border: 1px solid rgba(226,232,240,0.8);">
                <img 
                  src="{{ asset('images/benefits-illustration.jpg') }}" 
                  alt="Roadmap Pertumbuhan Retensi Pelanggan" 
                  style="width: 100%; height: auto; border-radius: 20px; display: block;"
                />
              </div>
            </div>

            <!-- Right Column: Benefits Checklist -->
            <div style="flex: 1 1 500px; min-width: 300px; max-width: 560px;">
              <span style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 2px; color: #2563EB;">
                KEUNGGULAN UTAMA
              </span>
              <h2 style="font-size: 28px; font-weight: 600; color: #1E293B; margin-top: 8px; margin-bottom: 0;">
                Manfaat & Nilai Tambah
              </h2>
              <p style="font-size: 13px; color: #64748B; margin-top: 12px; line-height: 1.7;">
                Sistem Klasifikasi Churn memberdayakan pengambil keputusan bisnis telekomunikasi dengan metrik akurat guna mengklasifikasikan risiko churn dan meningkatkan nilai loyalitas pelanggan.
              </p>

              <!-- 2x2 Grid -->
              <div style="display: flex; flex-wrap: wrap; gap: 16px; margin-top: 28px;">
                
                <div style="flex: 1 1 240px; display: flex; align-items: flex-start; gap: 10px;">
                  <span style="width: 22px; height: 22px; border-radius: 50%; background: #EFF6FF; color: #2563EB; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 2px;">
                    <i data-lucide="check" style="width: 13px; height: 13px;"></i>
                  </span>
                  <div>
                    <h4 style="font-size: 13px; font-weight: 600; color: #1E293B; margin: 0;">Model Akurasi Teruji</h4>
                    <p style="font-size: 11px; color: #64748B; margin-top: 2px; margin-bottom: 0;">Random Forest & PSO yang tervalidasi.</p>
                  </div>
                </div>

                <div style="flex: 1 1 240px; display: flex; align-items: flex-start; gap: 10px;">
                  <span style="width: 22px; height: 22px; border-radius: 50%; background: #EFF6FF; color: #2563EB; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 2px;">
                    <i data-lucide="check" style="width: 13px; height: 13px;"></i>
                  </span>
                  <div>
                    <h4 style="font-size: 13px; font-weight: 600; color: #1E293B; margin: 0;">Faktor Signifikan</h4>
                    <p style="font-size: 11px; color: #64748B; margin-top: 2px; margin-bottom: 0;">Identifikasi akar pemicu risiko churn.</p>
                  </div>
                </div>

                <div style="flex: 1 1 240px; display: flex; align-items: flex-start; gap: 10px;">
                  <span style="width: 22px; height: 22px; border-radius: 50%; background: #EFF6FF; color: #2563EB; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 2px;">
                    <i data-lucide="check" style="width: 13px; height: 13px;"></i>
                  </span>
                  <div>
                    <h4 style="font-size: 13px; font-weight: 600; color: #1E293B; margin: 0;">Saran Retensi Cepat</h4>
                    <p style="font-size: 11px; color: #64748B; margin-top: 2px; margin-bottom: 0;">Aksi taktis mencegah perpindahan.</p>
                  </div>
                </div>

                <div style="flex: 1 1 240px; display: flex; align-items: flex-start; gap: 10px;">
                  <span style="width: 22px; height: 22px; border-radius: 50%; background: #EFF6FF; color: #2563EB; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 2px;">
                    <i data-lucide="check" style="width: 13px; height: 13px;"></i>
                  </span>
                  <div>
                    <h4 style="font-size: 13px; font-weight: 600; color: #1E293B; margin: 0;">Manajemen Pengguna</h4>
                    <p style="font-size: 11px; color: #64748B; margin-top: 2px; margin-bottom: 0;">Sistem login & otorisasi profil aman.</p>
                  </div>
                </div>

              </div>

              <div style="margin-top: 32px;">
                <a 
                  href="{{ route('register') }}" 
                  style="background: #2563EB; color: #ffffff; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; padding: 12px 28px; border-radius: 50px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 14px rgba(37,99,235,0.25); transition: background 0.2s;"
                  onmouseover="this.style.background='#1D4ED8'"
                  onmouseout="this.style.background='#2563EB'"
                >
                  <span>Mulai Sekarang</span>
                  <i data-lucide="arrow-right" style="width: 14px; height: 14px;"></i>
                </a>
              </div>
            </div>

          </div>
        </div>
      </section>

      <!-- 5. Bottom CTA Banner -->
      <section style="max-width: 1140px; margin: 0 auto; padding: 70px 20px 90px;">
        <div style="background: linear-gradient(135deg, #1E40AF 0%, #2563EB 50%, #4338CA 100%); border-radius: 28px; padding: 48px 40px; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 24px; box-shadow: 0 20px 50px rgba(37,99,235,0.18);">
          <div>
            <h3 style="font-size: 24px; font-weight: 600; color: #ffffff; margin: 0; letter-spacing: -0.3px;">
              Siap mengoptimalkan retensi pelanggan Anda?
            </h3>
            <p style="font-size: 13px; color: #DBEAFE; margin-top: 8px; margin-bottom: 0; line-height: 1.6;">
              Mulai uji coba klasifikasi churn sekarang dengan model machine learning terintegrasi.
            </p>
          </div>
          <a 
            href="{{ route('register') }}" 
            style="background: #ffffff; color: #1D4ED8; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; padding: 14px 28px; border-radius: 50px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 14px rgba(0,0,0,0.06); flex-shrink: 0; transition: background 0.2s;"
            onmouseover="this.style.background='#F0F9FF'"
            onmouseout="this.style.background='#ffffff'"
          >
            <i data-lucide="sparkles" style="width: 15px; height: 15px;"></i>
            <span>Buat Akun Gratis</span>
          </a>
        </div>
      </section>

    </main>

    <!-- Floating Soft Scroll-To-Top Button -->
    <button
      type="button"
      x-show="showScrollTop"
      x-transition
      x-on:click="window.scrollTo({ top: 0, behavior: 'smooth' })"
      style="position: fixed; bottom: 24px; right: 24px; z-index: 50; width: 44px; height: 44px; border-radius: 14px; background: rgba(255,255,255,0.92); backdrop-filter: blur(8px); border: 1.5px solid #E2E8F0; color: #2563EB; box-shadow: 0 10px 25px rgba(15,23,42,0.1); cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s;"
      onmouseover="this.style.background='#2563EB'; this.style.color='#ffffff'; this.style.borderColor='#2563EB';"
      onmouseout="this.style.background='rgba(255,255,255,0.92)'; this.style.color='#2563EB'; this.style.borderColor='#E2E8F0';"
      title="Kembali ke atas"
      aria-label="Scroll to top"
    >
      <i data-lucide="chevron-up" style="width: 20px; height: 20px;"></i>
    </button>

    <!-- Footer -->
    <footer style="border-top: 1px solid #E2E8F0; background: #ffffff; padding: 30px 20px;">
      <div style="max-width: 1140px; margin: 0 auto; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 16px; font-size: 12px; color: #94A3B8;">
        <div>
          <span style="font-weight: 700; color: #1E293B;">KlasifikasiChurn<span style="color: #2563EB;">.</span></span>
          <span style="margin-left: 8px;">© {{ now()->year }} Hak Cipta Dilindungi</span>
        </div>
        <div style="display: flex; gap: 20px;">
          <span>Random Forest + PSO</span>
          <span>FastAPI Microservice</span>
          <span>Laravel 11</span>
        </div>
      </div>
    </footer>
  </div>
@endsection
