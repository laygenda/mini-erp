<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dasbor Eksekutif (Manajemen Puncak)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Omzet Bisnis</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                        Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-yellow-500">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pesanan Menunggu Staf</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                        {{ $pendingOrdersCount }} <span class="text-lg font-normal text-gray-500">Antrean</span>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-red-500">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Peringatan Stok Tipis</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                        {{ $lowStockProducts->count() }} <span class="text-lg font-normal text-gray-500">Varian</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-700 dark:text-gray-300 mb-4 border-b dark:border-gray-700 pb-2">Visualisasi Sisa Stok Gudang</h3>
                    <div class="relative h-64 w-full">
                        <canvas id="stockChart"></canvas>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-700 dark:text-gray-300 mb-4 border-b dark:border-gray-700 pb-2 text-red-500">Daftar Produk Segera Habis</h3>
                    
                    @if($lowStockProducts->isEmpty())
                        <p class="text-green-600 dark:text-green-400 font-semibold mt-4">Kondisi aman. Seluruh produk memiliki stok di atas 20 unit.</p>
                    @else
                        <ul class="divide-y divide-gray-200 dark:divide-gray-700 mt-2">
                            @foreach($lowStockProducts as $lowStock)
                            <li class="py-3 flex justify-between items-center">
                                <span class="text-gray-800 dark:text-gray-200 font-medium">{{ $lowStock->name }}</span>
                                <span class="bg-red-100 text-red-800 text-xs font-bold px-3 py-1 rounded-full border border-red-200">
                                    Sisa {{ $lowStock->stock }}
                                </span>
                            </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('stockChart').getContext('2d');
            
            // Mengambil data dari backend Laravel ke JavaScript
            const labels = {!! json_encode($chartLabels) !!};
            const data = {!! json_encode($chartData) !!};

            new Chart(ctx, {
                type: 'bar', // Coba ubah menjadi 'pie' atau 'doughnut' nanti jika ingin bereksperimen!
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Jumlah Stok Tersedia',
                        data: data,
                        backgroundColor: 'rgba(59, 130, 246, 0.5)', // Biru Tailwind transparan
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 1,
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0 }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>