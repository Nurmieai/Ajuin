@props([
'title' => ''
])

<nav class="navbar w-full bg-slate-950 text-slate-300 justify-between">
    <div class="flex flex-row justify-start items-center">
        <label for="my-drawer-4" aria-label="open sidebar" class="btn btn-square btn-ghost lg:hidden">

            {{-- Sidebar Toggle Icon --}}
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-linejoin="round" stroke-linecap="round" stroke-width="2" fill="none" stroke="currentColor" class="my-1.5 inline-block size-4">

                <path d="M4 4m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z"></path>
                <path d="M9 4v16"></path>
                <path d="M14 10l2 2l-2 2"></path>

            </svg>
        </label>

        <div class="px-4">
            {{ $title }}
        </div>
    </div>

    <div class="hover:bg-red-100 px-4">
        <form action="{{ route('logout') }}">
            @csrf
            <button type="submit">logout</button>
        </form>
    </div>
</nav>