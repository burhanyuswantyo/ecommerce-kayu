<x-customer-layout>
  <div class="py-6">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
        <div class="col-span-2">
          <div class="rounded-xl bg-white p-6 shadow">
            <h3 class="font-semibold">Pembayaran</h3>
            <p class="text-sm text-gray-500">Silakan lakukan pembayaran sesuai dengan total yang tertera di
              bawah
              ini.</p>
            <form action="{{ route('payment.confirm', $transaction->invoice_number) }}" method="post"
              enctype="multipart/form-data">
              @method('put')
              @csrf
              <div class="mt-4 flex flex-col gap-4">
                <div class="mx-auto flex w-fit flex-col items-center rounded-lg border bg-gray-50 px-4 py-2">
                  <span class="font-medium text-gray-500">Total Pembayaran</span>
                  <span
                    class="text-lg font-semibold">Rp{{ number_format($transaction->total_price, 0, ',', '.') }}</span>
                </div>
                <div>
                  <p class="text-sm font-medium text-gray-700">Bukti Pembayaran</p>
                  <input name="payment_evidence"
                    class="mt-2 block w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-600 placeholder-gray-400/70 file:rounded-full file:border-none file:bg-gray-200 file:px-4 file:py-1 file:text-sm file:text-gray-700 focus:border-blue-400 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:placeholder-gray-500 dark:file:bg-gray-800 dark:file:text-gray-200 dark:focus:border-blue-300"
                    type="file" />
                  @error('payment_evidence')
                    <span class="text-danger-500 text-xs">{{ $message }}</span>
                  @enderror
                </div>
                <button
                  class="focus:outline-hidden rounded-lg border border-transparent bg-green-600 px-4 py-1.5 text-sm font-medium text-white hover:bg-green-600 hover:text-white focus:bg-green-700 disabled:pointer-events-none disabled:opacity-50"
                  type="submit">
                  Konfirmasi Pembayaran
                </button>
              </div>
            </form>
          </div>
        </div>
        <div class="rounded-xl bg-white p-6 shadow">
          <h3 class="font-semibold">Metode Pembayaran</h3>
          <p class="text-sm text-gray-500">Silakan melakukan pembayaran ke salah satu rekening di bawah
            ini.
          </p>
          <div class="mt-4 flex flex-col gap-4">
            @foreach (config('store.store_payment_accounts') as $account)
              <div class="flex flex-col items-center">
                <span class="text-sm font-semibold">{{ $account['name'] }}</span>
                <span class="text-sm text-gray-500">{{ $account['account_number'] }}</span>
                <span class="text-sm font-semibold text-gray-500">{{ $account['account_name'] }}</span>
              </div>
            @endforeach
          </div>

        </div>
      </div>
    </div>
</x-customer-layout>
