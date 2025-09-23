<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;

new class extends Component {
    public $name = '';
    public $username = '';
    public $email = '';
    public $phone = '';
    public $password = '';
    public $password_confirmation = '';

    /**
     * Delete the currently authenticated user.
     */
    public function register(Logout $logout): void
    {
        $validated = $this->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'username' => ['required', 'string', 'lowercase', 'max:50', 'unique:' . User::class, 'alpha_dash'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
                'phone' => ['required', 'numeric', 'digits_between:10,15'],
                'password' => ['required', 'string', 'confirmed', 'min:8'],
            ],
            [],
            [
                'name' => 'Nama',
                'username' => 'Username',
                'email' => 'Email',
                'phone' => 'Nomor Handphone',
                'password' => 'Password',
                'password_confirmation' => 'Konfirmasi Password',
            ],
        );

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered(($user = Customer::create($validated))));

        Auth::guard('customer')->login($user);

        $this->redirect('/', navigate: true);
    }
}; ?>

<div>
    <button
        class="focus:outline-hidden inline-flex items-center gap-x-2 rounded-lg border border-brown-600 px-3 py-2 text-sm font-medium text-brown-600 hover:border-brown-500 hover:text-brown-500 focus:border-gray-500 focus:text-gray-500 disabled:pointer-events-none disabled:opacity-50 dark:border-white dark:text-white dark:hover:border-neutral-300 dark:hover:text-neutral-300"
        type="button"x-data="" x-on:click.prevent="$dispatch('open-modal', 'register-form')">
        Daftar
    </button>

    <x-modal :show="$errors->isNotEmpty()" focusable maxWidth="sm" name="register-form">
        <div class="px-6 py-4">
            <div class="mx-auto flex justify-center">
                <x-application-logo class="h-16" />
            </div>
            <h3 class="mt-3 text-center text-xl font-semibold text-gray-600 dark:text-gray-200">Daftar Sekarang</h3>
            <form class="mt-6 space-y-4" wire:submit="register">
                <div class="space-y-1">
                    <div class="relative flex items-center">
                        <span class="absolute">
                            <x-heroicon-o-user class="mx-3 h-6 w-6 text-gray-300 dark:text-gray-500" />
                        </span>

                        <input
                            class="block w-full rounded-lg border bg-white px-11 py-3 text-gray-700 focus:border-blue-400 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-blue-300"
                            placeholder="Nama" type="text" wire:model="name">
                    </div>
                    @error('name')
                        <p class="text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div class="space-y-1">
                    <div class="relative flex items-center">
                        <span class="absolute">
                            <x-heroicon-o-at-symbol class="mx-3 h-6 w-6 text-gray-300 dark:text-gray-500" />
                        </span>

                        <input
                            class="block w-full rounded-lg border bg-white px-11 py-3 text-gray-700 focus:border-blue-400 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-blue-300"
                            placeholder="Username" type="text" wire:model="username">
                    </div>
                    @error('username')
                        <p class="text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1">
                    <div class="relative flex items-center">
                        <span class="absolute">
                            <x-heroicon-o-envelope class="mx-3 h-6 w-6 text-gray-300 dark:text-gray-500" />
                        </span>

                        <input
                            class="block w-full rounded-lg border bg-white px-11 py-3 text-gray-700 focus:border-blue-400 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-blue-300"
                            placeholder="Email" type="email" wire:model="email">
                    </div>
                    @error('email')
                        <p class="text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1">
                    <div class="relative flex items-center">
                        <span class="absolute">
                            <x-heroicon-o-phone class="mx-3 h-6 w-6 text-gray-300 dark:text-gray-500" />
                        </span>

                        <input
                            class="block w-full rounded-lg border bg-white px-11 py-3 text-gray-700 focus:border-blue-400 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-blue-300"
                            placeholder="Nomor Handphone" type="text" wire:model="phone">
                    </div>
                    @error('phone')
                        <p class="text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1">
                    <div class="relative flex items-center">
                        <span class="absolute">
                            <x-heroicon-o-lock-closed class="mx-3 h-6 w-6 text-gray-300 dark:text-gray-500" />
                        </span>

                        <input
                            class="block w-full rounded-lg border bg-white px-10 py-3 text-gray-700 focus:border-blue-400 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-blue-300"
                            placeholder="Password" type="password" wire:model="password">
                    </div>
                    @error('password')
                        <p class="text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1">
                    <div class="relative flex items-center">
                        <span class="absolute">
                            <x-heroicon-o-lock-closed class="mx-3 h-6 w-6 text-gray-300 dark:text-gray-500" />
                        </span>

                        <input
                            class="block w-full rounded-lg border bg-white px-10 py-3 text-gray-700 focus:border-blue-400 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-blue-300"
                            placeholder="Konfirmasi Password" type="password" wire:model="password_confirmation">
                    </div>
                    @error('password_confirmation')
                        <p class="text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <button
                    class="w-full transform rounded-lg bg-brown-500 px-6 py-2 text-sm font-medium capitalize tracking-wide text-white transition-colors duration-300 hover:bg-brown-400 focus:outline-none focus:ring focus:ring-brown-300 focus:ring-opacity-50">
                    Daftar
                </button>
            </form>
        </div>

        <div class="flex items-center justify-center bg-brown-50 py-4 text-center dark:bg-gray-700">
            <span class="text-sm text-gray-600 dark:text-gray-200">Sudah punya akun? </span>

            <a class="mx-2 text-sm font-bold text-brown-500 hover:underline dark:text-brown-400" href="#"
                x-on:click.prevent="$dispatch('close-modal', 'register-form');$dispatch('open-modal', 'login-form')">Masuk</a>
        </div>
    </x-modal>
</div>
