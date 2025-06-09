<div wire:poll.5s="refresh" class="space-y-4">
    <div class="bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900 dark:to-blue-800 p-5 rounded-xl shadow hover:shadow-xl transition">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-md font-semibold text-blue-900 dark:text-blue-100">Total de Vendas</h3>
                <p class="text-3xl font-bold text-blue-700 dark:text-blue-200 mt-2">R$ {{ number_format($totalVendas, 2, ',', '.') }}</p>
            </div>
            <div class="flex items-center justify-center w-12 h-12 rounded-full bg-blue-200/50 dark:bg-blue-700/30">
                <!-- ícone SVG aqui -->
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chart-no-axes-combined-icon"><path d="M12 16v5"/><path d="M16 14v7"/><path d="M20 10v11"/><path d="m22 3-8.646 8.646a.5.5 0 0 1-.708 0L9.354 8.354a.5.5 0 0 0-.707 0L2 15"/><path d="M4 18v3"/><path d="M8 14v7"/></svg>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-r from-green-50 to-green-100 dark:from-green-900 dark:to-green-800 p-5 rounded-xl shadow hover:shadow-xl transition">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-md font-semibold text-green-900 dark:text-green-100">Número de Vendas</h3>
                <p class="text-3xl font-bold text-green-700 dark:text-green-200 mt-2">{{ $numeroVendas }}</p>
            </div>
            <div class="flex items-center justify-center w-12 h-12 rounded-full bg-green-200/50 dark:bg-green-700/30">
                <!-- ícone SVG aqui -->
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-up-1-0"><path d="m3 8 4-4 4 4"/><path d="M7 4v16"/><path d="M17 10V4h-2"/><path d="M15 10h4"/><rect x="15" y="14" width="4" height="6" ry="2"/></svg>
            </div>
        </div>
    </div>
</div>
