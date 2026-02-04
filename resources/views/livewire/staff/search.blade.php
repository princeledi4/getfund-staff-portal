<div class="w-full">
    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Welcome to the GETFund Staff Portal</h2>

    <p class="mt-4 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
        Welcome to the Staff Results Portal. Enter your unique Staff ID below to access your personalized information.
    </p>

    <form wire:submit="searchStaff" class="mt-6">
        <div class="space-y-4">
            <div>
                <label for="staff_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Staff ID
                </label>
                <input
                    id="staff_id"
                    wire:model.live="staff_id"
                    type="text"
                    autocomplete="off"
                    class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-gray-900 placeholder-gray-400 shadow-sm focus:border-amber-500 focus:ring-2 focus:ring-amber-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-amber-500 dark:focus:ring-amber-500 sm:text-sm"
                    placeholder="Enter your Staff ID (e.g., GF001)"
                >
                @error('staff_id')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-2">
                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-75 cursor-not-allowed"
                    class="inline-flex w-full items-center justify-center rounded-lg bg-amber-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition-all duration-150 hover:bg-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 disabled:opacity-75 dark:bg-amber-500 dark:hover:bg-amber-400"
                >
                    <span wire:loading.remove>
                        <svg class="mr-2 -ml-1 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                    <span wire:loading>
                        <svg class="mr-2 -ml-1 h-5 w-5 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                    <span wire:loading.remove>Search Staff</span>
                    <span wire:loading>Searching...</span>
                </button>
            </div>
        </div>
    </form>
</div>
