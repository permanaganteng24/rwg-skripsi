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
    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            
            <!-- Main Footer Content -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12">
                
                <!-- Brand Section -->
                <div class="lg:col-span-1">
                    <div class="flex items-center gap-2 mb-4">
                        <img src="{{ asset('assets/image/logo-big.png') }}" alt="Rizqi Wood Gallery" class="h-16 w-auto">
                    </div>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Curating timeless wood artistry from NTB for the world's finest interiors.
                    </p>
                </div>

                <!-- Collections -->
                <div>
                    <h4 class="text-sm font-bold mb-4 text-gray-900">Collections</h4>
                    <ul class="space-y-2 text-sm">
                        <li>
                            <a href="/products?category=living-room" class="text-gray-600 hover:text-amber-800 transition">
                                Living Room
                            </a>
                        </li>
                        <li>
                            <a href="/products?category=bedroom" class="text-gray-600 hover:text-amber-800 transition">
                                Bedroom
                            </a>
                        </li>
                        <li>
                            <a href="/products?category=kitchen-dining" class="text-gray-600 hover:text-amber-800 transition">
                                Kitchen & Dining
                            </a>
                        </li>
                        <li>
                            <a href="/products?category=decoration" class="text-gray-600 hover:text-amber-800 transition">
                                Decoration
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Support -->
                <div>
                    <h4 class="text-sm font-bold mb-4 text-gray-900">Support</h4>
                    <ul class="space-y-2 text-sm">
                        <li>
                            <a href="/how-to-order" class="text-gray-600 hover:text-amber-800 transition">
                                How to Order
                            </a>
                        </li>
                        <li>
                            <a href="/reviews" class="text-gray-600 hover:text-amber-800 transition">
                                Reviews & Ratings
                            </a>
                        </li>
                        <li>
                            <a href="/contact" class="text-gray-600 hover:text-amber-800 transition">
                                Custom & Export Inquiry
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Connect With Us -->
                <div>
                    <h4 class="text-sm font-bold mb-4 text-gray-900">Connect With Us</h4>
                    <ul class="space-y-3 text-sm text-gray-600">
                        <li>
                            <a href="https://wa.me/6281945591108" target="_blank" class="flex items-center gap-2 hover:text-amber-800 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.117.554 4.103 1.523 5.824L.057 23.633a.5.5 0 00.61.61l5.809-1.466A11.945 11.945 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.808 9.808 0 01-5.001-1.371l-.359-.214-3.724.94.957-3.625-.234-.373A9.818 9.818 0 012.182 12C2.182 6.57 6.57 2.182 12 2.182S21.818 6.57 21.818 12 17.43 21.818 12 21.818z"/></svg>
                                (+62) 819-4559-1108
                            </a>
                        </li>
                        <li>
                            <a href="https://www.instagram.com/rizqiwoodgallery" target="_blank" class="flex items-center gap-2 hover:text-amber-800 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                                @rizqiwoodgallery
                            </a>
                        </li>
                        <li>
                            <a href="https://www.tiktok.com/@rizqiwoodgallery" target="_blank" class="flex items-center gap-2 hover:text-amber-800 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="currentColor"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>
                                @rizqiwoodgallery
                            </a>
                        </li>
                        <li class="text-gray-600 text-xs leading-relaxed pt-2">
                            Jl. Raya Meninting, Meninting,<br>
                            Kec. Batu Layar, Kabupaten Lombok Barat,<br>
                            Nusa Tenggara Barat 83511, Indonesia
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Footer -->
            <div class="border-t border-gray-200 mt-10 pt-8 text-center">
                <p class="text-sm text-gray-500">
                    © {{ date('Y') }} Rizky Wood Gallery. All rights reserved.
                </p>
            </div>
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