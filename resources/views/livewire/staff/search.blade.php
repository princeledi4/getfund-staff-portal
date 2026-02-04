<div x-data="{ showModal: @entangle('showError') }">
    <h2 class="mt-6 text-xl font-semibold text-gray-900 dark:text-white">Welcome to the Getfund Staff Result Portal</h2>

    <p class="mt-4 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
        We're excited to introduce our Staff Results Portal, designed
        with your convenience in mind. In this secure and
        user-friendly platform, you can easily access your
        personalized results by entering your unique Staff ID and Surname.
    </p>

    <!-- Error Popup Modal -->
    <div x-show="showModal"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        <div class="flex min-h-screen items-center justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showModal = false; $wire.showError = false"></div>

            <!-- Modal panel -->
            <div class="inline-block transform overflow-hidden rounded-lg bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-sm sm:align-middle">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">Invalid Credentials</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">You've entered the wrong credentials. Please check your Staff ID and Surname and try again.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="button"
                            @click="showModal = false; $wire.showError = false"
                            class="inline-flex w-full justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 sm:w-auto sm:text-sm">
                        Try Again
                    </button>
                </div>
            </div>
        </div>
    </div>

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
