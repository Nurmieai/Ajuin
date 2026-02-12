<div>   
    <x-ui.input name="fullname" label="Nama Lengkap" value="{{ auth()->user()->fullname }}" disabled/>
    <x-ui.input name="nisn" label="NISN" value="{{ auth()->user()->nisn }}" disabled/>
    <x-ui.input name="major_id" label="Jurusan" value="{{ auth()->user()->major?->name }}" disabled/>
<form wire:submit.prevent="create"> 

    <x-ui.input wire:model='company_name' name="company_name" label="Nama perusahaan" placeholder="Nama perusahaan"/>
    <x-ui.input wire:model='company_email' name="company_email" type="email" label="Email perusahaan" placeholder="Email perusahaan"/>
    <x-ui.input wire:model='company_address' class="max-h-32" name="company_address" type="textarea" label="Alamat perusahaan" placeholder="Alamat perusahaan"/>
    <x-ui.input wire:model='company_phone_number' name="company_phone_number" label="Nomor Telepon perusahaan" placeholder=""/>

    <div class="grid grid-cols-2 gap-4">
        <x-ui.input wire:model='start_date' name="start_date" label="Tanggal Mulai" type="date"/>
        <x-ui.input wire:model='finish_date' name="finish_date" label="Tanggal Selesai" type="date" />
    </div>

    <button type="submit" class="w-full mt-5 py-2 rounded-md bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold transition disabled:opacity-50">
    <span wire:loading.remove>Buat Pengajuan</span>
    <span wire:loading class="loading loading-spinner loading-xs"></span>
    </button>
    
</form>
</div>
