<x-slot:title>
    Dashboard
</x-slot:title>

<div class="flex flex-col gap-4">
    <div class="flex flex-row items-center justify-center gap-1
                text-2xl font-bold w-full h-[120px] content-center text-center 
                border-1 rounded-lg
                text-slate-700 dark:text-slate-200
                border-slate-200 dark:border-slate-700
                bg-white dark:bg-slate-900">
        <h1 class="">
            Selamat Datang,
        </h1>
        <h1> {{ ucwords(auth()->user()->username) }}</h1>
    </div>

    <div class="flex flex-col gap-2">

        <x-ui.table :columns="['No', 'Nama', 'Jurusan', 'Status']">
            <tr>
                <td class="px-4 py-3">1</td>
                <td class="px-4 py-3">aldo gamteng</td>
                <td class="px-4 py-3">pemasaran tingkat lanjut</td>
                <td class="px-4 py-3">
                <span class="px-3 py-1 text-sm font-semibold rounded-full
                 bg-yellow-100 text-yellow-700
                 dark:bg-yellow-900 dark:text-yellow-300">Pending </span>
                 </td>
             </tr>

            <tr>
                <td class="px-4 py-3">2</td>
                <td class="px-4 py-3">gilbran gamteng</td>
                <td class="px-4 py-3">permesinan tingkat lanjut</td>
                <td class="px-4 py-3">
    <span class="px-3 py-1 text-sm font-semibold rounded-full
                 bg-red-100 text-red-700
                 dark:bg-red-900 dark:text-red-300"> Ditolak</span>
                 </td>
            </tr>

            <tr>
                <td class="px-4 py-3">3</td>
                <td class="px-4 py-3">reyhan gamteg</td>
                <td class="px-4 py-3">marketing tingkat lanjut</td>
                <td class="px-4 py-3">
    <span class="px-3 py-1 text-sm font-semibold rounded-full
                 bg-green-100 text-green-700
                 dark:bg-green-900 dark:text-green-300">
        Diterima
    </span>
</td>
            </tr>
            
        </x-ui.table>
        

    </div>
</div>
