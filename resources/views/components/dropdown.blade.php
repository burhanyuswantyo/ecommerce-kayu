<div @click.outside="open = false" @close.stop="open = false" class="relative w-full" x-data="{ open: false }">
    <div @click="open = ! open">
        {{ $trigger }}
    </div>

    <div @click="open = false"
        class="absolute bottom-0 z-50 mb-2 w-full -translate-y-1/2 rounded-md shadow-lg ltr:origin-bottom-right rtl:origin-bottom-left"
        style="display: none;" x-show="open" x-transition:enter-end="opacity-100 scale-100"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter="transition ease-out duration-200"
        x-transition:leave-end="opacity-0 scale-95" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75">
        <div class="rounded-md bg-white py-1 ring-1 ring-black ring-opacity-5 dark:bg-gray-700">
            {{ $content }}
        </div>
    </div>
</div>
