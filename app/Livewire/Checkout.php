<?php

namespace App\Livewire;

use App\Models\Transaction;
use App\Models\Village;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class Checkout extends Component
{
    public $form = [
        'name' => '',
        'phone' => '',
        'address' => '',
        'shipping_method' => '',
        'subdistrict_id' => null,
        'shipping_cost' => 0,
    ];

    public $shippingMethods = [
        'self_pickup' => 'Ambil di Toko',
        'delivery' => 'Dikirim ke Alamat',
    ];

    public function mount()
    {
        $this->form['name'] = auth('customer')->user()->name;
        $this->form['email'] = auth('customer')->user()->email;
        $this->form['phone'] = auth('customer')->user()->phone;
    }

    public function render()
    {
        $carts = auth('customer')->user()->carts()->with('product')->get();
        $total = $carts->pluck('product')->pluck('price')->sum();

        return view('livewire.checkout', compact('carts', 'total'));
    }

    public function process()
    {
        if (!$this->form['shipping_method']) {
            Notification::make()
                ->title('Pilih metode pengiriman terlebih dahulu.')
                ->danger()
                ->send();
            return;
        }

        if ($this->form['shipping_method'] === 'delivery') {
            $this->validate([
                'form.address' => 'required|string|min:10|max:255',
                'form.subdistrict_id' => 'required|exists:villages,id',
            ], attributes: [
                'form.address' => 'Alamat',
                'form.subdistrict_id' => 'Kota/Kecamatan',
            ]);
        }

        $this->validate([
            'form.name' => 'required|string|min:3|max:100',
            'form.phone' => 'required|string|min:10|max:15',
            'form.shipping_method' => 'required|in:self_pickup,delivery',
        ], attributes: [
            'form.name' => 'Nama',
            'form.phone' => 'Nomor Telepon',
            'form.shipping_method' => 'Metode Pengiriman',
        ]);

        DB::transaction(function () {
            $totalPrice = auth('customer')->user()->carts()->with('product')->get()->sum(function ($cart) {
                return $cart->quantity * $cart->product->price;
            });

            $transaction = Transaction::create([
                'customer_id' => auth('customer')->id(),
                'invoice_number' => 'INV-' . now()->format('Ymd') . '-' . auth('customer')->id() . '-' . rand(1000, 9999),
                'name' => $this->form['name'],
                'phone' => $this->form['phone'],
                'shipping_method' => $this->form['shipping_method'],
                'address' => $this->form['address'] ?? null,
                'subdistrict_id' => $this->form['subdistrict_id'] ?? null,
                'shipping_cost' => $this->form['shipping_cost'],
                'total_price' => $totalPrice,
                'grand_total' => $totalPrice + $this->form['shipping_cost'],
                'status' => 'waiting_for_payment',
                'payment_status' => 'unpaid',
            ]);

            $carts = auth('customer')->user()->carts()->with('product')->get();

            foreach ($carts as $cart) {
                $transaction->transactionItems()->create([
                    'product_id' => $cart->product->id,
                    'product_name' => $cart->product->name,
                    'price' => $cart->product->price,
                    'quantity' => $cart->quantity,
                    'subtotal' => $cart->quantity * $cart->product->price,
                ])->product->decrement('stock', $cart->quantity);
            }

            auth('customer')->user()->carts()->delete();

            Notification::make()
                ->title('Transaksi berhasil dibuat.')
                ->success()
                ->send();

            return to_route('payment.index', $transaction->invoice_number);
        });
    }

    #[On('citySelected')]
    public function onSelectCity($id)
    {
        $this->form['subdistrict_id'] = $id;

        $subdistrict = Village::find($id);

        $storeLatitude = config('store.store_coordinates.latitude');
        $storeLongitude = config('store.store_coordinates.longitude');

        $buyerLatitude = $subdistrict->district->latitude;
        $buyerLongitude = $subdistrict->district->longitude;

        $distance = $this->haversineDistance($storeLatitude, $storeLongitude, $buyerLatitude, $buyerLongitude);

        $totalWeight = auth('customer')->user()->carts()->with('product')->get()->sum(function ($cart) {
            return $cart->quantity * ($cart->product->weight_kg ?? 0);
        });

        $distanceCost = ceil($distance) * config('store.store_shipping_costs.delivery.per_km');
        $weightCost = ceil($totalWeight) * config('store.store_shipping_costs.delivery.per_kg');

        $shippingCost = config('store.store_shipping_costs.delivery.base_rate') + $distanceCost + $weightCost;

        $this->form['shipping_cost'] = ceil($shippingCost / 1000) * 1000;
    }

    public function setShippingMethod($type)
    {
        $this->form['shipping_method'] = $type;

        if ($type === 'self_pickup') {
            $this->form['shipping_cost'] = config('store.store_shipping_costs.self_pickup');
        }
    }

    function haversineDistance($lat1, $lon1, $lat2, $lon2, $unit = 'km')
    {
        $earthRadius = $unit === 'km' ? 6371 : 3958.8;

        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);

        $latDelta = $lat2 - $lat1;
        $lonDelta = $lon2 - $lon1;

        $a = sin($latDelta / 2) ** 2 +
            cos($lat1) * cos($lat2) * sin($lonDelta / 2) ** 2;

        $c = 2 * asin(sqrt($a));

        return $earthRadius * $c;
    }
}
