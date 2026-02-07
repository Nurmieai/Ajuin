@props([
'columns' => [],
])

<table class="table w-full p-4 bg-white ">
    <thead>
        <tr class="text-slate-700">
            @foreach ($columns as $column)
            <th class="p-1">{{ $column }}</th>
            @endforeach
        </tr>
    </thead>

    <tbody class="text-slate-700">
        {{ $slot }}
    </tbody>
</table>