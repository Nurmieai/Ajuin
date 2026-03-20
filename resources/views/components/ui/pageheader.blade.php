@props(['title' => null, 'subtitle' => null])

@php
$user = auth()->user();

// Tentukan role mana yang sedang aktif (asumsi satu user satu role utama)
$activeRole = $user->hasRole('teacher') ? 'teacher' : ($user->hasRole('student') ? 'student' : null);

// Logika Judul (Title)
$displayTitle = is_array($title)
? ($title[$activeRole] ?? 'Judul Default')
: ($title ?? ($activeRole === 'teacher' ? 'Kelola Pengajuan' : 'Pengajuan Saya'));

// Logika Subtitle
$displaySubtitle = is_array($subtitle)
? ($subtitle[$activeRole] ?? 'Deskripsi Default')
: ($subtitle ?? ($activeRole === 'teacher' ? 'Kelola semua pengajuan PKL siswa' : 'Kelola semua pengajuan PKL Anda'));
@endphp

<div {{ $attributes->merge(['class' => 'mb-2']) }}>
    <p class="text-md md:text-2xl font-bold text-slate-800 dark:text-slate-100 theme-transition">
        {{ $displayTitle }}
    </p>
    <p class="text-xs md:text-sm text-slate-500 dark:text-slate-400 mt-1 theme-transition">
        {{ $displaySubtitle }}
    </p>
</div>