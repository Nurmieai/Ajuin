@props([
'actions' => []
])

<ul class="menu bg-white rounded-box w-40">
    <li>
        <details open>
            <summary>Aksi</summary>
            <ul>
                @foreach ($actions as $action)
                <li>
                    @if (!empty($action['event']))
                    <button
                        wire:click="{{ $action['event'] }}"
                        class="text-left">
                        {{ $action['label'] }}
                    </button>
                    @else
                    {{-- Dummy / UI only --}}
                    <span class="text-slate-400 cursor-not-allowed">
                        {{ $action['label'] }}
                    </span>
                    @endif
                </li>
                @endforeach
            </ul>
        </details>
    </li>
</ul>