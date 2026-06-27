<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Inventaris Gudang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Area Tombol Aksi -->
            <div class="mb-4 flex justify-end">
                <a href="{{ route('staff.products.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow inline-block">
                    + Tambah Produk Baru
                </a>
            </div>

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Tabel Data -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-100 text-gray-700 uppercase text-sm leading-normal">
                                <th class="py-3 px-6 border-b">ID</th>
                                <th class="py-3 px-6 border-b">Nama SKU</th>
                                <th class="py-3 px-6 border-b">Harga</th>
                                <th class="py-3 px-6 border-b">Stok Tersedia</th>
                                <th class="py-3 px-6 border-b text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm font-light">
                            <!-- Looping Data dari Database -->
                            @foreach ($products as $item)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="py-3 px-6">{{ $item->id }}</td>
                                <td class="py-3 px-6 font-semibold">{{ $item->name }}</td>
                                <td class="py-3 px-6">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td class="py-3 px-6">
                                    <!-- Logika Visual: Jika stok tipis, teks jadi merah -->
                                    <span class="{{ $item->stock < 50 ? 'text-red-600 font-bold' : 'text-green-600' }}">
                                        {{ $item->stock }} Unit
                                    </span>
                                </td>
                                <td class="py-3 px-6 text-center">
                                    <span class="text-blue-500 cursor-pointer hover:underline mx-1">Edit</span>
                                    <span class="text-red-500 cursor-pointer hover:underline mx-1">Hapus</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>