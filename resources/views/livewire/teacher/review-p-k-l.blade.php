<x-slot:title>
    Ulasan PKL Siswa
</x-slot:title>

<div class="flex flex-col gap-3 h-screen overflow-hidden">
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

    {{-- Total Ulasan Card --}}
    <div class="shrink-0">
        <x-ui.card
            title="Total Ulasan"
            :value="$totalCount"
            icon="chat-bubble-left-right"
            color="blue" />
    </div>

    {{-- Filter Section - Bersebelahan Kecil --}}
    <div class="grid grid-cols-2 gap-2 shrink-0">
        <x-ui.input
            type="select"
            name="filterRating"
            wire:model.live="filterRating"
            
            :options="[
                '' => 'Semua Rating',
                '5' => '⭐ 5',
                '4' => '⭐ 4',
                '3' => '⭐ 3',
                '2' => '⭐ 2',
                '1' => '⭐ 1',
            ]"
            class="select-sm h-[36px] text-sm" />

        <x-ui.input
            type="select"
            name="sortRating"
            wire:model.live="sortRating"
            placeholder="Urutkan"
            :options="[
                'desc' => '↑ Tertinggi',
                'asc' => '↓ Terendah',
            ]"
            class="select-sm h-[36px] text-sm" />
    </div>

    @if($filterRating || $sortRating)
    <div class="shrink-0">
        <button wire:click="resetFilter"
            class="btn btn-sm btn-ghost text-red-500 hover:bg-red-50 dark:hover:bg-red-950/30 gap-1 h-[32px] w-full">
            <x-ui.icon name="x-mark" size="xs" />
            Reset Filter
        </button>
    </div>
    @endif

    {{-- Table Section - Scrollable --}}
    <div class="flex-1 overflow-hidden flex flex-col min-h-0">
        <div class="overflow-y-auto flex-1 border border-slate-200 dark:border-slate-800 rounded-lg">
            <x-ui.table
                :columns="[
                    'Siswa',
                    'Perusahaan',
                    'Ulasan',
                    'Rating',
                    'Tanggal'
                ]">
                @forelse($reviews as $item)
                <tr
                    wire:click="showDetail({{ $item->id }})"
                    class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors cursor-pointer">

                    {{-- Siswa --}}
                    <td class="py-3 px-3">
                        <div class="flex flex-col">
                            <span class="font-bold text-sm text-slate-700 dark:text-slate-200">
                                {{ $item->student->username ?? '-' }}
                            </span>
                            <span class="text-[10px] text-slate-500 uppercase italic">
                                {{ $item->student->nisn ?? '-' }}
                            </span>
                        </div>
                    </td>

                    {{-- Perusahaan --}}
                    <td class="py-3 px-3">
                        <div class="flex items-center gap-1.5">
                            <x-ui.icon name="building" size="xs" class="text-slate-400" />
                            <span class="text-xs text-slate-600 dark:text-slate-400 truncate max-w-[120px]">{{ $item->submission->company_name ?? '-' }}</span>
                        </div>
                    </td>

                    {{-- Judul + Isi --}}
                    <td class="py-3 px-3 max-w-[200px]">
                        <p class="font-bold text-xs text-slate-800 dark:text-slate-200 truncate">{{ $item->judul }}</p>
                        <p class="text-xs text-slate-500 mt-0.5 line-clamp-1 italic">"{{ $item->isi }}"</p>
                    </td>

                    {{-- Rating --}}
                    <td class="py-3 px-3">
                        <div class="flex items-center gap-0.5">
                            @for($i = 1; $i
                            <= 5; $i++)
                                <x-ui.icon name="star"
                                class="size-3 {{ $i <= $item->rating ? 'text-yellow-400 fill-yellow-400' : 'text-slate-200 dark:text-slate-700' }}" />
                            @endfor
                            <span class="text-[10px] font-bold text-slate-500 ml-1 bg-slate-100 dark:bg-slate-800 px-1 rounded">{{ $item->rating }}</span>
                        </div>
                    </td>

                    {{-- Tanggal --}}
                    <td class="py-3 px-3">
                        <span class="text-xs text-slate-500 font-medium">
                            {{ \Carbon\Carbon::parse($item->created_at)->format('d M y') }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-12">
                        <div class="flex flex-col items-center justify-center">
                            <x-ui.icon name="chat-bubble-left-right" size="lg" class="text-slate-200 dark:text-slate-800 mb-2" />
                            <p class="text-slate-500 font-bold text-sm">Tidak ada ulasan</p>
                            <p class="text-slate-400 text-xs mt-0.5">Ulasan akan muncul di sini.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </x-ui.table>
        </div>

        {{-- Pagination --}}
        <div class="mt-2 shrink-0">
            {{ $reviews->links() }}
        </div>
    </div>

    {{-- MODAL DETAIL ULASAN - Scrollable --}}
    @if($selectedReview)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        {{-- Backdrop --}}
        <div
            class="absolute inset-0 bg-black/60 backdrop-blur-sm"
            wire:click="closeDetail">
        </div>

        {{-- Modal Panel --}}
        <div class="relative bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 shadow-2xl rounded-xl w-full max-w-md flex flex-col max-h-[80vh]">

            {{-- Header --}}
            <div class="p-4 border-b border-slate-200 dark:border-slate-800 flex justify-between items-start bg-slate-50/50 dark:bg-slate-900/50 shrink-0">
                <div class="min-w-0">
                    <h3 class="text-base font-bold text-slate-800 dark:text-slate-100 truncate">{{ $selectedReview->judul }}</h3>
                    <p class="text-xs text-slate-500 mt-0.5 flex items-center gap-1">
                        <x-ui.icon name="building" size="xs" />
                        <span class="truncate">{{ $selectedReview->submission->company_name ?? '-' }}</span>
                    </p>
                </div>
                <button wire:click="closeDetail" class="btn btn-sm btn-circle btn-ghost shrink-0 ml-2">✕</button>
            </div>

            {{-- Body - Scrollable --}}
            <div class="p-4 space-y-4 overflow-y-auto">
                {{-- Info Siswa --}}
                <div class="flex items-center gap-3 bg-slate-50 dark:bg-slate-900 rounded-lg p-3 border border-slate-100 dark:border-slate-800">
                    <div class="bg-primary/10 text-primary rounded-full w-9 h-9 flex items-center justify-center font-bold text-base shrink-0">
                        {{ strtoupper(substr($selectedReview->student->username ?? $selectedReview->student->name ?? '?', 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="font-bold text-sm text-slate-800 dark:text-slate-200 truncate">
                            {{ $selectedReview->student->username ?? $selectedReview->student->name ?? '-' }}
                        </p>
                        <p class="text-xs text-slate-500">{{ $selectedReview->student->nisn ?? '-' }}</p>
                    </div>
                </div>

                {{-- Rating --}}
                <div class="flex items-center gap-2">
                    <div class="flex items-center gap-0.5">
                        @for($i = 1; $i
                        <= 5; $i++)
                            <x-ui.icon name="star"
                            class="size-4 {{ $i <= $selectedReview->rating ? 'text-yellow-400 fill-yellow-400' : 'text-slate-200 dark:text-slate-700' }}" />
                        @endfor
                    </div>
                    <span class="font-bold text-slate-700 dark:text-slate-300">{{ $selectedReview->rating }}/5</span>
                </div>

                {{-- Isi Ulasan --}}
                <div class="bg-slate-50 dark:bg-slate-900 rounded-lg p-3 border border-slate-100 dark:border-slate-800">
                    <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed italic">"{{ $selectedReview->isi }}"</p>
                </div>

                {{-- Tanggal --}}
                <p class="text-xs text-slate-400 text-right">
                    {{ \Carbon\Carbon::parse($selectedReview->created_at)->translatedFormat('d M Y, H:i') }}
                </p>
            </div>
        </div>
    </div>
    @endif

</div>