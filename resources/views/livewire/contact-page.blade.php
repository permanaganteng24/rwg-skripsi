<div class="bg-white font-sans text-gray-700">
    <div class="relative h-72 bg-cover bg-center"
        style="background-image: url('assets/image/hero-crop.png');">
        <div class="absolute inset-0 bg-white/60 backdrop-blur-[2px]"></div>
        <div
            class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex flex-col justify-center items-center text-center">
            <h1 class="text-4xl md:text-5xl font-serif font-bold text-gray-900 mb-3 tracking-tight">Contact</h1>
            <nav class="flex justify-center text-sm text-gray-600 gap-2 items-center font-medium">
                <a href="/" class="hover:text-amber-800 transition">Home</a>
                <x-heroicon-m-chevron-right class="w-3 h-3 text-gray-400" />
                <span class="text-gray-900">Contact</span>
            </nav>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">

        <div class="text-center mb-16 max-w-2xl mx-auto">
            <h2 class="text-3xl font-serif font-bold text-gray-900 mb-4">Get In Touch With Us</h2>
            <p class="text-gray-500 text-sm leading-relaxed">
                For More Information About Our Product & Services. Please Feel Free To Drop Us An Email. Our Staff
                Always Be There To Help You Out. Do Not Hesitate!
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-24">

            <div class="space-y-10">

                <div class="flex gap-6 items-start">
                    <div class="flex-shrink-0">
                        <x-heroicon-s-map-pin class="w-8 h-8 text-gray-900" />
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Address</h3>
                        <p class="text-gray-600 text-sm leading-relaxed max-w-xs">
                            Jl. Raya Meninting, Meninting,<br>
                            Kec. Batu Layar, Kabupaten Lombok Barat,<br>
                            Nusa Tenggara Barat 83511, Indonesia
                        </p>
                    </div>
                </div>

                <div class="flex gap-6 items-start">
                    <div class="flex-shrink-0">
                        <x-heroicon-s-phone class="w-8 h-8 text-gray-900" />
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Phone</h3>
                        <p class="text-gray-600 text-sm">
                            (+62) 819-4559-1108
                        </p>
                        <p class="text-gray-600 text-sm mt-1">
                            Mobile: (+62) 812-3456-7890
                        </p>
                    </div>
                </div>

                <div class="flex gap-6 items-start">
                    <div class="flex-shrink-0">
                        <x-heroicon-s-clock class="w-8 h-8 text-gray-900" />
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Working Time</h3>
                        <p class="text-gray-600 text-sm">
                            Monday-Friday: 9:00 - 22:00
                        </p>
                        <p class="text-gray-600 text-sm mt-1">
                            Saturday-Sunday: 9:00 - 21:00
                        </p>
                    </div>
                </div>

            </div>

            <div class="bg-white">
                <form wire:submit.prevent="submitMessage" class="space-y-6">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Your name</label>
                        <input wire:model="name" type="text" placeholder="Abc"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:ring-1 focus:ring-amber-800 focus:border-amber-800 outline-none transition">
                        @error('name')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email address</label>
                        <input wire:model="email" type="email" placeholder="Abc@def.com"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:ring-1 focus:ring-amber-800 focus:border-amber-800 outline-none transition">
                        @error('email')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                        <input wire:model="subject" type="text" placeholder="This is an optional"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:ring-1 focus:ring-amber-800 focus:border-amber-800 outline-none transition">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                        <textarea wire:model="message" rows="4" placeholder="Hi! I'd like to ask about"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:ring-1 focus:ring-amber-800 focus:border-amber-800 outline-none transition"></textarea>
                        @error('message')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <button type="submit" wire:loading.attr="disabled"
                            class="w-full sm:w-auto px-10 py-3 bg-[#6B4226] text-white font-bold rounded-lg hover:bg-[#5D3A20] transition shadow-md flex justify-center items-center gap-2">
                            <span wire:loading.remove>Submit</span>
                            <span wire:loading>Sending...</span>
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>
