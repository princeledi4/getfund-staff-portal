<div>
    <h2 class="mt-6 text-xl font-semibold text-gray-900 dark:text-white">Welcome to the Getfund Staff Result Portal</h2>

    <p class="mt-4 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
        We're excited to introduce our Staff Results Portal, designed
        with your convenience in mind. In this secure and
        user-friendly platform, you can easily access your
        personalized results by entering your unique Staff ID.
    </p>

    <form  wire:submit="searchStaff" method="post">
        @csrf
        <div class="sm:col-span-4 mb-2 mt-5">
           <div class="mt-2">
                <input
                    id="staff_id"
                    wire:model.live="staff_id"
                    type="text"
                    autocomplete="off"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                    placeholder="Enter Staff ID"
                >
                <div class="mt-2 text-xs">
                    @error('staff_id')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>
           </div>
       </div>

         <div class="mt-6 mb-10 flex items-center justify-start gap-x-6">
           <button
               type="submit"
               class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
           >Search Staff</button>
       </div>
    </form>
</div>
