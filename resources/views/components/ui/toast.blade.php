<div
    x-data="{
        show: false,
        message: '',
        type: 'success',
        showToast(msg, typ) {
            this.message = msg;
            this.type = typ || 'success';
            this.show = true;
            setTimeout(() => { this.show = false }, 3000);
        }
    }"
    x-init="
        @if(session()->has('success'))
            showToast('{{ session('success') }}', 'success');
        @elseif(session()->has('error'))
            showToast('{{ session('error') }}', 'error');
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
    class="toast toast-top toast-end z-[9999]"
    style="display: none;">
    <div :class="{
            'alert alert-success': type === 'success',
            'alert alert-error': type === 'error',
            'alert alert-warning': type === 'warning',
            'alert alert-info': type === 'info',
        }"
        class="shadow-lg cursor-pointer flex items-center gap-2"
        @click="show = false">
        <span x-text="message"></span>
    </div>
</div>