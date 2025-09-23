<div class="flex flex-col gap-6">
    @foreach ($transactions as $transaction)
        <div class="bg-white shadow rounded-xl">
            <div class="flex flex-col">
                <div class="flex items-center gap-4 p-4">
                    <a class="font-semibold text-brown-500 hover:text-brown-600"
                        href="{{ route('transaction.invoice', $transaction->invoice_number) }}"
                        target="_blank">{{ $transaction->invoice_number }}</a>
                    <p class="me-auto font-medium text-gray-500 text-sm">
                        {{ $transaction->created_at->translatedFormat('d M Y H:i') }}
                    </p>
                    @if ($transaction->status === 'waiting_for_payment')
                        @if ($transaction->payment_status === 'unpaid')
                            <span
                                class="inline-flex items-center bg-yellow-200 px-2 py-1 rounded-lg font-medium text-red-600 text-xs">Menunggu
                                Pembayaran</span>
                        @elseif ($transaction->payment_status === 'waiting_confirmation')
                            <span
                                class="inline-flex items-center bg-yellow-200 px-2 py-1 rounded-lg font-medium text-red-600 text-xs">Menunggu
                                Konfirmasi</span>
                        @endif
                    @elseif ($transaction->status === 'processed')
                        <span
                            class="inline-flex items-center bg-yellow-200 px-2 py-1 rounded-lg font-medium text-red-600 text-xs">Diproses</span>
                    @elseif ($transaction->status === 'on_delivery')
                        <span
                            class="inline-flex items-center bg-yellow-200 px-2 py-1 rounded-lg font-medium text-red-600 text-xs">Dalam
                            Pengiriman</span>
                    @elseif ($transaction->status === 'ready_pickup')
                        <span
                            class="inline-flex items-center bg-yellow-200 px-2 py-1 rounded-lg font-medium text-red-600 text-xs">Siap
                            Diambil</span>
                    @elseif ($transaction->status === 'completed')
                        <span
                            class="inline-flex items-center bg-green-600 px-2 py-1 rounded-lg font-medium text-white text-xs">Pesanan
                            Selesai</span>
                    @elseif ($transaction->status === 'canceled')
                        <span
                            class="inline-flex items-center bg-red-600 px-2 py-1 rounded-lg font-medium text-white text-xs">Pesanan
                            Dibatalkan</span>
                    @endif
                </div>
                <hr>
                <div class="p-6">
                    <div class="grid grid-cols-4 divide-x">
                        <ul class="space-y-3 col-span-2 px-4 max-h-48 overflow-auto">
                            @foreach ($transaction->transactionItems as $item)
                                <li class="flex items-start gap-2">
                                    <img alt="{{ $item->name }}" class="rounded-md w-12 h-12 object-cover"
                                        src="{{ asset('storage/' . $item->product->images[0]) }}">
                                    <div class="flex flex-col gap-0.5">
                                        <span class="font-medium text-sm">{{ $item->product_name }}</span>
                                        <span class="text-gray-500 text-xs">{{ $item->quantity }} barang x
                                            Rp{{ thousand($item->price) }}</span>
                                    </div>
                                    <span class="ms-auto font-medium text-sm">Rp.
                                        {{ thousand($item->quantity * $item->price) }}</span>
                                </li>
                            @endforeach

                        </ul>
                        <div class="flex flex-col gap-1 px-4">
                            <div>
                                <p class="text-gray-500 text-sm">Tipe Pengiriman</p>
                                <p class="font-semibold text-gray-800 text-sm">
                                    @if ($transaction->shipping_method === 'delivery')
                                        Dikirim ke Alamat
                                    @elseif($transaction->shipping_method === 'self_pickup')
                                        Ambil di Toko
                                    @endif
                                </p>
                                @if ($transaction->shipping_method === 'self_pickup')
                                    <p class="mt-1 font-semibold text-gray-500 text-xs">
                                        {{ config('store.store_address') }}
                                    </p>
                                @endif
                            </div>
                            @if ($transaction->shipping_method === 'delivery')
                                <div>
                                    <p class="text-gray-500 text-sm">Kurir</p>
                                    <p class="font-semibold text-gray-800 text-sm">
                                        {{ $transaction->courier ?? '-' }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-gray-500 text-sm">Nomor Resi</p>
                                    <p class="font-semibold text-gray-800 text-sm">
                                        {{ $transaction->receipt_number ?? '-' }}
                                    </p>
                                </div>
                            @endif
                        </div>
                        <div class="flex flex-col gap-1 px-4">
                            <div>
                                <p class="text-gray-500 text-sm">Subtotal Harga Barang</p>
                                <p class="font-semibold text-sm">
                                    Rp{{ thousand($transaction->total_price) ?? '-' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm">Ongkos Kirim</p>
                                <p class="font-semibold text-sm">
                                    Rp{{ thousand($transaction->shipping_cost) ?? '-' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm">Total Pembelian</p>
                                <p class="font-semibold text-sm">
                                    Rp{{ thousand($transaction->grand_total) ?? '-' }}
                                </p>
                            </div>
                            <div class="flex justify-end items-end gap-2 mt-4 grow">
                                @if ($transaction->status === 'waiting_for_payment' && $transaction->payment_status === 'unpaid')
                                    <a class="items-center gap-x-2 bg-green-600 hover:bg-green-700 focus:bg-green-700 disabled:opacity-50 px-4 py-1.5 border border-transparent rounded-lg focus:outline-hidden font-medium text-white text-sm disabled:pointer-events-none"
                                        href="{{ route('payment.index', $transaction->invoice_number) }}">
                                        Bayar
                                    </a>
                                @endif
                                @if ($transaction->status === 'on_delivery' || $transaction->status === 'ready_pickup')
                                    <button
                                        class="items-center gap-x-2 bg-green-600 hover:bg-green-700 focus:bg-green-700 disabled:opacity-50 px-4 py-1.5 border border-transparent rounded-lg focus:outline-hidden font-medium text-white text-sm disabled:pointer-events-none"
                                        wire:click="complete('{{ $transaction->id }}')">
                                        Selesaikan Pesanan
                                    </button>
                                @endif
                                @if ($transaction->status === 'waiting_for_payment' && $transaction->payment_status === 'unpaid')
                                    <button
                                        class="items-center gap-x-2 bg-red-600 hover:bg-red-700 focus:bg-red-700 disabled:opacity-50 px-4 py-1.5 border border-transparent rounded-lg focus:outline-hidden font-medium text-white text-sm disabled:pointer-events-none"
                                        wire:click="cancel('{{ $transaction->id }}')">
                                        Batalkan Pesanan
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
