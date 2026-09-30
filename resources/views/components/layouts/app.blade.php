<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Rizqi Wood Gallery' }}</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-stone-50 font-sans antialiased text-gray-900 flex flex-col min-h-screen">

    @livewire('navbar')

    <main class="flex-grow">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-charcoal text-stone-400 mt-auto">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-12">
                @foreach ([
                    ['100% Solid Kiln-Dried Teak', 'Certified, sustainably harvested premium hardwood.'],
                    ['Free Lombok Delivery', 'Complimentary delivery across Mataram & West Lombok.'],
                    ['Artisan Master Carvers', 'Every piece finished by veteran local craftsmen.'],
                    ['Custom Dimension Orders', 'Choose your size, finish, and timeline.'],
                ] as [$title, $text])
                    <div class="border border-white/10 rounded-lg p-4">
                        <p class="text-sm font-semibold text-stone-100">{{ $title }}</p>
                        <p class="text-xs mt-1 leading-relaxed">{{ $text }}</p>
                    </div>
                @endforeach
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 pb-10 border-b border-white/10">
                <div>
                    <p class="font-serif font-bold text-lg text-stone-100 mb-3">Rizqi Wood Gallery</p>
                    <p class="text-sm leading-relaxed">Crafting timeless wood artistry from West Nusa Tenggara for the world's finest interiors.</p>
                    <div class="flex gap-4 mt-5 text-sm">
                        <a href="https://www.instagram.com/rizqiwoodgallery" target="_blank" rel="noopener" class="hover:text-white">Instagram</a>
                        <a href="https://www.tiktok.com/@rizqiwoodgallery" target="_blank" rel="noopener" class="hover:text-white">TikTok</a>
                    </div>
                </div>

                <div>
                    <h4 class="text-xs font-semibold uppercase tracking-wider text-gold mb-4">Furniture Collections</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="/products?category=living-room" class="hover:text-white">Living Room</a></li>
                        <li><a href="/products?category=bedroom" class="hover:text-white">Bedroom</a></li>
                        <li><a href="/products?category=kitchen-dining" class="hover:text-white">Kitchen & Dining</a></li>
                        <li><a href="/products?category=decoration" class="hover:text-white">Decoration</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-xs font-semibold uppercase tracking-wider text-gold mb-4">Support & Information</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="/how-to-order" class="hover:text-white">How to Order & Delivery</a></li>
                        <li><a href="/reviews" class="hover:text-white">Customer Reviews & Ratings</a></li>
                        <li><a href="/about" class="hover:text-white">Workshop Story & Wood Provenance</a></li>
                        <li><a href="/contact" class="hover:text-white">Custom & Export Inquiry</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-xs font-semibold uppercase tracking-wider text-gold mb-4">Workshop & Showroom</h4>
                    <p class="text-sm leading-relaxed">
                        Jl. Raya Meninting, Meninting, Kec. Batu Layar,<br>
                        Kabupaten Lombok Barat, Nusa Tenggara Barat 83511, Indonesia
                    </p>
                    <a href="https://wa.me/6281945591108" target="_blank" rel="noopener" class="inline-block mt-3 text-sm hover:text-white">(+62) 819-4559-1108</a>
                </div>
            </div>

            <p class="pt-6 text-xs text-stone-500">&copy; {{ date('Y') }} Rizqi Wood Gallery. All rights reserved.</p>
        </div>
    </footer>

    <livewire:review-modal />

    @livewireScripts
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Livewire.on('alert', (data) => {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: data.type,   
                title: data.message,
                showConfirmButton: false,
                timer: 3000,      
                timerProgressBar: true,
                customClass: {
                    popup: 'colored-toast'
                }
            });
        });
    </script>
</body>
</html>