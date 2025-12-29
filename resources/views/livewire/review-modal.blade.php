<div>
    @if ($isModalOpen)
        <div class="fixed inset-0 z-[999] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">

            <div class="max-w-4xl mx-auto flex items-center justify-center min-h-screen px-4 text-center sm:p-0">

                <div class="fixed inset-0 bg-stone-900/75 transition-opacity backdrop-blur-sm"
                    style="background-color: rgba(0, 0, 0, 0.75);" wire:click="closeReviewModal" aria-hidden="true">
                </div>

                <div
                    class="relative bg-white rounded-2xl text-left overflow-hidden transform transition-all sm:my-8 sm:max-w-lg w-full border border-stone-100 shadow-2xl">

                    <div class="bg-white px-6 pt-6 pb-4">
                        <div class="text-center sm:text-left">
                            <h3 class="text-xl font-bold text-stone-800 leading-6 mb-1">
                                How was the product?
                            </h3>
                            <p class="text-sm text-stone-500 mb-6 truncate">
                                {{ $product_name }}
                            </p>

                            <div class="space-y-6">

                                <div x-data="{ currentRating: @entangle('rating'), hoverRating: 0 }">
                                    <label
                                        class="block text-xs font-bold uppercase tracking-wider text-stone-400 mb-3 text-center sm:text-left">
                                        Your Rating
                                    </label>
                                    <div class="flex items-center gap-2 justify-center sm:justify-start">
                                        @foreach (range(1, 5) as $star)
                                            <button type="button" @click="currentRating = {{ $star }}"
                                                class="focus:outline-none transition-transform active:scale-95 hover:scale-110 duration-200">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                    fill="currentColor" class="w-10 h-10 transition-colors duration-200"
                                                    :class="currentRating >= {{ $star }} ? 'text-yellow-400' :
                                                        'text-gray-300'">
                                                    <path fill-rule="evenodd"
                                                        d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        @endforeach
                                    </div>
                                    <p class="text-xs text-stone-400 mt-2 text-center sm:text-left font-medium"
                                        x-text="currentRating > 0 ? (currentRating + ' dari 5 Bintang') : 'Pilih bintang rating'">
                                    </p>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-stone-400 mb-2">
                                        Ulasan
                                    </label>
                                    <textarea wire:model="comment" rows="3"
                                        class="w-full bg-stone-50 border border-stone-200 rounded-xl px-4 py-3 text-stone-700 placeholder-stone-400 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition sm:text-sm resize-none"
                                        placeholder="Share your experience using this product..."></textarea>
                                    @error('comment')
                                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-stone-400 mb-2">
                                        Photos (Optional)
                                    </label>

                                    <div class="flex items-start gap-3">
                                        <label class="cursor-pointer group">
                                            <div
                                                class="w-20 h-20 rounded-xl border-2 border-dashed border-stone-300 flex flex-col items-center justify-center text-stone-400 group-hover:border-amber-500 group-hover:text-amber-500 transition bg-stone-50">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="w-6 h-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M12 4.5v15m7.5-7.5h-15" />
                                                </svg>
                                                <span class="text-[10px] font-medium mt-1">Add</span>
                                            </div>
                                            <input type="file" wire:model="photos" multiple class="hidden"
                                                accept="image/*" />
                                        </label>

                                        <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-hide">
                                            @if ($photos)
                                                @foreach ($photos as $photo)
                                                    <div class="relative w-20 h-20 flex-shrink-0">
                                                        <img src="{{ $photo->temporaryUrl() }}"
                                                            class="w-full h-full object-cover rounded-xl border border-stone-100 shadow-sm">
                                                    </div>
                                                @endforeach
                                            @endif

                                            <div wire:loading wire:target="photos"
                                                class="w-20 h-20 rounded-xl bg-stone-100 flex items-center justify-center animate-pulse">
                                                <svg class="w-6 h-6 text-stone-300 animate-spin" fill="none"
                                                    viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                                        stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                    </path>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                    @error('photos.*')
                                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-stone-50 px-6 py-4 flex flex-row-reverse gap-3 border-t border-stone-100">
                        <button wire:click="saveReview" type="button"
                            class="inline-flex justify-center rounded-xl border border-transparent px-6 py-2.5 bg-amber-800 text-sm font-bold text-white shadow-sm hover:bg-amber-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-900 disabled:opacity-50 disabled:cursor-not-allowed transition"
                            wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="saveReview, photos">Kirim Ulasan</span>
                            <span wire:loading wire:target="saveReview, photos" class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Sending...
                            </span>
                        </button>

                        <button wire:click="closeReviewModal" type="button" wire:loading.attr="disabled"
                            class="inline-flex justify-center rounded-xl border border-stone-200 px-6 py-2.5 bg-white text-sm font-bold text-stone-600 shadow-sm hover:bg-stone-50 hover:text-stone-800 focus:outline-none transition">

                            <span wire:loading.remove wire:target="closeReviewModal">Cancel</span>
                            <span wire:loading wire:target="closeReviewModal">Closing...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
