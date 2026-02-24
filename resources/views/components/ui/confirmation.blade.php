@props([
'open' => false,
'title' => 'Konfirmasi',
'message' => 'Apakah kamu yakin?',
'confirmText' => 'Ya',
'cancelText' => 'Batal',
'confirmAction' => null,
])

@if($open)
<div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50">
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-xl w-full max-w-md p-6">

        <h3 class="text-lg font-bold mb-3">
            {{ $title }}
        </h3>

        <p class="text-sm text-slate-600 dark:text-slate-300 mb-6">
            {{ $message }}
        </p>

        <div class="flex justify-end gap-3">
            <button
                wire:click="$set('confirmingAction', null)"
                class="btn">
                {{ $cancelText }}
            </button>

            <button
                wire:click="{{ $confirmAction }}"
                class="btn btn-success">
                {{ $confirmText }}
            </button>
        </div>

    </div>
</div>
@endif