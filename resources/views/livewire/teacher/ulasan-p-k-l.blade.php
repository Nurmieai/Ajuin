<x-slot:title>
    Ulasan PKL Siswa
</x-slot:title>

<div class="flex flex-col gap-4">
    {{-- Breadcrumbs --}}
    <x-ui.breadcrumbs :items="[
        'Ulasan Siswa' => [
            'url' => '#',
            'icon' => 'chat-bubble-left-right'
        ]
    ]" />

    {{-- Page Header --}}
    <x-ui.pageheader
        title="Ulasan PKL Siswa"
        subtitle="Ringkasan pengalaman dan feedback PKL dari seluruh siswa" />

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        {{-- Card Total Ulasan --}}
        <x-ui.card
            title="Total Ulasan"
            :value="$totalCount"
            icon="chat-bubble-left-right"
            color="blue" />

        {{-- Card Rata-rata Rating --}}
        <x-ui.card
            title="Rata-rata Rating"
            :value="$avgRating > 0 ? number_format($avgRating, 1) . ' / 5' : '-'"
            icon="star"
            color="yellow" />
    </div>

    {{-- Filter & Search Section --}}
    <div class="flex flex-col lg:flex-row justify-between items-stretch lg:items-center gap-4 w-full">

        {{-- Search Bar: Full width di mobile, auto di desktop --}}
        <div class="w-full lg:max-w-md">
            <x-ui.search wire:model.live.debounce.300ms="search" placeholder="Cari ulasan..." />
        </div>

        {{-- Container Filter: Grid 2 kolom di mobile agar hemat ruang --}}
        <div class="grid grid-cols-2 sm:flex sm:items-center gap-3 lg:gap-4 w-full lg:w-auto">

            {{-- Filter Rating --}}
            <div class="w-full sm:min-w-[160px]">
                <x-ui.input
                    type="select"
                    name="filterRating"
                    wire:model.live="filterRating"
                    placeholder="Semua Rating"
                    :options="[
                    '5' => '⭐ 5 Bintang',
                    '4' => '⭐ 4 Bintang',
                    '3' => '⭐ 3 Bintang',
                    '2' => '⭐ 2 Bintang',
                    '1' => '⭐ 1 Bintang',
                ]"
                    class="select-sm h-[38px] w-full" />
            </div>

            {{-- Urutkan Rating --}}
            <div class="w-full sm:min-w-[160px]">
                <x-ui.input
                    type="select"
                    name="sortRating"
                    wire:model.live="sortRating"
                    placeholder="Urutkan Rating"
                    :options="[
                    'desc' => '↑ Tertinggi',
                    'asc' => '↓ Terendah',
                ]"
                    class="select-sm h-[38px] w-full" />
            </div>

            {{-- Reset Button: Muncul di bawah/samping filter --}}
            @if($filterRating || $sortRating)
            <div class="col-span-2 sm:col-auto flex justify-end">
                <button wire:click="resetFilter"
                    class="btn btn-sm btn-ghost text-red-500 hover:bg-red-50 dark:hover:bg-red-950/30 gap-1 h-[38px] w-full sm:w-auto">
                    <x-ui.icon name="x-mark" size="xs" />
                    Reset
                </button>
            </div>
            @endif
        </div>
    </div>

    {{-- Table Section --}}
    <x-ui.table
        :columns="[
            'Siswa',
            'Perusahaan',
            'Ulasan',
            'Rating',
            'Tanggal'
        ]">
        @forelse($ulasans as $item)
        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
            {{-- Siswa --}}
            <td class="py-4 px-4">
                <div class="flex flex-col">
                    <span class="font-bold text-slate-700 dark:text-slate-200">
                        {{ $item->student->fullname ?? '-' }}
                    </span>
                    <span class="text-[10px] text-slate-500 uppercase italic">
                        NISN: {{ $item->student->nisn ?? '-' }}
                    </span>
                </div>
            </td>

            {{-- Perusahaan --}}
            <td class="py-4 px-4">
                <div class="flex items-center gap-2">
                    <x-ui.icon name="building" size="xs" class="text-slate-400" />
                    <span class="text-sm text-slate-600 dark:text-slate-400">{{ $item->submission->company_name ?? '-' }}</span>
                </div>
            </td>

            {{-- Judul + Isi --}}
            <td class="py-4 px-4 max-w-xs">
                <p class="font-bold text-sm text-slate-800 dark:text-slate-200">{{ $item->judul }}</p>
                <p class="text-xs text-slate-500 mt-1 line-clamp-2 leading-relaxed italic">"{{ $item->isi }}"</p>
            </td>

            {{-- Rating --}}
            <td class="py-4 px-4">
                <div class="flex items-center gap-0.5">
                    @for($i = 1; $i
                    <= 5; $i++)
                        <x-ui.icon name="star"
                        class="size-3.5 {{ $i <= $item->rating ? 'text-yellow-400 fill-yellow-400' : 'text-slate-200 dark:text-slate-700' }}" />
                    @endfor
                    <span class="text-[11px] font-bold text-slate-500 ml-1.5 bg-slate-100 dark:bg-slate-800 px-1.5 py-0.5 rounded">{{ $item->rating }}/5</span>
                </div>
            </td>

            {{-- Tanggal --}}
            <td class="py-4 px-4">
                <span class="text-xs text-slate-500 font-medium">
                    {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d M Y') }}
                </span>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="text-center py-20">
                <div class="flex flex-col items-center justify-center">
                    <x-ui.icon name="chat-bubble-left-right" size="xl" class="text-slate-200 dark:text-slate-800 mb-4" />
                    <p class="text-slate-500 font-bold">Tidak ada ulasan ditemukan</p>
                    <p class="text-slate-400 text-xs mt-1">Ulasan akan muncul di sini sesuai filter yang dipilih.</p>
                </div>
            </td>
        </tr>
        @endforelse
    </x-ui.table>

    {{-- Pagination --}}
    <div class="mx-auto justify-center">
        {{ $ulasans->links() }}
    </div>
</div>