<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1>Test Pembayaran Piutang Show</h1>
                    <p>Nomor: {{ $pembayaran->nomor ?? 'N/A' }}</p>
                    <p>Jumlah: Rp {{ number_format($pembayaran->jumlah ?? 0, 0, ',', '.') }}</p>

                    @if ($pembayaran->customer)
                        <p>Customer: {{ $pembayaran->customer->nama }}</p>
                    @endif

                    @if ($pembayaran->invoice)
                        <p>Invoice: {{ $pembayaran->invoice->nomor }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
