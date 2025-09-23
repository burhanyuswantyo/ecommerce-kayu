<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<x-dropdown>
    <x-slot name="trigger">
        <button class="group block w-full flex-shrink-0" href="#">
            <div class="flex items-center" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-on:profile-updated.window="name = $event.detail.name">
                <div class="flex h-9 w-9 rounded-full border">
                    <p class="m-auto text-sm font-semibold text-gray-500">
                        {{ auth()->user()->initials() }}</p>
                </div>
                <div class="ml-3">
                    <p class="text-start text-sm font-semibold text-gray-800">{{ auth()->user()->name }}</p>
                    <p class="text-start text-xs font-medium text-gray-400">
                        {{ '@' . auth()->user()->username }}</p>
                </div>
                <div class="ml-auto">
                    <x-heroicon-m-chevron-up class="size-5 text-gray-800 group-hover:text-gray-600" />
                </div>
            </div>
        </button>
    </x-slot>

    <x-slot name="content">
        <x-dropdown-link :href="route('profile')" class="flex gap-x-2 font-medium" wire:navigate>
            <x-heroicon-o-user class="size-5" />
            {{ __('Profile') }}
        </x-dropdown-link>

        <!-- Authentication -->
        <button class="w-full text-start" wire:click="logout">
            <x-dropdown-link class="flex gap-x-2 font-medium text-red-500">
                <x-heroicon-o-arrow-left-end-on-rectangle class="size-5" />
                {{ __('Log Out') }}
            </x-dropdown-link>
        </button>
    </x-slot>
</x-dropdown>
