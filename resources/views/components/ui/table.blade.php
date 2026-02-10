@props([
'columns' => [],
])

<div class="overflow-x-auto">
    <table
        class="table w-full
               bg-white dark:bg-slate-950
               border border-slate-200 dark:border-slate-800
               rounded-xl shadow-sm">

        <thead>
            <tr
                class="bg-slate-50 dark:bg-slate-900
                       text-slate-700 dark:text-slate-300
                       border-b border-slate-200 dark:border-slate-800">

                @foreach ($columns as $column)
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                    {{ $column }}
                </th>
                @endforeach

            </tr>
        </thead>

        <tbody
            class="divide-y divide-slate-200 dark:divide-slate-800
                   text-slate-700 dark:text-slate-300">

            {{ $slot }}

        </tbody>
    </table>
</div>