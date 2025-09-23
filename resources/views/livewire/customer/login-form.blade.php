<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;
use App\Livewire\Forms\LoginForm;

new class extends Component {
    public LoginForm $form;

    /**
     * Delete the currently authenticated user.
     */
    public function login(Logout $logout): void
    {
        $this->validate();

        $this->form->authenticate('customer');

        Session::regenerate();

        $this->redirectIntended(default: '/', navigate: true);
    }
}; ?>

<div>
    <button
        class="focus:outline-hidden inline-flex items-center gap-x-2 rounded-lg border border-transparent bg-brown-600 px-3 py-2 text-sm font-medium text-white hover:bg-brown-700 focus:bg-brown-700 disabled:pointer-events-none disabled:opacity-50"
        type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'login-form')">
        Masuk
    </button>

    <x-modal :show="$errors->isNotEmpty()" focusable maxWidth="sm" name="login-form">
        <div class="px-6 py-4">
            <div class="mx-auto flex justify-center">
                <x-application-logo class="h-16" />
            </div>
            <h3 class="mt-3 text-center text-xl font-semibold text-gray-600 dark:text-gray-200">Masuk</h3>
            <form class="mt-6 space-y-4" wire:submit="login">
                <div class="space-y-1">
                    <div class="relative flex items-center">
                        <span class="absolute">
                            <x-heroicon-o-at-symbol class="mx-3 h-6 w-6 text-gray-300 dark:text-gray-500" />
                        </span>

                        <input
                            class="block w-full rounded-lg border bg-white px-11 py-3 text-gray-700 focus:border-blue-400 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-blue-300"
                            name="username" placeholder="Username" type="text" wire:model="form.username">
                    </div>
                    @error('form.username')
                        <p class="text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div class="">
                    <div class="relative flex items-center">
                        <span class="absolute">
                            <x-heroicon-o-lock-closed class="mx-3 h-6 w-6 text-gray-300 dark:text-gray-500" />
                        </span>

                        <input
                            class="block w-full rounded-lg border bg-white px-10 py-3 text-gray-700 focus:border-blue-400 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-blue-300"
                            name="password" placeholder="Password" type="password" wire:model="form.password">
                    </div>
                    @error('form.password')
                        <p class="text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <button
                    class="w-full transform rounded-lg bg-brown-500 px-6 py-2 text-sm font-medium capitalize tracking-wide text-white transition-colors duration-300 hover:bg-brown-400 focus:outline-none focus:ring focus:ring-brown-300 focus:ring-opacity-50">
                    Masuk
                </button>
            </form>
        </div>

        <div class="flex items-center justify-center bg-brown-50 py-4 text-center dark:bg-gray-700">
            <span class="text-sm text-gray-600 dark:text-gray-200">Belum punya akun? </span>

            <a class="mx-2 text-sm font-bold text-brown-500 hover:underline dark:text-brown-400" href="#"
                x-on:click.prevent="$dispatch('close-modal', 'login-form');$dispatch('open-modal', 'register-form')">Daftar</a>
        </div>
    </x-modal>
</div>
