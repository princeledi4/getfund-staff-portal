<div>
    <h2 class="mt-6 text-xl font-semibold text-gray-900 dark:text-white">Welcome to the Getfund Staff Result Portal</h2>

    <p class="mt-4 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
        We're excited to introduce our Staff Results Portal, designed
        with your convenience in mind. In this secure and
        user-friendly platform, you can easily access your
        personalized results by entering your unique Staff ID and Surname.
    </p>

    @if($errorMessage)
        <div class="mt-4 rounded-md bg-red-50 border border-red-200 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800">{{ $errorMessage }}</p>
                </div>
            </div>
        </div>
    @endif

    <form wire:submit="searchStaff" method="post">
        @csrf
        <div class="sm:col-span-4 mb-2 mt-5">
           <div class="mt-2">
                <input
                    id="staff_id"
                    wire:model.live="staff_id"
                    type="text"
                    autocomplete="off"
                    class="block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 shadow-sm placeholder:text-gray-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 sm:text-sm"
                    placeholder="Enter Staff ID"
                >
                @error('staff_id')
                    <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                @enderror
           </div>

           <div class="mt-4">
                <input
                    id="surname"
                    wire:model.live="surname"
                    type="text"
                    autocomplete="off"
                    class="block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 shadow-sm placeholder:text-gray-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 sm:text-sm"
                    placeholder="Enter Surname"
                >
                @error('surname')
                    <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                @enderror
           </div>
       </div>

        <div class="mt-6 mb-10 flex items-center justify-start gap-x-6">
           <button
               type="submit"
               class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
           >Search Staff</button>
       </div>
    </form>
</div>
