<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class NavigationLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        $menus = [
            [
                'name' => 'Dashboard',
                'route' => route('dashboard'),
                'icon' => 'heroicon-o-squares-2x2',
                'active' => request()->routeIs('dashboard'),
                'show' => in_array(auth()->user()->role, ['admin', 'super_admin', 'manager']),
            ],
            [
                'name' => 'Produk',
                'route' => route('admin.product.index'),
                'icon' => 'heroicon-o-archive-box',
                'active' => request()->routeIs('admin.product*'),
                'show' => in_array(auth()->user()->role, ['admin', 'super_admin', 'manager']),
            ],
            [
                'name' => 'Kategori',
                'route' => route('admin.category.index'),
                'icon' => 'heroicon-o-tag',
                'active' => request()->routeIs('admin.category*'),
                'show' => in_array(auth()->user()->role, ['admin', 'super_admin']),
            ],
            [
                'name' => 'Transaksi',
                'route' => route('admin.transaction.index'),
                'icon' => 'heroicon-o-currency-dollar',
                'active' => request()->routeIs('admin.transaction*'),
                'show' => in_array(auth()->user()->role, ['admin', 'super_admin']),
            ],
            [
                'name' => 'Customer',
                'route' => route('admin.customer.index'),
                'icon' => 'heroicon-o-user-group',
                'active' => request()->routeIs('admin.customer*'),
                'show' => in_array(auth()->user()->role, ['admin', 'super_admin', 'manager']),
            ],
            [
                'name' => 'Employee',
                'route' => route('admin.employee.index'),
                'icon' => 'heroicon-o-users',
                'active' => request()->routeIs('admin.employee*'),
                'show' => in_array(auth()->user()->role, ['admin', 'super_admin']),
            ],
            [
                'name' => 'Report',
                'route' => route('admin.report.index'),
                'icon' => 'heroicon-o-document-text',
                'active' => request()->routeIs('admin.report*'),
                'show' => in_array(auth()->user()->role, ['admin', 'super_admin', 'manager']),
            ],
        ];

        return view('livewire.layout.navigation', compact('menus'));
    }
}
