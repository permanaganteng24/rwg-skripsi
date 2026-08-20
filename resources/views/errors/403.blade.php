<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Access Denied | Rizqi Wood Gallery</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Importing fonts to match the Furniture/Luxury vibe */
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Inter:wght@300;400;500&display=swap');
        .font-serif { font-family: 'Playfair Display', serif; }
        .font-sans { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-[#FDFBF7] text-stone-800 font-sans h-screen flex flex-col items-center justify-center p-4">

    <div class="fixed top-0 left-0 w-full h-2 bg-amber-700"></div>

    <div class="text-center max-w-lg">
        <div class="flex justify-center mb-6">
            <div class="bg-stone-100 p-4 rounded-full border border-stone-200">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-stone-500">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                </svg>
            </div>
        </div>

        <h1 class="text-6xl md:text-8xl font-serif font-bold text-stone-900 mb-2 tracking-tight">403</h1>
        <h2 class="text-2xl font-serif text-amber-800 mb-4 tracking-wide">Restricted Area</h2>
        
        <p class="text-stone-500 mb-10 leading-relaxed">
            Sorry, you don't have permission to access this page.<br>
            It seems this part of the gallery is strictly for staff.
        </p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ url('/') }}" class="px-8 py-3 bg-stone-900 text-white rounded-md font-medium hover:bg-amber-800 transition-colors duration-300 shadow-lg flex items-center justify-center gap-2 group">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 group-hover:-translate-x-1 transition-transform">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Back to Homepage
            </a>
            
            <a href="{{ url()->previous() }}" class="px-8 py-3 border border-stone-300 text-stone-600 rounded-md font-medium hover:bg-stone-100 transition-colors duration-300">
                Go Back
            </a>
        </div>
    </div>

    <div class="absolute bottom-6 text-stone-400 text-xs tracking-widest uppercase">
        Rizqi Wood Gallery
    </div>

</body>
</html>