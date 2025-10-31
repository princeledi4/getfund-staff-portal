<div>
    <h2 class="mt-6 text-xl font-semibold" style="color: var(--text-primary);">Welcome to the Getfund Staff Portal</h2>

    <p class="mt-4 text-sm leading-relaxed" style="color: var(--text-secondary);">
        We're excited to introduce our Staff Results Portal, designed
        with your convenience in mind. In this secure and
        user-friendly platform, you can easily access your
        personalized results by entering your unique Staff ID.
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
                    maxlength="11"
                    class="block w-full rounded-md py-3 px-4 shadow-sm text-sm transition-all duration-200"
                    style="background-color: var(--bg-secondary); color: var(--text-primary); border: 1px solid var(--border-color);"
                    placeholder="Enter Staff ID"
                    onfocus="this.style.borderColor='var(--getfund-green)'"
                    onblur="this.style.borderColor='var(--border-color)'"
                >
           </div>
       </div>

       <div class="sm:col-span-4 mb-2 mt-3">
           <div class="mt-2">
                <input
                    id="surname"
                    wire:model.live="surname"
                    type="text"
                    autocomplete="off"
                    class="block w-full rounded-md py-3 px-4 shadow-sm text-sm transition-all duration-200"
                    style="background-color: var(--bg-secondary); color: var(--text-primary); border: 1px solid var(--border-color);"
                    placeholder="Enter Surname"
                    onfocus="this.style.borderColor='var(--getfund-green)'"
                    onblur="this.style.borderColor='var(--border-color)'"
                >
           </div>
       </div>

         <div class="mt-6 mb-10 flex items-center justify-start gap-x-6">
           <button
               type="submit"
               class="rounded-md px-6 py-3 text-sm font-semibold shadow-lg transition-all duration-200 hover:shadow-xl transform hover:-translate-y-0.5"
               style="background: linear-gradient(135deg, var(--getfund-green-dark), var(--getfund-green)); color: white;"
           >
               <span class="flex items-center gap-2">
                   <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                   </svg>
                   Search Staff
               </span>
           </button>
       </div>
    </form>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('show-error', (event) => {
            Swal.fire({
                icon: 'error',
                title: 'Identity Verification Failed',
                text: 'We could not confirm your identity.',
                confirmButtonText: 'Try Again',
                confirmButtonColor: '#10b981',
                background: getComputedStyle(document.documentElement).getPropertyValue('--bg-secondary') || '#ffffff',
                color: getComputedStyle(document.documentElement).getPropertyValue('--text-primary') || '#000000',
            });
        });
    });
</script>
@endpush
