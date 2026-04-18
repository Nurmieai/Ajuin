<div class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-2xl bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-md p-6">

        <div class="mb-5 text-left">
            <h2 class="text-xl font-bold text-slate-800 dark:text-slate-100">Registrasi Siswa</h2>
            <p class="text-xs text-slate-500">Lengkapi data pendaftaran di bawah ini</p>
        </div>

        <form wire:submit.prevent="register" class="space-y-4">
            <x-ui.input name="fullname" label="Nama Lengkap" placeholder="Nama lengkap" wire:model="fullname" />

            <div class="grid grid-cols-2 gap-4">
                <x-ui.input name="username" label="Username" placeholder="Username" wire:model="username" />
                <x-ui.input name="nisn" label="NISN" placeholder="10 digit NISN" wire:model="nisn" />
            </div>

            <x-ui.input name="email" type="email" label="Email" placeholder="email@sekolah.sch.id" wire:model="email" />

            <div>
                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Jurusan</label>
                <select wire:model="major_id" class="w-full px-3 py-1.5 text-sm rounded-md bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 focus:ring-2 focus:ring-blue-500 outline-none transition">
                    <option value="">Pilih Jurusan</option>
                    @foreach($majors as $major)
                    <option value="{{ $major->id }}">{{ $major->name }}</option>
                    @endforeach
                </select>
                @error('major_id') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <x-ui.input name="password" type="password" label="Password" placeholder="••••••" wire:model.defer="password" />
                <x-ui.input name="password_confirmation" type="password" label="Konfirmasi" placeholder="••••••" wire:model.defer="password_confirmation" />
            </div>

            <div class="flex justify-between items-center text-sm">

                <a wire:navigate href="{{ route('login') }}"
                    class="text-slate-500 dark:text-slate-400
                              hover:text-blue-600 dark:hover:text-blue-400
                              transition">
                    Login disini
                </a>

            </div>
            <button type="submit" class="w-full mt-2 py-2 rounded-md bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold transition disabled:opacity-50">
                <span wire:loading.remove>Daftar Sekarang</span>
                <span wire:loading class="loading loading-spinner loading-xs"></span>
            </button>
        </form>
    </div>
</div>