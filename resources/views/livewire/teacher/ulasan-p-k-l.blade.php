<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Ulasan PKL Siswa</h2>
            <p class="text-slate-500 text-sm mt-1">Ringkasan pengalaman PKL dari seluruh siswa</p>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="bg-base-100 shadow rounded-box p-5 flex items-center gap-4">
            <div class="bg-primary/10 p-3 rounded-full">
                <x-ui.icon name="chat-bubble-left-right" class="size-6 text-primary" />
            </div>
            <div>
                <p class="text-sm text-slate-500">Total Ulasan</p>
                <p class="text-2xl font-bold text-slate-800 dark:text-slate-100">{{ $totalCount }}</p>
            </div>
        </div>

        <div class="bg-base-100 shadow rounded-box p-5 flex items-center gap-4">
            <div class="bg-yellow-100 p-3 rounded-full">
                <x-ui.icon name="star" class="size-6 text-yellow-500" />
            </div>
            <div>
                <p class="text-sm text-slate-500">Rata-rata Rating</p>
                <p class="text-2xl font-bold text-slate-800 dark:text-slate-100">
                    {{ $avgRating > 0 ? $avgRating . '/5' : '-' }}
                </p>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="bg-base-100 shadow rounded-box p-4">
        <div class="flex flex-col sm:flex-row gap-3">

            <select wire:model.live="filterRating" class="select select-bordered">
                <option value="">Semua Rating</option>
                @for($i = 5; $i >= 1; $i--)
                    <option value="{{ $i }}">{{ $i }} Bintang</option>
                @endfor
            </select>

            <select wire:model.live="sortRating" class="select select-bordered">
                <option value="">Urutkan</option>
                <option value="desc">Rating Tertinggi</option>
                <option value="asc">Rating Terendah</option>
            </select>

            @if($search || $filterRating || $sortRating)
                <button wire:click="resetFilter" class="btn btn-ghost">
                    <x-ui.icon name="x-mark" class="size-4" />
                    Reset
                </button>
            @endif
        </div>
    </div>

    {{-- Table Ulasan --}}
   <div class="bg-base-100 shadow rounded-box">
    <div style="max-height: 350px; overflow-y: scroll;">
            <table class="table w-full">
                <thead class="sticky top-0 z-10 bg-base-200">
                    <tr class="text-slate-600 dark:text-slate-400 text-xs uppercase tracking-wider">
                        <th class="py-3 px-4">Siswa</th>
                        <th class="py-3 px-4">Perusahaan</th>
                        <th class="py-3 px-4">Ulasan</th>
                        <th class="py-3 px-4">Rating</th>
                        <th class="py-3 px-4">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ulasans as $item)
                        <tr class="border-b border-base-200 hover:bg-base-50 dark:hover:bg-slate-800/50 transition-colors">
                            {{-- Siswa --}}
                            <td class="py-3 px-4">
                                <span class="font-medium text-sm text-slate-700 dark:text-slate-300">
                                    {{ $item->student->username ?? $item->student->name ?? '-' }}
                                </span>
                            </td>

                            {{-- Perusahaan --}}
                            <td class="py-3 px-4 text-sm text-slate-600 dark:text-slate-400">
                                {{ $item->submission->company_name ?? '-' }}
                            </td>

                            {{-- Judul + Isi --}}
                            <td class="py-3 px-4 max-w-xs">
                                <p class="font-medium text-sm text-slate-700 dark:text-slate-300">{{ $item->judul }}</p>
                                <p class="text-xs text-slate-400 mt-1 line-clamp-2">{{ $item->isi }}</p>
                            </td>

                            {{-- Rating --}}
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <x-ui.icon name="star"
                                            class="size-4 {{ $i <= $item->rating ? 'text-yellow-500' : 'text-slate-200 dark:text-slate-700' }}" />
                                    @endfor
                                    <span class="text-xs font-semibold text-slate-500 ml-1">{{ $item->rating }}/5</span>
                                </div>
                            </td>

                            {{-- Tanggal --}}
                            <td class="py-3 px-4 text-xs text-slate-500">
                                {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-12">
                                <x-ui.icon name="chat-bubble-left-right" class="size-12 mx-auto text-slate-300 mb-3" />
                                <p class="text-slate-500 font-medium">Belum ada ulasan</p>
                                <p class="text-slate-400 text-sm">
                                    {{ $search ? 'Tidak ditemukan hasil untuk pencarian ini.' : 'Ulasan akan muncul setelah siswa menyelesaikan PKL.' }}
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($ulasans->hasPages())
            <div class="p-4 border-t border-base-200">
                {{ $ulasans->links() }}
            </div>
        @endif
    </div>

</div>