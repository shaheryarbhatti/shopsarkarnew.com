{{--
    Shared delete-confirmation modal.
    Must live inside an Alpine scope that exposes a `confirm` object:
        x-data="{ confirm: { open:false, name:'', action:'', title:'Delete?' } }"
    Trigger it from a button:
        @click="confirm = { open:true, name:'John', action:'/admin/customers/5', title:'Delete Customer?' }"
--}}
<div x-show="confirm.open" x-cloak
     @keydown.escape.window="confirm.open = false"
     class="fixed inset-0 z-[70] flex items-center justify-center p-4">

    {{-- Backdrop --}}
    <div x-show="confirm.open" x-transition.opacity.duration.200ms
         @click="confirm.open = false"
         class="absolute inset-0 bg-brand-900/60 backdrop-blur-sm"></div>

    {{-- Panel --}}
    <div x-show="confirm.open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-90 translate-y-3"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="relative w-full max-w-md overflow-hidden rounded-3xl bg-white shadow-2xl ring-1 ring-black/5">

        {{-- Danger accent bar --}}
        <div class="h-1.5 bg-gradient-to-r from-red-500 via-rose-500 to-red-600"></div>

        <div class="px-8 pb-8 pt-7 text-center">
            {{-- Animated warning icon --}}
            <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-red-50">
                <span class="flex h-14 w-14 items-center justify-center rounded-full bg-gradient-to-br from-red-500 to-rose-600 text-white shadow-lg shadow-red-500/40">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                    </svg>
                </span>
            </div>

            <h3 class="mt-5 font-serif text-2xl font-bold text-ink" x-text="confirm.title">Delete?</h3>
            <p class="mx-auto mt-2 max-w-xs text-sm leading-relaxed text-gray-500">
                Are you sure you want to delete
                <span class="font-semibold text-ink" x-text="confirm.name"></span>?
                This action is <span class="font-semibold text-red-500">permanent</span> and cannot be undone.
            </p>

            <div class="mt-7 flex gap-3">
                <button type="button" @click="confirm.open = false"
                        class="flex-1 rounded-full border border-gray-200 bg-white px-5 py-3 text-sm font-semibold text-gray-600 transition hover:bg-gray-50">
                    Cancel
                </button>
                <form method="POST" :action="confirm.action" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="flex w-full items-center justify-center gap-2 rounded-full bg-gradient-to-r from-red-500 to-rose-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-red-500/30 transition hover:-translate-y-0.5 hover:shadow-red-500/50">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165"/></svg>
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
