@props([
'columns' => [],
'flatRight' => false, // Default false agar tidak merusak tabel di halaman lain
])

<div class="{{ $flatRight ? 'rounded-tl-xl rounded-b-xl' : 'rounded-xl' }} overflow-hidden
            border border-slate-200 dark:border-slate-800 theme-transition">
    <div class="overflow-x-auto">
        <table
            class="table w-full
               bg-white dark:bg-slate-950 
               {{ $flatRight ? 'rounded-tl-xl rounded-b-xl' : 'rounded-xl' }} shadow-sm 
               theme-transition">

            <thead class="">
                <tr
                    class="bg-slate-50 dark:bg-slate-900
                           text-slate-700 dark:text-slate-300
                           {{ $flatRight ? 'rounded-tl-xl' : 'rounded-t-xl' }} overflow-hidden
                           theme-transition">

                    @foreach ($columns as $column)
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider theme-transition">
                        {{ $column }}
                    </th>
                    @endforeach

                </tr>
            </thead>

            <tbody
                class="divide-y divide-slate-200 dark:divide-slate-800
                       text-slate-700 dark:text-slate-300 theme-transition">

                {{ $slot }}

            </tbody>
        </table>
    </div>
</div>