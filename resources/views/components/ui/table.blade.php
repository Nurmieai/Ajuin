@props([
'columns' => [],
'rows' => [],
])

<table class="table w-full">
    <thead>
        <tr class="text-slate-700">
            @foreach ($columns as $column)
            <th>{{ $column }}</th>
            @endforeach
        </tr>
    </thead>

    <tbody>
        @forelse ($rows as $index => $row)
        <tr>
            @foreach ($row as $cell)
            <td>{{ $cell }}</td>
            @endforeach
        </tr>
        @empty
        {{-- Dummy / empty state --}}
        <tr>
            <td colspan="{{ count($columns) }}" class="text-center text-slate-400">
                No data available
            </td>
        </tr>
        @endforelse
    </tbody>
</table>