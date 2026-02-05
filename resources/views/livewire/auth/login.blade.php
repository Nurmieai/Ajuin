<div class="h-screen bg-base-200 flex items-center justify-center overflow-hidden">
    <div class="w-full max-w-md px-6">
        <div class="card bg-base-100 shadow-xl rounded-2xl">
            <div class="card-body space-y-6">
                <div class="text-center">
                    <h1 class="text-3xl font-semibold tracking-tight">
                        Welcome Back
                    </h1>
                    <p class="text-sm text-gray-400">
                        Please login to your account
                    </p>
                </div>
                <form wire:submit.prevent="login" class="space-y-5">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Email</span>
                        </label>
                        <label
                            class="input input-bordered flex items-center gap-2
                            focus-within:ring-2 focus-within:ring-primary
                            @error('email') input-error @enderror">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-5 w-5 opacity-50"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M16 12a4 4 0 01-8 0 4 4 0 018 0z"/>
                            </svg>
                            <input
                                wire:model.defer="email"
                                type="email"
                                placeholder="email@example.com"
                                class="grow" />
                        </label>
                        @error('email')
                        <span class="text-error text-sm mt-1">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Password</span>
                        </label>
                        <label
                            class="input input-bordered flex items-center gap-2
                            focus-within:ring-2 focus-within:ring-primary
                            @error('password') input-error @enderror">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-5 w-5 opacity-50"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M12 15v2m0-10a4 4 0 00-4 4v3h8V11a4 4 0 00-4-4z"/>
                            </svg>
                            <input
                                wire:model.defer="password"
                                type="password"
                                placeholder="••••••••"
                                class="grow" />
                        </label>
                        @error('password')
                        <span class="text-error text-sm mt-1">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>
                    <button
                        type="submit"
                        class="btn btn-primary w-full text-base tracking-wide">
                        Login
                    </button>
                </form>
                @if (session()->has('message'))
                    <div class="alert alert-warning shadow-sm text-sm">
                        {{ session('message') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

