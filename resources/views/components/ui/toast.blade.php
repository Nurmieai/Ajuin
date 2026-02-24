@props([
'position' => 'toast-top toast-end', // posisi default
'duration' => 3000, // durasi tampil (ms)
'z' => 'z-[9999]', // z-index
'containerClass' => '', // tambahan class container
'alertClass' => '', // tambahan class alert
'defaultType' => 'success', // default type
])

<div
    x-data="{
        show: false,
        message: '',
        type: '{{ $defaultType }}',
        timeout: null,
        showToast(msg, typ = null) {
            this.message = msg;
            this.type = typ || this.type;
            this.show = true;

            clearTimeout(this.timeout);
            this.timeout = setTimeout(() => {
                this.show = false
            }, {{ $duration }});
        }
    }"
    x-init="
        @if(session()->has('success'))
            showToast('{{ session('success') }}', 'success');
        @elseif(session()->has('error'))
            showToast('{{ session('error') }}', 'error');
        @elseif(session()->has('warning'))
            showToast('{{ session('warning') }}', 'warning');
        @elseif(session()->has('info'))
            showToast('{{ session('info') }}', 'info');
        @endif
    "
    x-on:toast.window="showToast($event.detail.message, $event.detail.type)"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform translate-y-2"
    x-transition:enter-end="opacity-100 transform translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform translate-y-0"
    x-transition:leave-end="opacity-0 transform translate-y-2"
    class="toast {{ $position }} {{ $z }} {{ $containerClass }}"
    style="display: none;">
    <div
        :class="{
            'alert alert-success': type === 'success',
            'alert alert-error text-white': type === 'error',
            'alert alert-warning': type === 'warning',
            'alert alert-info': type === 'info',
        }"
        class="shadow-lg cursor-pointer flex items-center gap-2 {{ $alertClass }}"
        @click="show = false">
        <span x-text="message"></span>
    </div>
</div>