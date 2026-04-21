@props([
'name' => null,
'label' => null,
'type' => 'text',
'placeholder' => '',
'options' => [],
'multiple' => false
])

<div class="w-full">
    @if($label)
    <label class="label py-1" for="{{ $name }}">
        <span class="label-text font-semibold text-slate-600 dark:text-slate-300">
            {{ $label }}
        </span>
    </label>
    @endif

    @php
    $baseClass = "
    w-full
    bg-white dark:bg-slate-900
    text-slate-800 dark:text-slate-100
    placeholder-slate-400 dark:placeholder-slate-500
    border
    focus:ring-1 dark:focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-500
    focus:border-blue-600 dark:focus:border-blue-500
    break-words whitespace-normal
    ";

    $borderClass = $errors->has($name)
    ? 'border-red-500 dark:border-red-500'
    : 'border-slate-300 dark:border-slate-700';
    @endphp

    {{-- TEXTAREA --}}
    @if($type === 'textarea')
    <textarea
        id="{{ $name }}"
        wire:model="{{ $name }}"
        placeholder="{{ $placeholder }}"
        {{ $attributes->merge(['class' => "textarea textarea-bordered h-24 $baseClass $borderClass"]) }}>
    </textarea>

    {{-- SELECT --}}
    @elseif($type === 'select')
    <select
        id="{{ $name }}"
        wire:model="{{ $name }}"
        @if($multiple) multiple @endif
        {{ $attributes->merge([
            'class' => "select select-bordered $baseClass $borderClass " . ($multiple ? 'h-auto' : '')
        ]) }}>
        @if($placeholder && !$multiple)
        <option value="">{{ $placeholder }}</option>
        @endif
        @foreach($options as $value => $text)
        <option value="{{ $value }}">{{ $text }}</option>
        @endforeach
    </select>

    {{-- FILE --}}
    @elseif($type === 'file')
    @php
    $wireModel = $attributes->get('wire:model') ?? $attributes->get('wire:model.live') ?? $attributes->get('wire:model.defer') ?? $name;
    @endphp

    <div
        x-data="{ 
            isUploading: false, 
            progress: 0,
            fileName: null,
            fileSize: null,
            hasFile: false,
            frontEndError: null
        }"
        x-on:reset-file-inputs.window="
            $refs.fileInput.value = ''; 
            fileName = null; 
            fileSize = null; 
            hasFile = false; 
            isUploading = false; 
            progress = 0;
            frontEndError = null;
        ">

        <input
            id="{{ $name }}"
            type="file"
            x-ref="fileInput"
            x-on:change="
                const file = $refs.fileInput.files[0];
                frontEndError = null; // Reset error sebelumnya
                
                if (!file) {
                    fileName = null;
                    fileSize = null;
                    hasFile = false;
                    return;
                }
                
                // 1. Validasi Ukuran File (Maksimal 2MB)
                if (file.size > 2097152) {
                    frontEndError = 'Ukuran file maksimal 2MB.';
                    $refs.fileInput.value = '';
                    fileName = null;
                    fileSize = null;
                    hasFile = false;
                    return; 
                }

                // 2. Validasi Ekstensi File
                const allowedExtensions = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
                const fileExt = file.name.split('.').pop().toLowerCase();
                if (!allowedExtensions.includes(fileExt)) {
                    frontEndError = 'Format file harus berupa PDF, DOC, DOCX, JPG, atau PNG.';
                    $refs.fileInput.value = '';
                    fileName = null;
                    fileSize = null;
                    hasFile = false;
                    return;
                }

                // 3. Jika Lulus Validasi, Persiapkan Tampilan
                fileName = file.name;
                fileSize = (file.size / 1024).toFixed(1) + ' KB';
                hasFile = true;
                isUploading = true;
                progress = 0;
                
                // 4. Eksekusi Upload Manual ke Livewire
                $wire.upload('{{ $wireModel }}', file,
                    (uploadedFilename) => {
                        isUploading = false;
                        progress = 0;
                    },
                    (error) => {
                        isUploading = false;
                        progress = 0;
                        $refs.fileInput.value = '';
                        hasFile = false;
                        frontEndError = 'Gagal mengupload file ke server.';
                    },
                    (event) => {
                        progress = event;
                    }
                );
            "
            {{ $attributes->except(['wire:model', 'wire:model.live', 'wire:model.defer'])->merge([
                'class' => "
                    file-input
                    w-full
                    bg-white dark:bg-slate-900
                    text-slate-800 dark:text-slate-100
                    border $borderClass
                    file-input-bordered
                    file:bg-blue-600
                    file:text-white
                    file:border-none
                    hover:file:bg-blue-700
                    dark:file:bg-blue-500
                    dark:hover:file:bg-blue-400
                "
            ]) }} />

        {{-- Progress Bar --}}
        <div x-show="isUploading" class="mt-2" x-transition>
            <div class="w-full bg-slate-200 rounded-full h-2.5 dark:bg-slate-700">
                <div class="bg-blue-600 h-2.5 rounded-full theme-transition" :style="'width: ' + progress + '%'"></div>
            </div>
            <div class="text-xs text-slate-500 mt-1" x-text="'Uploading: ' + progress + '%'"></div>
        </div>

        {{-- Preview File dengan Alpine --}}
        <div x-show="hasFile && !isUploading && fileName"
            x-transition:enter="theme-transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform -translate-y-2"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="theme-transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform -translate-y-2"
            class="mt-2 p-2 bg-green-50 dark:bg-green-900/20 rounded border border-green-200 dark:border-green-800">
            <div class="flex items-center gap-2 text-sm text-green-700 dark:text-green-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span class="truncate flex-1" x-text="fileName"></span>
                <span class="text-xs text-slate-500 shrink-0" x-text="fileSize"></span>
                <button
                    type="button"
                    x-on:click="
                        $refs.fileInput.value = '';
                        fileName = null;
                        fileSize = null;
                        hasFile = false;
                        frontEndError = null;
                        $wire.set('{{ $wireModel }}', null);
                    "
                    class="text-red-500 hover:text-red-700 p-1 rounded hover:bg-red-100 dark:hover:bg-red-900/30 theme-transition"
                    title="Hapus file">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Tampilan Error Frontend (Alpine) --}}
        <div x-show="frontEndError" style="display: none;" class="text-red-500 text-xs mt-1 flex items-center gap-1 animate-pulse">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <span x-text="frontEndError"></span>
        </div>
    </div>

    <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">
        Max size 2MB (PDF, DOC, DOCX, JPG, PNG)
    </div>

    {{-- DEFAULT INPUT --}}
    @else
    <input
        id="{{ $name }}"
        type="{{ $type }}"
        wire:model="{{ $name }}"
        placeholder="{{ $placeholder }}"
        {{ $attributes->merge(['class' => "input input-bordered $baseClass $borderClass"]) }}>
    @endif

    {{-- Tampilan Error Backend (Livewire) --}}
    @error($name)
    <div class="text-red-500 text-xs mt-1 flex items-center gap-1 animate-pulse">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
        {{ $message }}
    </div>
    @enderror
</div>