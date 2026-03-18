<x-slot:title>
    Edit Pengajuan PKL
</x-slot:title>

<div class="max-w-4xl mx-auto">
    {{-- Header --}}
    <div class="mb-6">
        <div class="flex items-center gap-3 mb-2">
            <a wire:navigate href="{{ route('student.submission-manage') }}" class="btn btn-sm btn-ghost">
                Kembali
            </a>
        </div>
        <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Edit Pengajuan PKL</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Perbarui informasi pengajuan PKL Anda</p>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success shadow-sm text-sm mb-4" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-error shadow-sm text-sm mb-4" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <form wire:submit.prevent="update">
                @csrf
                <div class="bg-slate-50 dark:bg-slate-900 p-4 rounded-lg mb-6">
                    <h3 class="font-semibold text-slate-700 dark:text-slate-300 mb-3 flex items-center gap-2">
                        Informasi Siswa
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="label">
                                <span class="label-text text-xs">Nama Lengkap</span>
                            </label>
                            <input type="text" value="{{ auth()->user()->fullname }}" class="input input-bordered input-sm w-full" disabled />
                        </div>
                        <div>
                            <label class="label">
                                <span class="label-text text-xs">NISN</span>
                            </label>
                            <input type="text" value="{{ auth()->user()->nisn }}" class="input input-bordered input-sm w-full" disabled />
                        </div>
                        <div>
                            <label class="label">
                                <span class="label-text text-xs">Jurusan</span>
                            </label>
                            <input type="text" value="{{ auth()->user()->major?->name }}" class="input input-bordered input-sm w-full" disabled />
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <h3 class="font-semibold text-slate-700 dark:text-slate-300 flex items-center gap-2">
                        Informasi Perusahaan
                    </h3>

                    <div>
                        <label class="label">
                            <span class="label-text">Nama Perusahaan <span class="text-error">*</span></span>
                        </label>
                        <input 
                            type="text" 
                            wire:model="company_name" 
                            class="input input-bordered w-full @error('company_name') input-error @enderror" 
                            placeholder="PT. Contoh Perusahaan"
                        />
                        @error('company_name') 
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="label">
                                <span class="label-text">Email Perusahaan <span class="text-error">*</span></span>
                            </label>
                            <input 
                                type="email" 
                                wire:model="company_email" 
                                class="input input-bordered w-full @error('company_email') input-error @enderror" 
                                placeholder="info@perusahaan.com"
                            />
                            @error('company_email') 
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <div>
                            <label class="label">
                                <span class="label-text">Nomor Telepon <span class="text-error">*</span></span>
                            </label>
                            <input 
                                type="text" 
                                wire:model="company_phone_number" 
                                class="input input-bordered w-full @error('company_phone_number') input-error @enderror" 
                                placeholder="021-12345678"
                            />
                            @error('company_phone_number') 
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="label">
                            <span class="label-text">Alamat Perusahaan <span class="text-error">*</span></span>
                        </label>
                        <textarea 
                            wire:model="company_address" 
                            class="textarea textarea-bordered w-full h-24 @error('company_address') textarea-error @enderror" 
                            placeholder="Alamat lengkap perusahaan"
                        ></textarea>
                        @error('company_address') 
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                </div>

                <div class="divider"></div>
                <div class="space-y-4">
                    <h3 class="font-semibold text-slate-700 dark:text-slate-300 flex items-center gap-2">
                        Dokumen Persyaratan
                    </h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        Upload file baru jika ingin mengganti dokumen yang sudah ada
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="label">
                                <span class="label-text">Kunjungan Industri</span>
                            </label>
                            
                            @if($existing_industrial_visit)
                                <div class="bg-blue-50 dark:bg-blue-950/30 p-3 rounded-lg border border-blue-200 dark:border-blue-800 mb-2">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="flex items-start gap-2 flex-1 min-w-0">
                                            <div class="min-w-0">
                                                <p class="text-xs font-medium text-blue-900 dark:text-blue-300 truncate">
                                                    {{ basename($existing_industrial_visit->file_path) }}
                                                </p>
                                                <div class="flex gap-2 mt-1">
                                                    <a href="{{ asset('storage/' . $existing_industrial_visit->file_path) }}" 
                                                       target="_blank"
                                                       class="text-xs text-blue-600 hover:text-blue-800">
                                                        Lihat
                                                    </a>
                                                    {{-- <button 
                                                        type="button"
                                                        wire:click="removeFile('industrial_visit')"
                                                        wire:confirm="Yakin ingin menghapus file ini?"
                                                        class="text-xs text-red-600 hover:text-red-800">
                                                        Hapus
                                                    </button> --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <input 
                                type="file" 
                                wire:model="industrial_visit" 
                                class="file-input file-input-bordered file-input-sm w-full @error('industrial_visit') file-input-error @enderror"
                                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                            />
                            <div wire:loading wire:target="industrial_visit" class="text-xs text-slate-500 mt-1">
                                Uploading...
                            </div>
                            @error('industrial_visit') 
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <div>
                            <label class="label">
                                <span class="label-text">Uji Kompetensi</span>
                            </label>
                            
                            @if($existing_competency_test)
                                <div class="bg-blue-50 dark:bg-blue-950/30 p-3 rounded-lg border border-blue-200 dark:border-blue-800 mb-2">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="flex items-start gap-2 flex-1 min-w-0">
                                            <div class="min-w-0">
                                                <p class="text-xs font-medium text-blue-900 dark:text-blue-300 truncate">
                                                    {{ basename($existing_competency_test->file_path) }}
                                                </p>
                                                <div class="flex gap-2 mt-1">
                                                    <a href="{{ asset('storage/' . $existing_competency_test->file_path) }}" 
                                                       target="_blank"
                                                       class="text-xs text-blue-600 hover:text-blue-800">
                                                        Lihat
                                                    </a>
                                                    {{-- <button 
                                                        type="button"
                                                        wire:click="removeFile('competency_test')"
                                                        wire:confirm="Yakin ingin menghapus file ini?"
                                                        class="text-xs text-red-600 hover:text-red-800">
                                                        Hapus
                                                    </button> --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <input 
                                type="file" 
                                wire:model="competency_test" 
                                class="file-input file-input-bordered file-input-sm w-full @error('competency_test') file-input-error @enderror"
                                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                            />
                            <div wire:loading wire:target="competency_test" class="text-xs text-slate-500 mt-1">
                                Uploading...
                            </div>
                            @error('competency_test') 
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <div>
                            <label class="label">
                                <span class="label-text">Kartu SPP</span>
                            </label>
                            
                            @if($existing_spp_card)
                                <div class="bg-blue-50 dark:bg-blue-950/30 p-3 rounded-lg border border-blue-200 dark:border-blue-800 mb-2">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="flex items-start gap-2 flex-1 min-w-0">
                                            <div class="min-w-0">
                                                <p class="text-xs font-medium text-blue-900 dark:text-blue-300 truncate">
                                                    {{ basename($existing_spp_card->file_path) }}
                                                </p>
                                                <div class="flex gap-2 mt-1">
                                                    <a href="{{ asset('storage/' . $existing_spp_card->file_path) }}" 
                                                       target="_blank"
                                                       class="text-xs text-blue-600 hover:text-blue-800">
                                                        Lihat
                                                    </a>
                                                    {{-- <button 
                                                        type="button"
                                                        wire:click="removeFile('spp_card')"
                                                        wire:confirm="Yakin ingin menghapus file ini?"
                                                        class="text-xs text-red-600 hover:text-red-800">
                                                        Hapus
                                                    </button> --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <input 
                                type="file" 
                                wire:model="spp_card" 
                                class="file-input file-input-bordered file-input-sm w-full @error('spp_card') file-input-error @enderror"
                                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                            />
                            <div wire:loading wire:target="spp_card" class="text-xs text-slate-500 mt-1">
                                Uploading...
                            </div>
                            @error('spp_card') 
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="alert alert-info text-xs">
                        <span>Format file yang didukung: PDF, DOC, DOCX, JPG, JPEG, PNG (Maks. 2MB)</span>
                    </div>
                </div>

                <div class="divider"></div>
                <div class="space-y-4">
                    <h3 class="font-semibold text-slate-700 dark:text-slate-300 flex items-center gap-2">
                        Periode PKL
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="label">
                                <span class="label-text">Tanggal Mulai <span class="text-error">*</span></span>
                            </label>
                            <input 
                                type="date" 
                                wire:model="start_date" 
                                class="input input-bordered w-full @error('start_date') input-error @enderror"
                            />
                            @error('start_date') 
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <div>
                            <label class="label">
                                <span class="label-text">Tanggal Selesai <span class="text-error">*</span></span>
                            </label>
                            <input 
                                type="date" 
                                wire:model="finish_date" 
                                class="input input-bordered w-full @error('finish_date') input-error @enderror"
                            />
                            @error('finish_date') 
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="divider"></div>
                <div class="flex justify-end gap-3">
                    <button 
                        type="button"
                        wire:click="cancel"
                        class="btn btn-ghost">
                        Batal
                    </button>
                    <button 
                        type="submit"
                        class="btn btn-primary"
                        wire:loading.attr="disabled"
                        wire:target="update, industrial_visit, competency_test, spp_card">
                        <span wire:loading.remove wire:target="update">
                            Simpan Perubahan
                        </span>
                        <span wire:loading wire:target="update" class="loading loading-spinner loading-sm"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>