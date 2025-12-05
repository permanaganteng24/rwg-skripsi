<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
    <div class="flex h-full items-center">
        <main class="w-full max-w-md mx-auto p-6">
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm">
                <div class="p-4 sm:p-7">
                    <div class="text-center">
                        <h1 class="block text-2xl font-bold text-gray-800">Sign up</h1>
                        <p class="mt-2 text-sm text-gray-600">
                            Already have an account?
                            <a class="text-amber-800 decoration-2 hover:underline font-medium" href="/login">
                                Sign in here
                            </a>
                        </p>
                    </div>

                    <div class="mt-5">
                        <form wire:submit.prevent="register">
                            <div class="grid gap-y-4">
                                <div>
                                    <label for="name" class="block text-sm mb-2">Name</label>
                                    <div class="relative">
                                        <input type="text" id="name" wire:model="name"
                                            class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-amber-800 focus:ring-amber-800 border"
                                            required>
                                        @error('name')
                                            <p class="text-xs text-red-600 mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div>
                                    <label for="email" class="block text-sm mb-2">Email address</label>
                                    <div class="relative">
                                        <input type="email" id="email" wire:model="email"
                                            class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-amber-800 focus:ring-amber-800 border"
                                            required>
                                        @error('email')
                                            <p class="text-xs text-red-600 mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div>
                                    <label for="password" class="block text-sm mb-2">Password</label>
                                    <div class="relative">
                                        <input type="password" id="password" wire:model="password"
                                            class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-amber-800 focus:ring-amber-800 border"
                                            required>
                                        @error('password')
                                            <p class="text-xs text-red-600 mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <button type="submit"
                                    class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-amber-800 text-white hover:bg-amber-900 disabled:opacity-50 disabled:pointer-events-none">
                                    Sign up
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
