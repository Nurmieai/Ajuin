@props([
'columns' => [],
'flatRight' => false,
'flatLeft' => false,
])

@php
// Inisialisasi class default (semua sisi bulat)
$wrapperClass = 'rounded-xl';
$theadClass = 'rounded-t-xl';

// LOGIKA MOBILE:
// Jika flatLeft aktif, hilangkan rounded kiri atas.
// Jika flatRight aktif, hilangkan rounded kanan atas.
// Di mobile (default class), kita pakai rounded spesifik.

if ($flatLeft && $flatRight) {
// Jika keduanya aktif di mobile, hanya bawah yang bulat
$wrapperClass = 'rounded-b-xl';
$theadClass = '';
} elseif ($flatLeft) {
// Flat kiri di mobile, tapi di desktop (md) dipaksa bulat kembali
$wrapperClass = 'rounded-tr-xl rounded-b-xl md:rounded-xl';
$theadClass = 'rounded-tr-xl md:rounded-t-xl';
} elseif ($flatRight) {
// Flat kanan berlaku di mobile DAN tetap flat di desktop
$wrapperClass = 'rounded-tl-xl rounded-b-xl';
$theadClass = 'rounded-tl-xl';
}
@endphp

<div class="{{ $wrapperClass }} overflow-hidden border border-slate-200 dark:border-slate-800 theme-transition">
    <div class="overflow-x-auto">
        <table
            class="table w-full bg-white dark:bg-slate-950 {{ $wrapperClass }} shadow-sm theme-transition">

            <thead>
                <tr class="bg-slate-50 dark:bg-slate-900 text-slate-700 dark:text-slate-300 {{ $theadClass }} overflow-hidden theme-transition">

                    @foreach ($columns as $column)
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider theme-transition">
                        {{ $column }}
                    </th>
                    @endforeach

                </tr>
            </thead>

            <tbody class="divide-y divide-slate-200 dark:divide-slate-800 text-slate-700 dark:text-slate-300 theme-transition">
                {{ $slot }}
            </tbody>
        </table>
    </div>
</div>