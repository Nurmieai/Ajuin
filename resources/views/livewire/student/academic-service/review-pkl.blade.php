<div class="flex flex-col gap-4">
    <x-ui.breadcrumbs :items="[
        'Ulasan PKL' => [
            'url' => '#',
            'icon' => 'chat-bubble-left-right'
        ]
    ]" />

    <x-ui.pageheader
        title="Ulasan & Pengalaman"
        subtitle="Bagikan pengalaman PKL kamu atau lihat ulasan dari teman-teman lainnya" />

    {{-- ULASAN SAYA --}}
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-800/30">
            <div class="flex items-center gap-2">
                <x-ui.icon name="document-text" color="blue" size="sm" />
                <span class="font-bold text-slate-700 dark:text-slate-200 uppercase text-xs tracking-wider">Ulasan Saya</span>
            </div>
            @if($review && $canReview)
                <button wire:click="openForm" class="btn btn-sm btn-ghost text-blue-600">Edit Ulasan</button>
            @endif
        </div>

        <div class="p-6">
            @if(!$submission)
                <div class="py-12 text-center">
                    <x-ui.icon name="archive" size="xl" class="mx-auto text-slate-200 mb-4" />
                    <h3 class="text-lg font-bold text-slate-700 dark:text-slate-200">Belum Ada PKL</h3>
                    <p class="text-slate-500 text-sm">Kamu belum memiliki pengajuan PKL yang diterima.</p>
                </div>

            @elseif(!$canReview)
                <div class="py-12 text-center">
                    <x-ui.icon name="clock" size="xl" class="mx-auto text-slate-200 mb-4" />
                    <h3 class="text-lg font-bold text-slate-700 dark:text-slate-200">PKL Masih Berlangsung</h3>
                    <p class="text-slate-500 text-sm max-w-xs mx-auto mt-2">
                        Ulasan bisa ditulis setelah masa PKL selesai pada
                        <span class="font-bold text-blue-600">
                            {{ \Carbon\Carbon::parse($submission->finish_date)->translatedFormat('d F Y') }}
                        </span>
                    </p>
                </div>

            @elseif(!$review)
                <div class="py-12 text-center">
                    <x-ui.icon name="chat-bubble-left-right" size="xl" class="mx-auto text-slate-200 mb-4" />
                    <h3 class="text-lg font-bold text-slate-700 dark:text-slate-200">Belum Ada Ulasan</h3>
                    <p class="text-slate-500 text-sm mb-6">Bagikan pengalaman PKL kamu di {{ $submission->company_name }}!</p>
                    <button wire:click="openForm" class="btn bg-blue-600 hover:bg-blue-700 text-white border-none px-8">Tulis Ulasan</button>
                </div>

            @else
                
                <div class="border border-slate-200 dark:border-slate-800 rounded-lg overflow-hidden">
                    <x-ui.table :columns="['Perusahaan', 'Ulasan', 'Rating', 'Tanggal']">
                        <tr
                            wire:click="openForm"
                            class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors cursor-pointer">

                            {{-- Perusahaan --}}
                            <td class="py-3 px-3">
                                <div class="flex items-center gap-1.5">
                                    <x-ui.icon name="building" size="xs" class="text-slate-400" />
                                    <span class="text-xs text-slate-600 dark:text-slate-400 truncate max-w-[120px]">{{ $submission->company_name }}</span>
                                </div>
                            </td>

                            {{-- Judul + Isi --}}
                            <td class="py-3 px-3 max-w-[200px]">
                                <p class="font-bold text-xs text-slate-800 dark:text-slate-200 truncate">{{ $review->judul }}</p>
                                <p class="text-xs text-slate-500 mt-0.5 line-clamp-1 italic">"{{ $review->isi }}"</p>
                            </td>

                            {{-- Rating --}}
                            <td class="py-3 px-3">
                                <div class="flex items-center gap-0.5">
                                    @for($i = 1; $i <= 5; $i++)
                                        <x-ui.icon name="star"
                                            class="size-3 {{ $i <= $review->rating ? 'text-yellow-400 fill-yellow-400' : 'text-slate-200 dark:text-slate-700' }}" />
                                    @endfor
                                    <span class="text-[10px] font-bold text-slate-500 ml-1 bg-slate-100 dark:bg-slate-800 px-1 rounded">{{ $review->rating }}</span>
                                </div>
                            </td>

                            {{-- Tanggal --}}
                            <td class="py-3 px-3">
                                <span class="text-xs text-slate-500 font-medium">
                                    {{ \Carbon\Carbon::parse($review->created_at)->format('d M y') }}
                                </span>
                            </td>
                        </tr>
                    </x-ui.table>
                </div>
            @endif
        </div>
    </div>

    {{-- ULASAN SISWA LAIN --}}
