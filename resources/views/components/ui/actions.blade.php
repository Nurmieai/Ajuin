@props(['actions' => []])

<ul class=" bg-transparent flex p-0 justify-between w-full flex justify-evenly">
    @foreach ($actions as $action)
    @php
    $iconName = $action['icon'] ?? 'info';
    $iconColor = $action['color'] ?? 'gray';
    $label = $action['label'] ?? '';
    $url = $action['url'] ?? null;
    $event = $action['event'] ?? null;
    @endphp

    <li class="px-1">
        @if ($url)
        {{-- Jika ada URL, gunakan Tag Anchor --}}
        <a wire:navigate href="{{ $url }}" title="{{ $label }}" class="p-0 tooltip" data-tip="{{ $label }}">
            <x-ui.icon :name="$iconName" :color="$iconColor" />
        </a>
        @elseif ($event)
        {{-- Jika ada Event, gunakan Button Livewire --}}
        <button wire:click.prevent="{{ $event }}" title="{{ $label }}" class="p-0 tooltip" data-tip="{{ $label }}">
            <x-ui.icon :name="$iconName" :color="$iconColor" />
        </button>
        @else
        <span class="p-0 opacity-50"><x-ui.icon :name="$iconName" :color="$iconColor" /></span>
        @endif
    </li>
    @endforeach