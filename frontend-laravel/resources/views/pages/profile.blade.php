@extends('layouts.app')

@section('title', 'Profil Pengguna - ChurnPredict AI')
@section('page-title', 'Profil Pengguna')

@section('content')
  @php
    $userPhoto = $currentUser['photo_url'] ?? null;
  @endphp

  <div class="mb-5 flex flex-col gap-3 border-b border-slate-100/90 pb-4 sm:flex-row sm:items-center sm:justify-between dark:border-slate-800/80">
    <div>
      <h1 class="text-xl font-semibold tracking-tight text-slate-700 dark:text-white">Profil Pengguna</h1>
      <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">Informasi akun dan pengaturan kredensial aplikasi.</p>
    </div>
  </div>

  @if (session('success_message'))
    <div class="mb-4 flex items-center gap-2 rounded-xl border border-emerald-100 bg-emerald-50/80 px-4 py-3 text-xs font-medium text-emerald-800 dark:border-emerald-900/40 dark:bg-emerald-950/30 dark:text-emerald-300">
      <i data-lucide="check-circle" class="h-4 w-4 text-emerald-600 dark:text-emerald-400"></i>
      <span>{{ session('success_message') }}</span>
    </div>
  @endif

  @if ($errors->any())
    <div class="mb-4 flex items-center gap-2 rounded-xl border border-amber-100 bg-amber-50/80 px-4 py-3 text-xs font-medium text-amber-800 dark:border-amber-900/40 dark:bg-amber-950/30 dark:text-amber-300">
      <i data-lucide="alert-circle" class="h-4 w-4 text-amber-600 dark:text-amber-400"></i>
      <span>{{ $errors->first() }}</span>
    </div>
  @endif

  <div class="space-y-6">
    <section class="rounded-xl border border-slate-100/90 bg-white shadow-2xs dark:border-slate-800/80 dark:bg-slate-900">
      <div class="border-b border-slate-100/70 px-5 py-3.5 dark:border-slate-800/60">
        <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Detail Akun</h2>
        <p class="text-[11px] text-slate-500 dark:text-slate-400">Informasi profil dari akun yang sedang aktif.</p>
      </div>

      <div class="grid gap-6 p-5 md:grid-cols-[220px_1fr]">
        <div
          class="rounded-xl border border-slate-100/80 bg-slate-50/50 p-4 text-center dark:border-slate-800/60 dark:bg-slate-800/30"
          x-data="{
            photoMenu: false,
            selectedPhoto: false,
            previewPhoto: null,
            clearSelectedPhoto() {
              this.selectedPhoto = false;
              this.previewPhoto = null;
              this.$refs.photoInput.value = '';
            }
          }"
        >
          <form id="profile-photo-form" method="POST" action="{{ route('profile.photo.update') }}" enctype="multipart/form-data">
            @csrf
            <input
              x-ref="photoInput"
              name="photo"
              type="file"
              accept="image/*"
              required
              class="hidden"
              x-on:change="
                const file = $event.target.files[0];
                selectedPhoto = Boolean(file);
                previewPhoto = file ? URL.createObjectURL(file) : null;
              "
            >
          </form>

          <button type="button" class="mx-auto block rounded-full outline-none ring-indigo-500 transition focus:ring-2" x-on:click="photoMenu = !photoMenu" title="Atur foto profil">
            <template x-if="previewPhoto">
              <img x-bind:src="previewPhoto" alt="Preview foto profil" class="h-20 w-20 rounded-full object-cover shadow-xs">
            </template>

            <template x-if="!previewPhoto">
              @if ($userPhoto)
                <img src="{{ $userPhoto }}" alt="{{ $currentUser['name'] }}" class="h-20 w-20 rounded-full object-cover shadow-xs">
              @else
                <span class="grid h-20 w-20 place-items-center rounded-full bg-indigo-50 font-bold text-indigo-600 dark:bg-indigo-950/60 dark:text-indigo-400">
                  <i data-lucide="user" class="h-8 w-8"></i>
                </span>
              @endif
            </template>
          </button>

          <div x-show="photoMenu" x-transition class="mt-3 grid gap-2">
            <button type="button" class="inline-flex h-8 w-full items-center justify-center gap-2 rounded-lg border border-slate-100 bg-white px-3 text-[11px] font-semibold text-slate-700 shadow-2xs transition hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-800 dark:text-slate-300" x-on:click="$refs.photoInput.click()">
              <i data-lucide="camera" class="h-3.5 w-3.5"></i>
              Pilih Gambar
            </button>

            <button type="submit" form="profile-photo-form" x-show="selectedPhoto" x-transition class="inline-flex h-8 w-full items-center justify-center gap-2 rounded-lg bg-indigo-600 px-3 text-[11px] font-semibold text-white shadow-xs transition hover:bg-indigo-700">
              <i data-lucide="upload" class="h-3.5 w-3.5"></i>
              Simpan
            </button>

            <button type="button" x-show="selectedPhoto" x-transition class="inline-flex h-8 w-full items-center justify-center gap-2 rounded-lg border border-rose-100 bg-white px-3 text-[11px] font-semibold text-rose-600 shadow-2xs transition hover:bg-rose-50 dark:border-rose-900/40 dark:bg-slate-800 dark:text-rose-400" x-on:click="clearSelectedPhoto()">
              <i data-lucide="x" class="h-3.5 w-3.5"></i>
              Batal
            </button>

            @if ($userPhoto)
              <form method="POST" action="{{ route('profile.photo.delete') }}" x-show="!selectedPhoto" x-transition class="m-0">
                @csrf
                <button type="submit" class="inline-flex h-8 w-full items-center justify-center gap-2 rounded-lg border border-rose-100 bg-white px-3 text-[11px] font-semibold text-rose-600 shadow-2xs transition hover:bg-rose-50 dark:border-rose-900/40 dark:bg-slate-800 dark:text-rose-400">
                  <i data-lucide="trash-2" class="h-3.5 w-3.5"></i>
                  Hapus
                </button>
              </form>
            @endif
          </div>

          <p class="mt-3 text-sm font-bold text-slate-900 dark:text-white">{{ $currentUser['name'] }}</p>
          <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">{{ $currentUser['role'] }}</p>
        </div>

        <dl class="divide-y divide-slate-100/80 rounded-xl border border-slate-100/80 bg-white dark:divide-slate-800/60 dark:border-slate-800/60 dark:bg-slate-900">
          <div class="grid gap-1 px-5 py-4 sm:grid-cols-[160px_1fr]">
            <dt class="text-xs font-semibold text-slate-500 dark:text-slate-400">Nama Lengkap</dt>
            <dd class="text-xs font-semibold text-slate-900 dark:text-white">{{ $currentUser['name'] }}</dd>
          </div>
          <div class="grid gap-1 px-5 py-4 sm:grid-cols-[160px_1fr]">
            <dt class="text-xs font-semibold text-slate-500 dark:text-slate-400">Alamat Email</dt>
            <dd class="text-xs font-semibold text-slate-900 dark:text-white">{{ $currentUser['email'] }}</dd>
          </div>
          <div class="grid gap-1 px-5 py-4 sm:grid-cols-[160px_1fr]">
            <dt class="text-xs font-semibold text-slate-500 dark:text-slate-400">Hak Akses (Role)</dt>
            <dd class="text-xs font-semibold text-slate-900 dark:text-white">{{ $currentUser['role'] }}</dd>
          </div>
        </dl>
      </div>
    </section>

    <section class="rounded-xl border border-slate-100/90 bg-white shadow-2xs dark:border-slate-800/80 dark:bg-slate-900">
      <div class="border-b border-slate-100/70 px-5 py-3.5 dark:border-slate-800/60">
        <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Ganti Password</h2>
        <p class="text-[11px] text-slate-500 dark:text-slate-400">Perbarui kata sandi akun untuk keamanan login.</p>
      </div>

      <form method="POST" action="{{ route('profile.password.update') }}" class="grid gap-4 p-5 md:grid-cols-3">
        @csrf
        <label class="block">
          <span class="mb-1.5 block text-xs font-medium text-slate-600 dark:text-slate-300">Password Saat Ini</span>
          <input name="current_password" type="password" required class="h-9 w-full rounded-lg border border-slate-200/60 bg-white px-3 text-xs text-slate-800 outline-none transition focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-slate-700/70 dark:bg-slate-900 dark:text-white">
        </label>

        <label class="block">
          <span class="mb-1.5 block text-xs font-medium text-slate-600 dark:text-slate-300">Password Baru</span>
          <input name="password" type="password" required class="h-9 w-full rounded-lg border border-slate-200/60 bg-white px-3 text-xs text-slate-800 outline-none transition focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-slate-700/70 dark:bg-slate-900 dark:text-white">
        </label>

        <label class="block">
          <span class="mb-1.5 block text-xs font-medium text-slate-600 dark:text-slate-300">Konfirmasi Password Baru</span>
          <input name="password_confirmation" type="password" required class="h-9 w-full rounded-lg border border-slate-200/60 bg-white px-3 text-xs text-slate-800 outline-none transition focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-slate-700/70 dark:bg-slate-900 dark:text-white">
        </label>

        <div class="md:col-span-3">
          <button type="submit" class="inline-flex h-9 items-center justify-center gap-2 rounded-lg bg-indigo-600 px-4 text-xs font-semibold text-white shadow-xs transition hover:bg-indigo-700 active:scale-[0.99] dark:shadow-none">
            <i data-lucide="lock" class="h-3.5 w-3.5"></i>
            Perbarui Password
          </button>
        </div>
      </form>
    </section>
  </div>
@endsection
