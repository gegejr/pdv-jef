<!-- Cards com gráficos via Livewire (exemplo) -->
<div wire:poll.5s="refresh" class="space-y-4">
    <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 p-5 rounded-xl shadow hover:shadow-xl transition transform hover:scale-105 duration-300">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-md font-semibold text-blue-900 dark:text-blue-100">Total de Vendas</h3>
                <p class="text-3xl font-bold text-blue-700 dark:text-blue-200 mt-2">R$ {{ number_format($totalVendas, 2, ',', '.') }}</p>
            </div>
            <div class="flex items-center justify-center w-12 h-12 rounded-full bg-blue-200/50 dark:bg-blue-700/30">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chart-no-axes-combined-icon"><path d="M12 16v5"/><path d="M16 14v7"/><path d="M20 10v11"/><path d="m22 3-8.646 8.646a.5.5 0 0 1-.708 0L9.354 8.354a.5.5 0 0 0-.707 0L2 15"/><path d="M4 18v3"/><path d="M8 14v7"/></svg>
            </div>
        </div>
        <canvas id="chartTotalVendas" class="mt-4" style="height:70px;"></canvas>
    </div>

    
</div>
