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

                        <td class="py-3 px-3">
                            <div class="flex items-center gap-1.5">
                                <x-ui.icon name="building" size="xs" class="text-slate-400" />
                                <span class="text-xs text-slate-600 dark:text-slate-400 truncate max-w-[120px]">{{ $submission->company_name }}</span>
                            </div>
                        </td>

                        <td class="py-3 px-3 max-w-[200px]">
                            <p class="font-bold text-xs text-slate-800 dark:text-slate-200 truncate">{{ $review->judul }}</p>
                            <p class="text-xs text-slate-500 mt-0.5 line-clamp-1 italic">"{{ $review->isi }}"</p>
                        </td>

                        <td class="py-3 px-3">
                            <div class="flex items-center gap-0.5">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <=$review->rating)
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-3 text-yellow-400">
                                        <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z" clip-rule="evenodd" />
                                    </svg>
                                    @else
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-3 text-slate-300 dark:text-slate-600">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.601c-.38-.325-.078-.948.32-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
                                    </svg>
                                    @endif
                                    @endfor
                                    <span class="text-[10px] font-bold text-slate-500 ml-1 bg-slate-100 dark:bg-slate-800 px-1 rounded">{{ $review->rating }}</span>
                            </div>
                        </td>

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
        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-800/30">
            <div class="flex items-center gap-2">
                <x-ui.icon name="users" color="blue" size="sm" />
                <span class="font-bold text-slate-700 dark:text-slate-200 uppercase text-xs tracking-wider">Ulasan Siswa Lain</span>
            </div>

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

        <div class="flex-1 overflow-hidden flex flex-col min-h-0 p-4">
            <div class="overflow-y-auto flex-1 border border-slate-200 dark:border-slate-800 rounded-lg">
                <x-ui.table :columns="['Siswa', 'Perusahaan', 'Ulasan', 'Rating', 'Tanggal']">
                    @forelse($previewReviews as $item)
                    <tr
                        wire:click="showDetail({{ $item->id }})"
                        class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors cursor-pointer">

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

                        <td class="py-3 px-3">
                            <div class="flex items-center gap-1.5">
                                <x-ui.icon name="building" size="xs" class="text-slate-400" />
                                <span class="text-xs text-slate-600 dark:text-slate-400 truncate max-w-[120px]">{{ $item->submission->company_name ?? '-' }}</span>
                            </div>
                        </td>

                        <td class="py-3 px-3 max-w-[200px]">
                            <p class="font-bold text-xs text-slate-800 dark:text-slate-200 truncate">{{ $item->judul }}</p>
                            <p class="text-xs text-slate-500 mt-0.5 line-clamp-1 italic">"{{ $item->isi }}"</p>
                        </td>

                        <td class="py-3 px-3">
                            <div class="flex items-center gap-0.5">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <=$item->rating)
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-3 text-yellow-400">
                                        <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z" clip-rule="evenodd" />
                                    </svg>
                                    @else
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-3 text-slate-300 dark:text-slate-600">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.601c-.38-.325-.078-.948.32-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
                                    </svg>
                                    @endif
                                    @endfor
                                    <span class="text-[10px] font-bold text-slate-500 ml-1 bg-slate-100 dark:bg-slate-800 px-1 rounded">{{ $item->rating }}</span>
                            </div>
                        </td>

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

            <div class="mt-2 shrink-0">
                {{ $previewReviews->links() }}
            </div>
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
                    <div class="flex items-center justify-between bg-slate-50/50 dark:bg-slate-900/50 p-3 rounded-lg border border-slate-100 dark:border-slate-800">
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

                    {{-- Isi Ulasan --}}
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Isi Pengalaman</label>
                        {{-- FIX: Menambahkan break-words untuk menangani kata yang sangat panjang --}}
                        <div class="bg-slate-50 dark:bg-slate-900 rounded-xl p-5 border border-slate-100 dark:border-slate-800 shadow-inner overflow-hidden">
                            <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed italic break-words">"{{ $selectedReview->isi }}"</p>
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

    {{-- MODAL FORM TULIS / EDIT ULASAN --}}
    <template x-teleport="body">
        <dialog id="review_form_modal"
            class="modal backdrop-blur-sm"
            wire:ignore.self
            x-data
            @open-ulasan-modal.window="$nextTick(() => $el.showModal())"
            @close-ulasan-modal.window="$el.close()">
            <div class="modal-box w-full max-w-2xl bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 shadow-xl rounded-xl p-0 overflow-hidden flex flex-col max-h-[90vh]">
                <div class="p-6 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center bg-slate-50/50 dark:bg-slate-900/50 shrink-0">
                    <h3 class="text-xl font-bold text-slate-800 dark:text-slate-100">
                        {{ $review ? 'Edit Ulasan' : 'Tulis Ulasan' }}
                    </h3>
                    <button type="button" wire:click="closeForm" class="btn btn-sm btn-circle btn-ghost">✕</button>
                </div>

                <div class="p-8 space-y-6 overflow-y-auto">
                    <form id="reviewSubmitForm" wire:submit.prevent="save" class="space-y-6">
                        <x-ui.input
                            name=""
                            label="Perusahaan"
                            :value="$submission?->company_name"
                            disabled
                            class="bg-slate-100 dark:bg-slate-800 opacity-70" />

                        <x-ui.input
                            name="judul"
                            label="Judul Ulasan"
                            wire:model="judul"
                            placeholder="Judul ulasan..." />

                        <div>
                            <label class="label-text font-semibold text-slate-600 dark:text-slate-300 block mb-2">Rating</label>
                            <div class="flex gap-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <button type="button" wire:click="$set('rating', {{ $i }})" class="p-1 transition-transform active:scale-90 {{ $rating >= $i ? 'text-yellow-400' : 'text-slate-300' }}">
                                    <x-ui.icon name="star" class="size-7 {{ $rating >= $i ? 'fill-current' : '' }}" />
                                    </button>
                                    @endfor
                            </div>
                            @error('rating') <span class="text-error text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="label-text font-semibold text-slate-600 dark:text-slate-300 block mb-2">Isi Ulasan</label>
                            <div class="relative">
                                <textarea
                                    wire:model="isi"
                                    placeholder="Ceritakan pengalamanmu..."
                                    maxlength="400"
                                    rows="4"
                                    class="textarea textarea-bordered w-full text-sm resize-none pr-3 pb-7 focus:ring-blue-500/30
                                        {{ strlen($isi ?? '') >= 400
                                            ? 'border-red-400 focus:border-red-400'
                                            : (strlen($isi ?? '') >= 370
                                                ? 'border-yellow-400 focus:border-yellow-400'
                                                : '') }}">
                                </textarea>
                                <div class="absolute bottom-2.5 right-3 pointer-events-none">
                                    <span class="text-[10px] font-semibold
                                        {{ strlen($isi ?? '') >= 400 ? 'text-red-500' : (strlen($isi ?? '') >= 370 ? 'text-yellow-500' : 'text-slate-400') }}">
                                        {{ strlen($isi ?? '') }}/400
                                    </span>
                                </div>
                            </div>
                            @error('isi') <span class="text-error text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </form>
                </div>

                <div class="p-4 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-200 dark:border-slate-800 flex justify-end gap-3 shrink-0">
                    <button type="button" wire:click="closeForm" class="btn px-8 bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-200 border-none hover:bg-slate-300">Batal</button>
                    <button form="reviewSubmitForm" type="submit" class="btn px-8 bg-blue-600 hover:bg-blue-700 text-white border-none shadow-lg shadow-blue-500/20" wire:loading.attr="disabled" wire:target="save">
                        <span wire:loading.remove wire:target="save">Simpan</span>
                        <span wire:loading wire:target="save" class="flex items-center gap-2">
                            <span class="loading loading-spinner loading-xs"></span> Menyimpan...
                        </span>
                    </button>
                </div>
            </div>

            <form method="dialog" class="modal-backdrop">
                <button wire:click="closeForm">close</button>
            </form>
        </dialog>
    </template>
</div>