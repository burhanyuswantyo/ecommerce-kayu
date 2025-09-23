 <?php

 use App\Livewire\Actions\Logout;
 use Livewire\Volt\Component;
 use Livewire\Attributes\On;

 new class extends Component {
     public int $cartCount = 0;
     public int $transactionCount = 0;
     public string $searchKey = '';

     #[On('cart-updated')]
     public function updateCartCount()
     {
         $this->getCartCount();
     }

     public function mount()
     {
         $this->getCartCount();
     }

     public function getCartCount()
     {
         if (auth('customer')->check()) {
             $this->cartCount = auth('customer')->user()->carts()->count();
             $this->transactionCount = auth('customer')->user()->transactions()->completed(false)->count();
         }
     }

     public function search()
     {
         return to_route('product.index', [
             'search' => $this->searchKey,
         ]);
     }

     /**
      * Log the current user out of the application.
      */
     public function logout(Logout $logout): void
     {
         $logout('customer');

         $this->redirect('/', navigate: true);
     }
 }; ?>

 <nav class="bg-white border-gray-100 border-b" x-data="{ open: false }">
     <!-- Primary Navigation Menu -->
     <div class="mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">
         <div class="flex h-16">
             <div class="flex grow">
                 <!-- Logo -->
                 <div class="flex flex-shrink-0 items-center">
                     <a class="flex items-center gap-x-2" href="/" wire:navigate>
                         <x-application-logo class="block fill-current w-auto h-9 text-gray-800 dark:text-gray-200" />
                         <h1 class="font-semibold text-gray-600 text-lg">{{ env('APP_NAME', 'Closer') }}</h1>
                     </a>
                 </div>

                 <!-- Navigation Links -->
                 <div class="hidden sm:flex space-x-8 sm:-my-px sm:ms-10 w-full">
                     <div class="relative my-auto w-full">
                         <span class="left-0 absolute inset-y-0 flex items-center pl-3">
                             <x-heroicon-m-magnifying-glass class="size-5 text-gray-400" />
                         </span>
                         <input
                             class="bg-white dark:bg-gray-900 focus:ring-opacity-40 pr-4 pl-10 border border-gray-200 dark:border-gray-600 focus:border-brown-400 dark:focus:border-brown-300 rounded-md focus:outline-none focus:ring focus:ring-brown-300 w-full text-gray-700 dark:text-gray-300"
                             placeholder="Cari" type="text" wire:keydown.enter="search" wire:model='searchKey'>
                     </div>
                 </div>
             </div>

             <!-- Settings Dropdown -->
             <div class="hidden sm:flex sm:items-center sm:ms-6">
                 @guest('customer')
                     <div class="flex gap-2">
                         <livewire:customer.login-form />
                         <livewire:customer.register-form />
                     </div>
                 @endguest
                 @auth('customer')
                     <a class="inline-flex relative justify-center items-center bg-white hover:bg-gray-50 focus:bg-gray-50 dark:bg-neutral-900 dark:hover:bg-neutral-800 dark:focus:bg-neutral-800 disabled:opacity-50 shadow-2xs border border-gray-200 dark:border-neutral-700 rounded-lg focus:outline-hidden size-10 font-semibold text-gray-800 dark:text-white text-sm disabled:pointer-events-none"
                         href="{{ route('cart.index') }}">
                         <x-heroicon-o-shopping-cart class="size-5" />
                         @if ($cartCount > 0)
                             <span
                                 class="inline-flex top-0 absolute items-center bg-red-500 px-1.5 py-0.5 rounded-full font-medium text-white text-xs -translate-y-1/2 translate-x-1/2 end-0 transform">{{ $cartCount }}</span>
                         @endif
                     </a>
                     <x-customer.dropdown align="right" width="48">
                         <x-slot name="trigger">
                             <button
                                 class="inline-flex items-center px-3 py-2 border border-transparent rounded-md focus:outline-none font-medium text-gray-500 hover:text-gray-700 text-sm leading-4 transition duration-150 ease-in-out">
                                 <div>{{ auth('customer')->user()->name }}</div>

                                 <div class="ms-1">
                                     <x-heroicon-m-chevron-down class="size-4" />
                                 </div>
                             </button>
                         </x-slot>

                         <x-slot name="content">
                             <x-dropdown-link :href="route('transaction.index')">
                                 <div class="flex justify-between">
                                     <span>Transaksi</span>
                                     @if ($transactionCount)
                                         <span
                                             class="inline-flex justify-center items-center bg-red-500 rounded-full size-5 font-medium text-white text-xs transform">{{ $transactionCount }}</span>
                                     @endif
                                 </div>
                             </x-dropdown-link>

                             <!-- Authentication -->
                             <x-dropdown-link class="cursor-pointer" wire:click="logout">
                                 Log Out
                             </x-dropdown-link>
                         </x-slot>
                     </x-customer.dropdown>
                 @endauth
             </div>

             <!-- Hamburger -->
             <div class="sm:hidden flex items-center -me-2">
                 <button @click="open = ! open"
                     class="inline-flex justify-center items-center hover:bg-gray-100 focus:bg-gray-100 p-2 rounded-md focus:outline-none text-gray-400 hover:text-gray-500 focus:text-gray-500 transition duration-150 ease-in-out">
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
     {{-- <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="space-y-1 pt-2 pb-3">
            <x-responsive-nav-link :active="request()->routeIs('dashboard')" :href="route('dashboard')">
                Dashboard
            </x-responsive-nav-link>
        </div>

        @auth
            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-1 border-gray-200 border-t">
                <div class="px-4">
                    <div class="font-medium text-gray-800 text-base">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-gray-500 text-sm">{{ Auth::user()->email }}</div>
                </div>

                <div class="space-y-1 mt-3">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        Profile
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                            Log Out
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @endauth
    </div> --}}
 </nav>