<div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm overflow-hidden">
    
    {{-- Header + Search --}}
    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-800/30">
        <div class="flex items-center gap-2">
            <x-ui.icon name="users" color="blue" size="sm" />
            <span class="font-bold text-slate-700 dark:text-slate-200 uppercase text-xs tracking-wider">Ulasan Siswa Lain</span>
        </div>

        {{-- Search --}}
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <x-ui.icon name="magnifying-glass" size="xs" class="text-slate-400" />
            </div>
            <input
                wire:model.live.debounce.400ms="search"
                type="text"
                placeholder="Cari siswa atau perusahaan..."
                class="pl-8 pr-4 py-1.5 text-xs rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 w-56 transition-all" />
            @if($search)
                <button wire:click="$set('search', '')" class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-slate-400 hover:text-slate-600">
                    <x-ui.icon name="x-circle" size="xs" />
                </button>
            @endif
        </div>
    </div>

    {{-- Tabel --}}
    <div class="flex-1 overflow-hidden flex flex-col min-h-0 p-4">
        <div class="overflow-y-auto flex-1 border border-slate-200 dark:border-slate-800 rounded-lg">
            <x-ui.table :columns="['Siswa', 'Perusahaan', 'Ulasan', 'Rating', 'Tanggal']">
                @forelse($previewReviews as $item)
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
                            <p class="text-slate-500 font-bold text-sm">Belum ada ulasan</p>
                            <p class="text-slate-400 text-xs mt-0.5">Ulasan siswa lain akan muncul di sini.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </x-ui.table>
        </div>

        {{-- Pagination --}}
        <div class="mt-2 shrink-0">
            {{ $previewReviews->links() }}
        </div>
    </div>
</div>
    {{-- MODAL DETAIL ULASAN SISWA LAIN --}}
    @if($selectedReview)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeDetail"></div>

        <div class="relative bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 shadow-2xl rounded-xl w-full max-w-md flex flex-col max-h-[80vh]">
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
                        @for($i = 1; $i <= 5; $i++)
                            <x-ui.icon name="star"
                                class="size-4 {{ $i <= $selectedReview->rating ? 'text-yellow-400 fill-yellow-400' : 'text-slate-200 dark:text-slate-700' }}" />
                        @endfor
                    </div>
                    <span class="font-bold text-slate-700 dark:text-slate-300">{{ $selectedReview->rating }}/5</span>
                </div>

                {{-- Isi --}}
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

    
    <div
        x-data="{ open: false }"
        x-on:open-ulasan-modal.window="open = true"
        x-on:close-ulasan-modal.window="open = false"
        x-show="open"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4">

        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeForm"></div>

        <div class="relative z-10 bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 shadow-xl rounded-xl w-full max-w-2xl h-auto max-h-[90vh] flex flex-col overflow-hidden">
            <div class="p-6 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center bg-slate-50/50 dark:bg-slate-900/50 shrink-0">
                <h3 class="text-xl font-bold text-slate-800 dark:text-slate-100">{{ $review ? 'Edit Ulasan' : 'Tulis Ulasan' }}</h3>
                <button wire:click="closeForm" class="btn btn-sm btn-circle btn-ghost">✕</button>
            </div>
            <div class="p-8 space-y-6 overflow-y-auto flex-1">
                <x-ui.input label="Perusahaan" :value="$submission?->company_name" disabled class="bg-slate-100 dark:bg-slate-800 opacity-70" />

                <div>
                    <x-ui.input label="Judul Ulasan" wire:model.live="judul" placeholder="Judul ulasan..." />
                    @error('judul') <span class="text-error text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="label-text font-semibold text-slate-600 dark:text-slate-300 block mb-2">Rating</label>
                    <div class="flex gap-1">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" wire:click="$set('rating', {{ $i }})" class="p-1 {{ $rating >= $i ? 'text-yellow-400' : 'text-slate-300' }}">
                                <x-ui.icon name="star" class="size-7 {{ $rating >= $i ? 'fill-current' : '' }}" />
                            </button>
                        @endfor
                    </div>
                    @error('rating') <span class="text-error text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <x-ui.input label="Isi Ulasan" type="textarea" wire:model.live="isi" placeholder="Ceritakan pengalamanmu..." />
                    @error('isi') <span class="text-error text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="p-4 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-200 dark:border-slate-800 flex justify-end gap-3 shrink-0">
                <button wire:click="closeForm" class="btn px-8 bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-200 border-none">Batal</button>
                <button wire:click="save" class="btn px-8 bg-blue-600 text-white border-none">Simpan</button>
            </div>
        </div>
    </div>
</div>