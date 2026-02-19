<div class="fixed inset-0 bg-black/50 dark:bg-black/70 flex items-center justify-center z-50">
    <div class="bg-white dark:bg-slate-950 p-8 rounded-xl shadow-xl
                w-full max-w-xl border border-slate-200 dark:border-slate-800">

        <!-- Header -->
        <h2 class="text-xl font-bold mb-6 border-b border-slate-200 dark:border-slate-800 pb-3
                   text-slate-800 dark:text-slate-100 flex justify-between items-center">
            Detail Mitra PKL

            <button
                wire:click="close"
                class="btn btn-sm btn-circle btn-ghost">
                X
            </button>
        </h2>

        <div class="space-y-5">

            <!-- Nama Mitra (Highlight) -->
            <div>
                <label class="text-xs font-semibold uppercase tracking-wider
                              text-slate-500 dark:text-slate-400">
                    Nama Mitra
                </label>
                <p class="mt-1 text-lg font-medium
                          text-blue-600 dark:text-blue-400">
                    {{ $partner->name }}
                </p>
            </div>

            <div class="grid grid-cols-2 gap-4">

                <!-- Email -->
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wider
                                  text-slate-500 dark:text-slate-400">
                        Email
                    </label>
                    <p class="mt-1 text-slate-700 dark:text-slate-300">
                        {{ $partner->email ?? '-' }}
                    </p>
                </div>

                <!-- Telepon -->
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wider
                                  text-slate-500 dark:text-slate-400">
                        No. Telepon
                    </label>
                    <p class="mt-1 text-slate-700 dark:text-slate-300">
                        {{ $partner->phone_number ?? '-' }}
                    </p>
                </div>

                <!-- Kuota -->
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wider
                                  text-slate-500 dark:text-slate-400">
                        Kuota
                    </label>
                    <p class="mt-1 text-slate-700 dark:text-slate-300">
                        {{ $partner->quota }} Orang
                    </p>
                </div>

                <!-- Kriteria -->
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wider
                                  text-slate-500 dark:text-slate-400">
                        Kriteria
                    </label>
                    <p class="mt-1 text-slate-700 dark:text-slate-300">
                        {{ $partner->criteria ?? '-' }}
                    </p>
                </div>
            </div>

            <!-- Alamat -->
            <div>
                <label class="text-xs font-semibold uppercase tracking-wider
                              text-slate-500 dark:text-slate-400">
                    Alamat
                </label>
                <p class="mt-1 text-slate-700 dark:text-slate-300 leading-relaxed">
                    {{ $partner->address }}
                </p>
            </div>

            <!-- Periode -->
            <div class="rounded-lg border border-slate-200 dark:border-slate-800
                        bg-slate-50 dark:bg-slate-900 p-4">
                <label class="text-xs font-semibold uppercase tracking-wider
                              text-slate-500 dark:text-slate-400 block mb-1">
                    Periode Kerjasama
                </label>
                <p class="text-sm text-slate-600 dark:text-slate-300">
                    <span class="font-medium text-slate-800 dark:text-slate-100">
                        {{ \Carbon\Carbon::parse($partner->start_date)->translatedFormat('d F Y') }}
                    </span>
                    s/d
                    <span class="font-medium text-slate-800 dark:text-slate-100">
                        {{ \Carbon\Carbon::parse($partner->finish_date)->translatedFormat('d F Y') }}
                    </span>
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-8 pt-4 border-t border-slate-200 dark:border-slate-800 text-right">
            <button
                wire:click="close"
                class="px-5 py-2 rounded-md font-medium transition
                       bg-slate-200 hover:bg-slate-300
                       dark:bg-slate-700 dark:hover:bg-slate-600
                       text-slate-700 dark:text-slate-100">
                Tutup
            </button>
        </div>
    </div>
</div>