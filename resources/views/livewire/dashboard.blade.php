<x-slot:title>
    Dashboard
</x-slot:title>

<div class="flex flex-col gap-4">
    <div class="flex flex-row items-center justify-center gap-1
                text-2xl font-bold w-full h-[120px] content-center text-center 
                border-4 rounded-lg
                text-slate-700 dark:text-slate-200
                border-slate-200 dark:border-slate-700
                bg-white dark:bg-slate-800">
        <h1 class="">
            Selamat Datang,
        </h1>
        <h1> {{ ucwords(auth()->user()->username) }}</h1>
    </div>

    <div class="flex flex-col gap-2">

        <h1 class="text-2xl font-bold text-slate-700 dark:text-slate-200">
            Dashboard
        </h1>

        <x-ui.table :columns="['#', 'Name', 'Job', 'Favorite Color']">
            <tr>
                <td class="px-4 py-3">1</td>
                <td class="px-4 py-3">Cy Ganderton</td>
                <td class="px-4 py-3">Quality Control Specialist</td>
                <td class="px-4 py-3">Blue</td>
            </tr>

            <tr>
                <td class="px-4 py-3">2</td>
                <td class="px-4 py-3">Hart Hagerty</td>
                <td class="px-4 py-3">Desktop Support Technician</td>
                <td class="px-4 py-3">Purple</td>
            </tr>

            <tr>
                <td class="px-4 py-3">3</td>
                <td class="px-4 py-3">Brice Swyre</td>
                <td class="px-4 py-3">Tax Accountant</td>
                <td class="px-4 py-3">Red</td>
            </tr>
        </x-ui.table>

    </div>
</div>