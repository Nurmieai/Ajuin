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

    {{-- Card Section --}}
    <div class="grid grid-cols-2 gap-2 shrink-0">
        {{-- Total Ulasan --}}
        <x-ui.card
            title="Total Ulasan"
            :value="$totalCount"
            icon="chat-bubble-left-right"
            color="blue" />

        {{-- Rating Perusahaan - hanya muncul saat search perusahaan spesifik --}}
        @if($companyRatingCard)
        <x-ui.card
            :title="'Rating ' . \Illuminate\Support\Str::limit($companyRatingCard['name'], 15)"
            :value="$companyRatingCard['avg'] . ' / 5'"
            icon="star"
            color="yellow" />
        @else
        {{-- Placeholder card saat tidak search perusahaan --}}
        <div class="rounded-xl border border-dashed border-slate-200 dark:border-slate-800 flex flex-col items-center justify-center p-3 gap-1">
            <x-ui.icon name="building-office" size="sm" class="text-slate-300 dark:text-slate-700" />
            <p class="text-[10px] text-slate-400 text-center leading-tight">Cari nama perusahaan untuk lihat rating-nya</p>
        </div>
        @endif
    </div>

    {{-- Filter Section --}}
    <div class="grid grid-cols-2 gap-2 shrink-0">
        {{-- Search Gabungan --}}
        <x-ui.input
            type="text"
            name="search"
            wire:model.live.debounce.300ms="search"
            placeholder="Cari siswa / perusahaan..."
            class="input-sm h-[36px] text-sm" />

        {{-- Filter Rating --}}
        <x-ui.input
            type="select"
            name="filterRating"
            wire:model.live="filterRating"
            :options="[
                '' => 'Semua Rating',
                '5' => '5 Bintang',
                '4' => '4 Bintang',
                '3' => '3 Bintang',
                '2' => '2 Bintang',
                '1' => '1 Bintang',
            ]"
            class="select-sm h-[36px] text-sm" />
    </div>

    {{-- Reset Filter Button --}}
    @if($search || $filterRating)
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
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <=$item->rating)
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="size-3 text-yellow-400">
                                    <path fill-rule="evenodd"
                                        d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z"
                                        clip-rule="evenodd" />
                                </svg>
                                @else
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor"
                                    class="size-3 text-slate-300 dark:text-slate-600">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.601c-.38-.325-.078-.948.32-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
                                </svg>
                                @endif
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

    {{-- MODAL DETAIL ULASAN --}}
    <template x-teleport="body">
        <dialog id="review_detail_modal"
            class="modal backdrop-blur-sm"
            wire:ignore.self
            x-data
            @open-detail-modal.window="$nextTick(() => $el.showModal())"
            @close-detail-modal.window="$el.close()">
            @if($selectedReview)
            <div class="modal-box w-full max-w-md bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 shadow-xl rounded-xl p-0 overflow-hidden flex flex-col max-h-[90vh]">
                {{-- Header --}}
                <div class="p-6 border-b border-slate-200 dark:border-slate-800 flex justify-between items-start bg-slate-50/50 dark:bg-slate-900/50 shrink-0">
                    <div class="min-w-0">
                        <h3 class="text-xl font-bold text-slate-800 dark:text-slate-100 truncate">{{ $selectedReview->judul }}</h3>
                        <p class="text-xs text-slate-500 mt-1 flex items-center gap-1">
                            <x-ui.icon name="building" size="xs" />
                            <span class="truncate">{{ $selectedReview->submission->company_name ?? '-' }}</span>
                        </p>
                    </div>
                    <button type="button" wire:click="closeDetail" class="btn btn-sm btn-circle btn-ghost">✕</button>
                </div>

                {{-- Body --}}
                <div class="p-8 space-y-6 overflow-y-auto">
                    {{-- Info Siswa --}}
                    <div class="flex items-center gap-3 bg-slate-50 dark:bg-slate-900 rounded-lg p-4 border border-slate-100 dark:border-slate-800">
                        <div class="bg-blue-600/10 text-blue-600 rounded-full w-10 h-10 flex items-center justify-center font-bold text-lg shrink-0">
                            {{ strtoupper(substr($selectedReview->student->username ?? '?', 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="font-bold text-sm text-slate-800 dark:text-slate-200 truncate">
                                {{ $selectedReview->student->username ?? '-' }}
                            </p>
                            <p class="text-xs text-slate-500">{{ $selectedReview->student->nisn ?? '-' }}</p>
                        </div>
                    </div>

                    {{-- Rating --}}
                    <div class="space-y-3">
                        {{-- Rating Siswa (Ulasan Ini) --}}
                        <div class="flex items-center justify-between w-full bg-slate-50/50 dark:bg-slate-900/50 p-3 rounded-lg border border-slate-100 dark:border-slate-800">
                            <span class="text-xs font-semibold text-slate-500">Rating Siswa</span>
                            <div class="flex items-center gap-1.5">
                                <div class="flex items-center gap-0.5">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 {{ $i <= $selectedReview->rating ? 'text-yellow-400' : 'text-slate-300 dark:text-slate-700' }}">
                                        <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z" clip-rule="evenodd" />
                                        </svg>
                                        @endfor
                                </div>
                                <span class="font-bold text-slate-700 dark:text-slate-200 text-sm">{{ $selectedReview->rating }}/5</span>
                            </div>
                        </div>

                        {{-- Rating Rata-rata Perusahaan --}}
                        @php
                        $modalCompanyName = $selectedReview->submission->company_name ?? null;
                        $modalCompanyAvg = null;
                        $modalCompanyCount = 0;
                        if ($modalCompanyName) {
                        $modalCompanyAvg = round(
                        \App\Models\Review::whereHas('submission', fn($q) => $q->where('company_name', $modalCompanyName))->avg('rating'),
                        1
                        );
                        $modalCompanyCount = \App\Models\Review::whereHas('submission', fn($q) => $q->where('company_name', $modalCompanyName))->count();
                        }
                        @endphp
                        @if($modalCompanyAvg)
                        <div class="bg-yellow-50 dark:bg-yellow-950/20 border border-yellow-200 dark:border-yellow-800/40 rounded-lg p-3">
                            <p class="text-[10px] font-semibold text-yellow-700 dark:text-yellow-400 uppercase tracking-wide mb-1.5">
                                Rating Perusahaan ({{ $modalCompanyName }})
                            </p>
                            <div class="flex items-center gap-2">
                                <div class="flex items-center gap-0.5">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <=floor($modalCompanyAvg))
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 text-yellow-400">
                                        <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z" clip-rule="evenodd" />
                                        </svg>
                                        @else
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4 text-yellow-300 dark:text-yellow-800">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.601c-.38-.325-.078-.948.32-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
                                        </svg>
                                        @endif
                                        @endfor
                                </div>
                                <span class="font-bold text-yellow-700 dark:text-yellow-300 text-sm">{{ $modalCompanyAvg }}</span>
                                <span class="text-xs text-yellow-600 dark:text-yellow-500">/5</span>
                                <span class="text-[10px] text-yellow-600 dark:text-yellow-500 ml-auto">dari {{ $modalCompanyCount }} ulasan</span>
                            </div>
                        </div>
                        @endif
                    </div>

                    {{-- Isi Ulasan --}}
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Isi Pengalaman</label>
                        {{-- FIX: Menambahkan break-words untuk menangani kata yang sangat panjang --}}
                        <div class="bg-slate-50 dark:bg-slate-900 rounded-xl p-5 border border-slate-100 dark:border-slate-800 shadow-inner overflow-hidden">
                            <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed italic break-words whitespace-pre-wrap">"{{ $selectedReview->isi }}"</p>
                        </div>
                    </div>

                    <p class="text-[10px] text-slate-400 text-right italic font-medium">
                        Diposting pada {{ \Carbon\Carbon::parse($selectedReview->created_at)->translatedFormat('d F Y, H:i') }}
                    </p>
                </div>

                <div class="p-4 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-200 dark:border-slate-800 flex justify-end shrink-0">
                    <button type="button" wire:click="closeDetail" class="btn px-8 bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-200 border-none hover:bg-slate-300">Tutup</button>
                </div>
            </div>
            @endif
            <form method="dialog" class="modal-backdrop">
                <button wire:click="closeDetail">close</button>
            </form>
        </dialog>
    </template>

</div>