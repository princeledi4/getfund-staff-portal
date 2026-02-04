<div>
    <h2 class="mt-6 text-xl font-semibold text-gray-900 dark:text-white">Welcome to the Getfund Staff Result Portal</h2>

    <p class="mt-4 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
        We're excited to introduce our Staff Results Portal, designed
        with your convenience in mind. In this secure and
        user-friendly platform, you can easily access your
        personalized results by entering your unique Staff ID and Surname.
    </p>

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
