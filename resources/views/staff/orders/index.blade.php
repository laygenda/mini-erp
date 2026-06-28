<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Pesanan Masuk (Order Fulfillment)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 overflow-x-auto">
                    
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-100 text-gray-700 uppercase text-xs leading-normal">
                                <th class="py-3 px-6 border-b">ID Nota</th>
                                <th class="py-3 px-6 border-b">Tanggal Masuk</th>
                                <th class="py-3 px-6 border-b">Nama Reseller</th>
                                <th class="py-3 px-6 border-b">Total Tagihan</th>
                                <th class="py-3 px-6 border-b text-center">Status</th>
                                <th class="py-3 px-6 border-b text-center">Aksi Verifikasi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm font-light">
                            @forelse ($orders as $order)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="py-3 px-6 font-bold">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                                <td class="py-3 px-6">{{ $order->created_at->format('d M Y, H:i') }}</td>
                                <td class="py-3 px-6 font-semibold">{{ $order->user->name }}</td>
                                <td class="py-3 px-6 font-bold text-blue-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                <td class="py-3 px-6 text-center">
                                    @if($order->status === 'pending')
                                        <span class="bg-yellow-200 text-yellow-700 py-1 px-3 rounded-full text-xs font-bold uppercase">Pending</span>
                                    @elseif($order->status === 'diproses')
                                        <span class="bg-blue-200 text-blue-700 py-1 px-3 rounded-full text-xs font-bold uppercase">Diproses</span>
                                    @else
                                        <span class="bg-green-200 text-green-700 py-1 px-3 rounded-full text-xs font-bold uppercase">Selesai</span>
                                    @endif
                                </td>
                                <td class="py-3 px-6 text-center">
                                    @if($order->status === 'pending')
                                        <form action="{{ route('staff.orders.approve', $order->id) }}" method="POST" onsubmit="return confirm('Setujui pesanan ini dan potong stok dari gudang?');">
                                            @csrf
                                            <button type="submit" class="bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-1 px-4 rounded text-xs">
                                                Setujui & Proses
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-gray-400 text-xs italic">Terkunci</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-4 px-6 text-center text-gray-500">Belum ada pesanan masuk sama sekali.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('staff.dashboard') }}" class="text-blue-500 hover:underline font-semibold">&larr; Kembali ke Manajemen Inventaris</a>
            </div>

        </div>
    </div>
</x-app-layout>