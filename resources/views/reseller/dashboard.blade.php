<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dasbor Distributor (Pemesanan Barang)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 dark:bg-green-900 dark:text-green-300 px-4 py-3 rounded relative mb-4">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 dark:bg-red-900 dark:text-red-300 px-4 py-3 rounded relative mb-4">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 overflow-x-auto">
                    <h3 class="text-lg font-bold mb-4 text-gray-700 dark:text-gray-300 border-b dark:border-gray-700 pb-2">Katalog Produk Tersedia</h3>
                    
                    <form action="{{ route('reseller.checkout') }}" method="POST">
                        @csrf
                        
                        <table class="w-full text-left border-collapse mb-6">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase text-xs leading-normal">
                                    <th class="py-3 px-6 border-b dark:border-gray-600">Nama Produk</th>
                                    <th class="py-3 px-6 border-b dark:border-gray-600">Deskripsi</th>
                                    <th class="py-3 px-6 border-b dark:border-gray-600">Harga Satuan</th>
                                    <th class="py-3 px-6 border-b dark:border-gray-600 text-center">Jumlah Beli (Unit)</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm font-light">
                                @forelse ($products as $index => $item)
                                <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="py-4 px-6 font-semibold text-gray-700 dark:text-gray-200">{{ $item->name }}</td>
                                    <td class="py-4 px-6 text-gray-500 dark:text-gray-400 text-xs">{{ $item->description }}</td>
                                    <td class="py-4 px-6 text-blue-600 dark:text-blue-400 font-medium">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="py-4 px-6 text-center">
                                        <input type="hidden" name="items[{{ $index }}][product_id]" value="{{ $item->id }}">
                                        
                                        <input type="number" name="items[{{ $index }}][quantity]" value="0" min="0" max="{{ $item->stock }}" class="w-24 text-center rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <div class="text-xs text-gray-400 dark:text-gray-500 mt-1">Sisa Stok: {{ $item->stock }}</div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="py-4 px-6 text-center text-gray-500 dark:text-gray-400">Mohon maaf, saat ini semua produk sedang habis.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>

                        @if($products->isNotEmpty())
                        <div class="flex items-center justify-end border-t dark:border-gray-700 pt-4">
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded shadow text-lg w-full sm:w-auto">
                                Proses Pesanan Sekarang
                            </button>
                        </div>
                        @endif
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>