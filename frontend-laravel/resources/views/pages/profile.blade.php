@extends('layouts.app')

@section('title', 'Profile - ChurnPredict AI')
@section('page-title', 'Profile')

@section('content')
  @php
    $userPhoto = $currentUser['photo_url'] ?? null;
  @endphp

  <div class="sticky top-0 z-20 -mx-5 mb-3 bg-[#F3F6FA] px-5 py-3 sm:-mx-6 sm:px-6">
    <h2 class="text-xl font-bold text-[#102A43]">Profile</h2>
    <p class="mt-1 text-xs font-medium text-[#64748B]">Informasi akun pengguna aplikasi.</p>
  </div>

  @if (session('success_message'))
    <div class="mb-3 rounded-md border border-[#F6C7DA] bg-[#FFF0F6] px-4 py-3 text-[13px] font-medium text-[#DB5A8D]">
      {{ session('success_message') }}
    </div>
  @endif

  @if ($errors->any())
    <div class="mb-3 rounded-md border border-[#e7d599] bg-[#fff9df] px-4 py-3 text-[13px] font-medium text-[#886b14]">
      {{ $errors->first() }}
    </div>
  @endif

  <section class="rounded-md border border-[#D8E2EC] bg-white">
    <div class="border-b border-[#D8E2EC] px-5 py-4">
      <h3 class="text-base font-semibold text-[#102A43]">Detail Akun</h3>
      <p class="mt-1 text-xs text-[#64748B]">Data profil dari akun yang sedang login.</p>
    </div>

    <div class="grid gap-5 p-5 md:grid-cols-[220px_1fr]">
      <div
        class="rounded-md border border-[#D8E2EC] bg-[#F8FAFC] p-4 text-center"
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

        <button type="button" class="mx-auto block rounded-md outline-none ring-[#DB5A8D] transition focus:ring-2" x-on:click="photoMenu = !photoMenu" title="Atur foto profil">
          <template x-if="previewPhoto">
            <img x-bind:src="previewPhoto" alt="Preview foto profil" class="h-20 w-20 rounded-md object-cover">
          </template>

          <template x-if="!previewPhoto">
            @if ($userPhoto)
              <img src="{{ $userPhoto }}" alt="{{ $currentUser['name'] }}" class="h-20 w-20 rounded-md object-cover">
            @else
              <span class="grid h-20 w-20 place-items-center rounded-md bg-[#FFF0F6] text-[#DB5A8D]">
                <i data-lucide="image-off" class="h-8 w-8"></i>
              </span>
            @endif
          </template>
        </button>

        <div x-show="photoMenu" x-transition class="mt-3 grid gap-2">
          <button type="button" class="inline-flex h-8 w-full items-center justify-center gap-2 rounded-md border border-[#D8E2EC] bg-white px-3 text-[11px] font-semibold text-[#475569] transition hover:bg-[#F8FAFC]" x-on:click="$refs.photoInput.click()">
            <i data-lucide="camera" class="h-3.5 w-3.5"></i>
            Pilih Gambar
          </button>

          <button type="submit" form="profile-photo-form" x-show="selectedPhoto" x-transition class="inline-flex h-8 w-full items-center justify-center gap-2 rounded-md bg-[#DB5A8D] px-3 text-[11px] font-semibold text-white transition hover:bg-[#C84A7B]">
            <i data-lucide="upload" class="h-3.5 w-3.5"></i>
            Simpan
          </button>

          <button type="button" x-show="selectedPhoto" x-transition class="inline-flex h-8 w-full items-center justify-center gap-2 rounded-md border border-[#f1b8b8] bg-white px-3 text-[11px] font-semibold text-[#b73535] transition hover:bg-[#fff4f4]" x-on:click="clearSelectedPhoto()">
            <i data-lucide="trash-2" class="h-3.5 w-3.5"></i>
            Batal
          </button>

          @if ($userPhoto)
            <form method="POST" action="{{ route('profile.photo.delete') }}" x-show="!selectedPhoto" x-transition>
              @csrf
              <button type="submit" class="inline-flex h-8 w-full items-center justify-center gap-2 rounded-md border border-[#f1b8b8] bg-white px-3 text-[11px] font-semibold text-[#b73535] transition hover:bg-[#fff4f4]">
                <i data-lucide="trash-2" class="h-3.5 w-3.5"></i>
                Hapus
              </button>
            </form>
          @else
            <button type="button" x-show="!selectedPhoto" class="inline-flex h-8 w-full cursor-not-allowed items-center justify-center gap-2 rounded-md border border-[#D8E2EC] bg-white px-3 text-[11px] font-semibold text-[#94A3B8]" disabled>
              <i data-lucide="trash-2" class="h-3.5 w-3.5"></i>
              Hapus
            </button>
          @endif
        </div>

        <p class="mt-3 text-sm font-bold text-[#102A43]">{{ $currentUser['name'] }}</p>
        <p class="mt-1 text-xs font-medium text-[#64748B]">{{ $currentUser['role'] }}</p>
      </div>

      <dl class="divide-y divide-[#E5EEF8] rounded-md border border-[#D8E2EC]">
        <div class="grid gap-1 px-4 py-3 sm:grid-cols-[150px_1fr]">
          <dt class="text-[13px] font-semibold text-[#64748B]">Nama</dt>
          <dd class="text-[13px] font-semibold text-[#102A43]">{{ $currentUser['name'] }}</dd>
        </div>
        <div class="grid gap-1 px-4 py-3 sm:grid-cols-[150px_1fr]">
          <dt class="text-[13px] font-semibold text-[#64748B]">Email</dt>
          <dd class="text-[13px] font-semibold text-[#102A43]">{{ $currentUser['email'] }}</dd>
        </div>
        <div class="grid gap-1 px-4 py-3 sm:grid-cols-[150px_1fr]">
          <dt class="text-[13px] font-semibold text-[#64748B]">Role</dt>
          <dd class="text-[13px] font-semibold text-[#102A43]">{{ $currentUser['role'] }}</dd>
        </div>
      </dl>
    </div>
  </section>

  <section class="mt-5 rounded-md border border-[#D8E2EC] bg-white">
    <div class="border-b border-[#D8E2EC] px-5 py-4">
      <h3 class="text-base font-semibold text-[#102A43]">Reset Password</h3>
      <p class="mt-1 text-xs text-[#64748B]">Perbarui password akun yang sedang digunakan.</p>
    </div>

    <form method="POST" action="{{ route('profile.password.update') }}" class="grid gap-4 p-5 md:grid-cols-3">
      @csrf
      <label class="block">
        <span class="mb-1.5 block text-xs font-semibold text-[#475569]">Password Saat Ini</span>
        <input name="current_password" type="password" required class="h-10 w-full rounded-md border border-[#D8E2EC] bg-white px-3 text-sm font-medium text-[#102A43] outline-none transition focus:border-[#DB5A8D]">
      </label>

      <label class="block">
        <span class="mb-1.5 block text-xs font-semibold text-[#475569]">Password Baru</span>
        <input name="password" type="password" required class="h-10 w-full rounded-md border border-[#D8E2EC] bg-white px-3 text-sm font-medium text-[#102A43] outline-none transition focus:border-[#DB5A8D]">
      </label>

      <label class="block">
        <span class="mb-1.5 block text-xs font-semibold text-[#475569]">Konfirmasi Password</span>
        <input name="password_confirmation" type="password" required class="h-10 w-full rounded-md border border-[#D8E2EC] bg-white px-3 text-sm font-medium text-[#102A43] outline-none transition focus:border-[#DB5A8D]">
      </label>

      <div class="md:col-span-3">
        <button type="submit" class="inline-flex h-9 items-center justify-center gap-2 rounded-md bg-[#DB5A8D] px-4 text-xs font-semibold text-white transition hover:bg-[#C84A7B]">
          <i data-lucide="lock" class="h-3.5 w-3.5"></i>
          Reset Password
        </button>
      </div>
    </form>
  </section>
@endsection
