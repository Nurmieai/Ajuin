<div class="bg-base-100 shadow rounded-box overflow-hidden">
    @if(!isset($ulasanList) || $ulasanList->isEmpty())
        <div class="p-8 text-center">
            <x-ui.icon name="chat-bubble-left-right" class="size-16 mx-auto text-slate-300 mb-4" />

            @if(isset($pkl) && $pkl->status === 'selesai')
                <h3 class="text-lg font-semibold text-slate-600">Belum ada ulasan</h3>
                <p class="text-slate-500 mb-4">Bagikan pengalaman PKL Anda sekarang!</p>
                <a href="{{ route('ulasan.create', $pkl->id) }}" class="btn btn-primary btn-sm">
                    Tulis Ulasan
                </a>
            @else
                <h3 class="text-lg font-semibold text-slate-600">Belum ada ulasan</h3>
                <p class="text-slate-500">Ulasan dapat diberikan setelah PKL selesai.</p>
            @endif
        </div>
    @else
        <div class="divide-y divide-slate-200 dark:divide-slate-700">
            @foreach($ulasanList as $item)
                <!-- ... item list ... -->
            @endforeach
        </div>
    @endif
</div>