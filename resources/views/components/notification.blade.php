<div class="fixed inset-0 flex items-end justify-center px-4 py-6 pointer-events-none sm:p-6 sm:items-start sm:justify-end" style="z-index: 999;">
    <div
        x-data="{ show: false, message: '', type: 'success' }"
        x-on:notify.window="
            let detail = $event.detail[0];
            show = true;
            message = detail.message;
            type = detail.type;
            setTimeout(() => { show = false }, 2500)
        "
        x-show="show"
        x-description="Notification panel, show/hide based on alert state."
        x-transition:enter="transform ease-out duration-300 transition"
        x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
        x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="max-w-sm w-full shadow-lg rounded pointer-events-auto"
        :class="{ 'bg-teal-400': type == 'success', 'bg-red-400': type == 'error', 'bg-orange-400': type == 'warning' }"
        style="display: none;"
    >
        <div class="rounded shadow-xs overflow-hidden">
            <div class="p-4">
                <div class="flex justify-start items-start">
                    <div class="w-0 flex-1 pt-0.5 flex items-center space-x-4">
                        <template x-if="type == 'success'">
                            <i class="rounded-full border-2 border-white shrink-0 h-6 w-6 flex items-center justify-center">
                                <svg class="shrink-0 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </i>
                        </template>
                        <template x-if="type == 'error'">
                            <i class="rounded-full border-2 border-white shrink-0 h-6 w-6 flex items-center justify-center">
                                <svg class="shrink-0 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </i>
                        </template>
                        <template x-if="type == 'warning'">
                            <i class="rounded-full border-2 border-white shrink-0 h-6 w-6 flex items-center justify-center">
                                <svg class="shrink-0 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 12h.01M4.293 6.707a1 1 0 010-1.414L5.414 4.586a1 1 0 011.414 0L12 10.172l5.172-5.172a1 1 0 011.414 0l1.121 1.121a1 1 0 010 1.414L12 16.828l-7.707-7.707z" />
                                </svg>
                            </i>
                        </template>
                        <span x-text="message" class="font-bold text-xs md:text-sm leading-5 text-white"></span>
                    </div>
                    <div class="ml-4 flex-shrink-0 flex">
                        <button @click="show = false" class="mt-0.5 inline-flex focus:outline-none text-white focus:text-gray-500 transition ease-in-out duration-150">
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
