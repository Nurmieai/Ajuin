<div class="space-y-6">

    {{-- ========================================================= --}}
    {{-- CARD UTAMA: Ulasan Saya                                    --}}
    {{-- ========================================================= --}}
    <div class="bg-base-100 shadow rounded-box overflow-hidden">

        {{-- Flash Message --}}
        @if(session()->has('message'))
            <div class="alert alert-success m-4">
                <x-ui.icon name="check-circle" class="size-5" />
                <span>{{ session('message') }}</span>
            </div>
        @endif

        {{-- Belum ada submission yang approved --}}
        @if(!$submission)
            <div class="p-8 text-center">
                <x-ui.icon name="document-plus" class="size-16 mx-auto text-slate-300 mb-4" />
                <h3 class="text-lg font-semibold text-slate-600">Belum Ada PKL</h3>
                <p class="text-slate-500">Kamu belum memiliki pengajuan PKL yang diterima.</p>
            </div>

        {{-- PKL belum selesai --}}
        @elseif(!$canReview)
            <div class="p-8 text-center">
                <x-ui.icon name="clock" class="size-16 mx-auto text-slate-300 mb-4" />
                <h3 class="text-lg font-semibold text-slate-600">PKL Masih Berlangsung</h3>
                <p class="text-slate-500">
                    Ulasan bisa ditulis setelah masa PKL selesai pada
                    <span class="font-semibold text-slate-700">
                        {{ \Carbon\Carbon::parse($submission->finish_date)->translatedFormat('d F Y') }}
                    </span>.
                </p>
            </div>

        {{-- Form Input / Edit Ulasan --}}
        @elseif($showForm)
            <div class="p-6">
                <h3 class="text-lg font-bold mb-4">{{ $ulasan ? 'Edit Ulasan' : 'Tulis Ulasan' }}</h3>

                <div class="space-y-4">
                    <div>
                        <label class="label">Perusahaan</label>
                        <input type="text" value="{{ $submission->company_name }}"
                            class="input input-bordered w-full bg-base-200 cursor-not-allowed" disabled>
                    </div>

                    <div>
                        <label class="label">Judul Ulasan</label>
                        <input type="text" wire:model="judul" class="input input-bordered w-full"
                            placeholder="Pengalaman PKL di {{ $submission->company_name }}">
                        @error('judul') <span class="text-error text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="label">Rating</label>
                        <div class="flex gap-2">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button" wire:click="$set('rating', {{ $i }})"
                                    class="btn btn-ghost btn-sm {{ $rating >= $i ? 'text-yellow-500' : 'text-slate-300' }}">
                                    <x-ui.icon name="star" class="size-6" />
                                </button>
                            @endfor
                        </div>
                        @error('rating') <span class="text-error text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="label">Ceritakan Pengalamanmu</label>
                        <textarea wire:model="isi" class="textarea textarea-bordered w-full h-32"
                            placeholder="Bagaimana pengalaman PKL kamu..."></textarea>
                        @error('isi') <span class="text-error text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex gap-2 justify-end pt-4">
                        <button type="button" wire:click="closeForm" class="btn btn-ghost">Batal</button>
                        <button type="button" wire:click="save" class="btn btn-primary">
                            {{ $ulasan ? 'Update' : 'Kirim' }} Ulasan
                        </button>
                    </div>
                </div>
            </div>

        {{-- Belum ada ulasan --}}
        @elseif(!$ulasan)
            <div class="p-8 text-center">
                <x-ui.icon name="chat-bubble-left-right" class="size-16 mx-auto text-slate-300 mb-4" />
                <h3 class="text-lg font-semibold text-slate-600">Belum Ada Ulasan</h3>
                <p class="text-slate-500 mb-4">Bagikan pengalaman PKL kamu di {{ $submission->company_name }}!</p>
                <button wire:click="openForm" class="btn btn-primary">
                    Tulis Ulasan
                </button>
            </div>

        {{-- Sudah ada ulasan --}}
        @else
            <div class="p-6">
                <div class="flex items-center gap-2 mb-4 text-slate-500">
                    <x-ui.icon name="document-text" class="size-4" />
                    <span class="text-xs font-semibold uppercase tracking-wider">Ulasan PKL Saya</span>
                </div>

                <div class="bg-slate-50 dark:bg-slate-900 rounded-lg p-5 border border-slate-200 dark:border-slate-800">
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <h4 class="font-semibold text-slate-800 dark:text-slate-200 text-lg">
                                {{ $ulasan->judul }}
                            </h4>
                            <p class="text-sm text-slate-500">
                                {{ $submission->company_name }} •
                                {{ \Carbon\Carbon::parse($ulasan->created_at)->translatedFormat('d F Y') }}
                            </p>
                        </div>
                        @if($ulasan->rating)
                            <div class="flex items-center gap-1 bg-yellow-100 dark:bg-yellow-900/30 px-3 py-1 rounded-full">
                                <x-ui.icon name="star" class="size-4 text-yellow-600" />
                                <span class="font-bold text-yellow-700 dark:text-yellow-400">{{ $ulasan->rating }}/5</span>
                            </div>
                        @endif
                    </div>
                    <p class="text-slate-700 dark:text-slate-300 leading-relaxed mt-4">
                        {{ $ulasan->isi }}
                    </p>
                </div>

                <div class="mt-4 flex justify-end gap-2">
                    <button wire:click="openForm" class="btn btn-sm btn-ghost">
                        Edit Ulasan
                    </button>
                </div>
            </div>
        @endif
    </div>

    {{-- ========================================================= --}}
    {{-- CARD KEDUA: Ulasan dari Siswa Lain (Preview)               --}}
    {{-- ========================================================= --}}
    <div class="bg-base-100 shadow rounded-box overflow-hidden">
        <div class="p-4 flex items-center justify-between border-b border-base-200">
            <div class="flex items-center gap-2">
                <x-ui.icon name="users" class="size-5 text-slate-500" />
                <span class="font-semibold text-slate-700 dark:text-slate-300">Ulasan dari Siswa Lain</span>
            </div>
            <button wire:click="toggleAllUlasan" class="btn btn-sm btn-ghost">
                Lihat Semua
            </button>
        </div>

        {{-- Preview 2 ulasan terbaru --}}
        <div class="p-4 space-y-3">
            @forelse($previewUlasans as $item)
                <div class="bg-slate-50 dark:bg-slate-900 rounded-lg p-4 border border-slate-200 dark:border-slate-800">
                    <div class="flex items-start justify-between mb-1">
                        <div>
                            <p class="font-semibold text-sm text-slate-700 dark:text-slate-300">{{ $item->judul }}</p>
                            <p class="text-xs text-slate-400">
                                {{ $item->student->name ?? '-' }} • {{ $item->submission->company_name ?? '-' }} •
                                {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y') }}
                            </p>
                        </div>
                        @if($item->rating)
                            <div class="flex items-center gap-1 bg-yellow-100 dark:bg-yellow-900/30 px-2 py-0.5 rounded-full shrink-0">
                                <x-ui.icon name="star" class="size-3 text-yellow-600" />
                                <span class="text-xs font-bold text-yellow-700 dark:text-yellow-400">{{ $item->rating }}/5</span>
                            </div>
                        @endif
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2 mt-1">{{ $item->isi }}</p>
                </div>
            @empty
                <div class="text-center py-6 text-slate-400">
                    <p class="text-sm">Belum ada ulasan dari siswa lain.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- ========================================================= --}}
    {{-- MODAL: Semua Ulasan Siswa                                  --}}
    {{-- ========================================================= --}}
    @if($showAllUlasan)
        {{-- Backdrop --}}
        <div
            wire:click="toggleAllUlasan"
            class="fixed inset-0 z-40 bg-black/60 backdrop-blur-sm transition-opacity duration-200">
        </div>

        {{-- Modal Panel --}}
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 pointer-events-none">
            <div class="bg-base-100 rounded-2xl shadow-2xl w-full max-w-2xl max-h-[80vh] flex flex-col pointer-events-auto">

                {{-- Modal Header --}}
                <div class="flex items-center justify-between p-5 border-b border-base-200 shrink-0">
                    <div class="flex items-center gap-2">
                        <x-ui.icon name="chat-bubble-left-right" class="size-5 text-primary" />
                        <h3 class="font-bold text-lg text-slate-800 dark:text-slate-100">Semua Ulasan PKL</h3>
                    </div>
                    <button wire:click="toggleAllUlasan" class="btn btn-sm btn-ghost btn-circle">
                        <x-ui.icon name="x-mark" class="size-5" />
                    </button>
                </div>

                {{-- Modal Body - Scrollable --}}
                <div class="overflow-y-auto flex-1 p-5 space-y-4">
                    @forelse($allUlasans as $item)
                        <div class="bg-slate-50 dark:bg-slate-900 rounded-xl p-4 border border-slate-200 dark:border-slate-800">
                            <div class="flex items-start justify-between mb-2">
                                <div>
                                    <p class="font-semibold text-slate-800 dark:text-slate-200">{{ $item->judul }}</p>
                                    <p class="text-xs text-slate-500 mt-0.5">
                                        {{ $item->student->name ?? '-' }} •
                                        {{ $item->submission->company_name ?? '-' }} •
                                        {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y') }}
                                    </p>
                                </div>
                                @if($item->rating)
                                    <div class="flex items-center gap-1 bg-yellow-100 dark:bg-yellow-900/30 px-3 py-1 rounded-full shrink-0 ml-3">
                                        <x-ui.icon name="star" class="size-4 text-yellow-600" />
                                        <span class="font-bold text-yellow-700 dark:text-yellow-400 text-sm">{{ $item->rating }}/5</span>
                                    </div>
                                @endif
                            </div>
                            <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed mt-2">{{ $item->isi }}</p>
                        </div>
                    @empty
                        <div class="text-center py-12 text-slate-400">
                            <x-ui.icon name="chat-bubble-left-right" class="size-12 mx-auto text-slate-300 mb-3" />
                            <p>Belum ada ulasan dari siswa lain.</p>
                        </div>
                    @endforelse
                </div>

                {{-- Modal Footer - Pagination --}}
                @if($allUlasans->hasPages())
                    <div class="p-4 border-t border-base-200 shrink-0">
                        {{ $allUlasans->links() }}
                    </div>
                @endif

            </div>
        </div>
    @endif

</div>