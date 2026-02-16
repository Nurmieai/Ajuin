@props([
'columns' => [],
])
<div class="rounded-xl overflow-hidden
            border border-slate-200 dark:border-slate-800">
    <div class="overflow-x-auto">
        <table
            class="table w-full
               bg-white dark:bg-slate-950
               rounded-xl shadow-sm">

            <thead class="">
                <tr
                    class="rounded-t-4xl
                       bg-slate-50 dark:bg-slate-900
                       text-slate-700 dark:text-slate-300
                       
                       rounded-t-xl overflow-hidden">

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
</div>