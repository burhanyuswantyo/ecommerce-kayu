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

<nav aria-label="Main navigation" class="bg-white dark:bg-gray-800 border-gray-100 dark:border-gray-700 border-b"
    x-data="{ open: false }">
    <div class="hidden sm:flex bg-gray-100 min-h-screen">
        <div class="flex flex-col w-64">
            <div class="flex flex-col flex-1 bg-white min-h-0">
                <div class="flex flex-col flex-1 pt-5 pb-4 overflow-y-auto">
                    <div class="flex flex-shrink-0 items-center px-4">
                        <a class="flex items-center gap-x-2" href="{{ route('dashboard') }}" wire:navigate>
                            <x-application-logo
                                class="block fill-current w-auto h-9 text-gray-800 dark:text-gray-200" />
                            <h1 class="font-bold text-gray-600 text-xl">{{ env('APP_NAME', 'Closer') }}</h1>
                        </a>
                    </div>
                    <nav aria-label="Sidebar" class="flex-1 space-y-1 mt-5 px-2">
                        @foreach ($menus as $menu)
                            @if ($menu['show'])
                                <x-nav-link :active="$menu['active']" :href="$menu['route']"
                                    :icon="$menu['icon']">{{ __($menu['name']) }}</x-nav-link>
                            @endif
                        @endforeach
                    </nav>
                </div>
                <div class="flex px-6 py-4">
                    <livewire:layout.dropdown />
                </div>
            </div>
        </div>
    </div>

    <!-- Primary Navigation Menu -->
    <div class="sm:hidden mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="flex items-center shrink-0">
                    <a href="{{ route('dashboard') }}" wire:navigate>
                        <x-application-logo class="block fill-current w-auto h-9 text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center bg-white dark:bg-gray-800 px-3 py-2 border border-transparent rounded-md focus:outline-none font-medium text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 dark:text-gray-400 text-sm leading-4 transition duration-150 ease-in-out">
                            <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-on:profile-updated.window="name = $event.detail.name"
                                x-text="name"></div>

                            <div class="ms-1">
                                <svg class="fill-current w-4 h-4" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path clip-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        fill-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile')" wire:navigate>
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <button class="w-full text-start" wire:click="logout">
                            <x-dropdown-link>
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </button>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="sm:hidden flex items-center -me-2">
                <button @click="open = ! open"
                    class="inline-flex justify-center items-center hover:bg-gray-100 focus:bg-gray-100 dark:hover:bg-gray-900 dark:focus:bg-gray-900 p-2 rounded-md focus:outline-none text-gray-400 hover:text-gray-500 focus:text-gray-500 dark:hover:text-gray-400 dark:focus:text-gray-400 dark:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            d="M4 6h16M4 12h16M4 18h16" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" d="M6 18L18 6M6 6l12 12"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="space-y-1 pt-2 pb-3">
            @foreach ($menus as $menu)
                @if ($menu['show'])
                    <x-responsive-nav-link :active="$menu['active']" :href="$menu['route']"
                        wire:navigate>{{ __($menu['name']) }}</x-responsive-nav-link>
                @endif
            @endforeach
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-gray-200 dark:border-gray-600 border-t">
            <div class="px-4">
                <div class="font-medium text-gray-800 dark:text-gray-200 text-base" x-data="{{ json_encode(['name' => auth()->user()->name]) }}"
                    x-on:profile-updated.window="name = $event.detail.name" x-text="name"></div>
                <div class="font-medium text-gray-500 text-sm">{{ auth()->user()->email }}</div>
            </div>

            <div class="space-y-1 mt-3">
                <x-responsive-nav-link :href="route('profile')" wire:navigate>
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <button class="w-full text-start" wire:click="logout">
                    <x-responsive-nav-link>
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </button>
            </div>
        </div>
    </div>
</nav>
