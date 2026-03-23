<x-slot:title>
    Ulasan PKL Saya
</x-slot:title>

<div class="flex flex-col gap-4">
    {{-- Breadcrumbs --}}
    <x-ui.breadcrumbs :items="[
        'Ulasan PKL' => [
            'url' => '#',
            'icon' => 'chat-bubble-left-right'
        ]
    ]" />

    <x-ui.pageheader
        title="Ulasan & Pengalaman"
        subtitle="Bagikan pengalaman PKL kamu atau lihat ulasan dari teman-teman lainnya" />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        {{-- KOLOM KIRI: Form / Ulasan Saya --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center gap-2 bg-slate-50/50 dark:bg-slate-800/30">
                    <x-ui.icon name="document-text" color="blue" size="sm" />
                    <span class="font-bold text-slate-700 dark:text-slate-200 uppercase text-xs tracking-wider">Ulasan Saya</span>
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
                            <span class="font-bold text-blue-600">{{ \Carbon\Carbon::parse($submission->finish_date)->translatedFormat('d F Y') }}</span>
                        </p>
                    </div>
                    @else
                    {{-- Tampilan Ulasan atau Tombol Tulis --}}
                    @if(!$ulasan)
                    <div class="py-12 text-center">
                        <x-ui.icon name="chat-bubble-left-right" size="xl" class="mx-auto text-slate-200 mb-4" />
                        <h3 class="text-lg font-bold text-slate-700 dark:text-slate-200">Belum Ada Ulasan</h3>
                        <p class="text-slate-500 text-sm mb-6">Bagikan pengalaman PKL kamu di {{ $submission->company_name }}!</p>
                        <button wire:click="openForm" class="btn bg-blue-600 hover:bg-blue-700 text-white border-none px-8">Tulis Ulasan</button>
                    </div>
                    @else
                    <div class="space-y-4">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-50 dark:bg-slate-800/50 p-6 rounded-2xl border border-slate-200 dark:border-slate-700">
                            <div class="flex-1">
                                <h4 class="font-black text-slate-800 dark:text-slate-100 text-xl">{{ $ulasan->judul }}</h4>
                                <p class="text-sm text-slate-500 flex items-center gap-2">
                                    <x-ui.icon name="building" size="xs" /> {{ $submission->company_name }}
                                </p>
                            </div>
                            <div class="flex items-center gap-2 bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 px-4 py-2 rounded-xl h-fit">
                                <x-ui.icon name="star" class="size-5 text-yellow-400 fill-current" />
                                <span class="font-black text-slate-800 dark:text-slate-100 text-lg">{{ $ulasan->rating }}/5</span>
                            </div>
                        </div>
                        <p class="text-slate-600 dark:text-slate-300 italic leading-relaxed">"{{ $ulasan->isi }}"</p>
                        <div class="flex justify-end">
                            <button wire:click="openForm" class="btn btn-sm btn-ghost text-blue-600">Edit Ulasan Saya</button>
                        </div>
                    </div>
                    @endif
                    @endif
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: Preview Siswa Lain --}}
        <div class="space-y-4">
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-800/30">
                    <span class="font-bold text-slate-700 dark:text-slate-200 text-xs uppercase tracking-wider">Siswa Lain</span>
                    <button wire:click="toggleAllUlasan" class="text-xs font-bold text-blue-600 hover:underline">Lihat Semua</button>
                </div>
                <div class="p-4 space-y-4">
                    @forelse($previewUlasans as $item)
                    <div class="p-4 rounded-xl border border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900">
                        <p class="font-bold text-sm text-slate-800 dark:text-slate-200 line-clamp-1">{{ $item->judul }}</p>
                        <p class="text-[10px] text-slate-500 mb-2">{{ $item->student->fullname }} • {{ $item->submission->company_name }}</p>
                        <p class="text-xs text-slate-500 italic line-clamp-2">"{{ $item->isi }}"</p>
                    </div>
                    @empty
                    <p class="text-center py-4 text-xs text-slate-400">Belum ada ulasan.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL FORM (Teleport) --}}
    <template x-teleport="body">
        <dialog id="ulasan_form_modal" class="modal backdrop-blur-sm" wire:ignore.self>
            <div class="modal-box w-full max-w-2xl bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 shadow-xl rounded-xl p-0 overflow-hidden flex flex-col max-h-[90vh]">
                <div class="p-6 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center bg-slate-50/50 dark:bg-slate-900/50 shrink-0">
                    <h3 class="text-xl font-bold text-slate-800 dark:text-slate-100">{{ $ulasan ? 'Edit Ulasan' : 'Tulis Ulasan' }}</h3>
                    <button wire:click="closeForm" class="btn btn-sm btn-circle btn-ghost">✕</button>
                </div>
                <div class="p-8 space-y-6 overflow-y-auto">
                    <x-ui.input label="Perusahaan" :value="$submission?->company_name" disabled class="bg-slate-100 dark:bg-slate-800 opacity-70" />
                    <x-ui.input label="Judul Ulasan" wire:model="judul" placeholder="Judul ulasan..." />
                    <div>
                        <label class="label-text font-semibold text-slate-600 dark:text-slate-300 block mb-2">Rating</label>
                        <div class="flex gap-1">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button" wire:click="$set('rating', {{ $i }})" class="p-1 {{ $rating >= $i ? 'text-yellow-400' : 'text-slate-300' }}">
                                <x-ui.icon name="star" class="size-7 {{ $rating >= $i ? 'fill-current' : '' }}" />
                                </button>
                                @endfor
                        </div>
                    </div>
                    <x-ui.input label="Isi Ulasan" type="textarea" wire:model="isi" placeholder="Ceritakan pengalamanmu..." />
                </div>
                <div class="p-4 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-200 dark:border-slate-800 flex justify-end gap-3 shrink-0">
                    <button wire:click="closeForm" class="btn px-8 bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-200 border-none">Batal</button>
                    <button wire:click="save" class="btn px-8 bg-blue-600 text-white border-none">Simpan</button>
                </div>
            </div>
        </dialog>
    </template>

    {{-- MODAL SEMUA ULASAN --}}
    <template x-teleport="body">
        <dialog id="all_ulasan_modal" class="modal backdrop-blur-sm" wire:ignore.self>
            <div class="modal-box w-full max-w-2xl bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 shadow-xl rounded-xl p-0 overflow-hidden flex flex-col max-h-[90vh]">
                <div class="p-6 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center bg-slate-50/50 dark:bg-slate-900/50 shrink-0">
                    <h3 class="text-xl font-bold">Semua Ulasan PKL</h3>
                    <button wire:click="toggleAllUlasan" class="btn btn-sm btn-circle btn-ghost">✕</button>
                </div>
                <div class="p-6 space-y-4 overflow-y-auto">
                    @if($showAllUlasan)
                    @foreach($allUlasans as $item)
                    <div class="p-4 bg-slate-50 dark:bg-slate-900/50 rounded-xl border border-slate-100 dark:border-slate-800">
                        <div class="flex justify-between items-start mb-2">
                            <h5 class="font-bold">{{ $item->judul }}</h5>
                            <span class="text-xs font-bold text-yellow-500">⭐ {{ $item->rating }}/5</span>
                        </div>
                        <p class="text-xs text-slate-500 mb-2">{{ $item->student->fullname }} • {{ $item->submission->company_name }}</p>
                        <p class="text-sm text-slate-600 dark:text-slate-400 italic">"{{ $item->isi }}"</p>
                    </div>
                    @endforeach
                    @endif
                </div>
                <div class="flex justify-center p-4 bg-slate-50 dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800">
                    @if($showAllUlasan) {{ $allUlasans->links('components.ui.pagination') }} @endif
                </div>
            </div>
        </dialog>
    </template>
</div>

@script
<script>
    $wire.on('open-ulasan-modal', () => document.getElementById('ulasan_form_modal').showModal());
    $wire.on('close-ulasan-modal', () => document.getElementById('ulasan_form_modal').close());
    $wire.on('open-all-ulasan-modal', () => document.getElementById('all_ulasan_modal').showModal());
    $wire.on('close-all-ulasan-modal', () => document.getElementById('all_ulasan_modal').close());
</script>
@endscript