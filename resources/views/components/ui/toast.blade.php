@props([
'position' => 'toast-top toast-end',
'duration' => 3000,
'z' => 'z-[9999]',
'containerClass' => '',
'alertClass' => '',
'defaultType' => 'success',
])

<div
    x-data="{
        show: false,
        message: '',
        type: '{{ $defaultType }}',
        timeout: null,
        showToast(msg, typ = '{{ $defaultType }}') {
            this.message = msg;
            this.type = typ;
            this.show = true;

            clearTimeout(this.timeout);
            this.timeout = setTimeout(() => {
                this.show = false;
            }, {{ $duration }});
        }
    }"
    x-init="
        @if(session()->has('success')) showToast('{{ session('success') }}', 'success'); @endif
        @if(session()->has('error')) showToast('{{ session('error') }}', 'error'); @endif
        @if(session()->has('warning')) showToast('{{ session('warning') }}', 'warning'); @endif
        @if(session()->has('info')) showToast('{{ session('info') }}', 'info'); @endif
    "
    x-on:toast.window="showToast($event.detail.message, $event.detail.type)"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 scale-90"
    x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-90"
    class="toast {{ $position }} {{ $z }} {{ $containerClass }}"
    style="display: none;">

    <div :class="{
            'alert alert-success text-success-content': type === 'success',
            'alert alert-error text-error-content': type === 'error',
            'alert alert-warning text-warning-content': type === 'warning',
            'alert alert-info text-info-content': type === 'info',
         }"
        class="shadow-xl border-none grid-flow-col {{ $alertClass }} cursor-pointer"
        @click="show = false">

        <!-- Ikon Dinamis -->
        <svg x-show="type === 'success'" xmlns="http://www.w3.org" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <svg x-show="type === 'error'" xmlns="http://www.w3.org" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <svg x-show="type === 'warning' || type === 'info'" xmlns="http://www.w3.org" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>

        <span x-text="message" class="text-sm font-medium"></span>
    </div>
</div>