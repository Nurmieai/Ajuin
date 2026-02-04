<x-slot:title>
    Dashboard
</x-slot:title>

<div class="flex flex-col gap-4">
    <h1 class="text-2xl text-slate-700 font-bold w-full h-[120px] content-center text-center border-4 rounded-lg">Selamat Datang, </h1>
    <div class="flex flex-col gap-2">
        <h1 class="text-2xl text-slate-700 font-bold borde">Dashboard</h1>
        <div class="overflow-x-auto rounded-box border border-base-content/5 bg-white text-slate-700">
            <x-ui.table
                :columns="['#', 'Name', 'Job', 'Favorite Color']"
                :rows="[
        [1, 'Cy Ganderton', 'Quality Control Specialist', 'Blue'],
        [2, 'Hart Hagerty', 'Desktop Support Technician', 'Purple'],
        [3, 'Brice Swyre', 'Tax Accountant', 'Red'],
    ]" />

        </div>
    </div>

</div>