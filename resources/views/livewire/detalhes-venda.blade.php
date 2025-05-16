@if ($vendaSelecionada)
    <div class="space-y-6">
        <div class="flex justify-between items-center border-b-2 border-blue-500 pb-2 mb-4">
            <h3 class="text-2xl font-bold text-blue-700">
                Detalhes da Venda #{{ $vendaSelecionada->id }}
            </h3>
        </div>

        <section>
            <h4 class="text-lg font-semibold text-blue-600 mb-2">Itens Vendidos</h4>
            <ul class="list-disc list-inside space-y-1 max-h-48 overflow-y-auto border border-blue-300 rounded-md p-4 bg-blue-50">
                @foreach ($vendaSelecionada->itens as $item)
                    <li class="flex justify-between text-blue-800 font-medium">
                        <span>{{ $item->produto->nome }} — Qtde: {{ $item->quantidade }}</span>
                        <span class="font-mono bg-blue-100 rounded px-2 py-0.5">R$ {{ number_format($item->venda->total, 2, ',', '.') }}</span>
                    </li>
                @endforeach
            </ul>
        </section>

        <section>
            <h4 class="text-lg font-semibold text-blue-600 mb-2">Desconto</h4>
            <ul class="list-disc list-inside space-y-1 max-h-48 overflow-y-auto border border-blue-300 rounded-md p-4 bg-blue-50">
                    <li class="flex justify-between text-blue-800 font-medium">
                        <span class="font-mono bg-blue-100 rounded px-2 py-0.5">R$ {{ number_format($vendaSelecionada->desconto_total, 2, ',', '.') }}</span>
                    </li>

            </ul>
        </section>

        <section class="flex justify-between text-lg font-bold text-blue-900 border-t border-blue-400 pt-3">
            <span>Total:</span>
            <span class="bg-blue-200 rounded px-3 py-1">
                R$ {{ number_format($vendaSelecionada->total - $vendaSelecionada->desconto_total, 2, ',', '.') }}
            </span>
        </section>

        <section>
            <h4 class="text-lg font-semibold text-blue-600 mb-2 mt-4">Método(s) de Pagamento</h4>
            <ul class="list-disc list-inside space-y-1 border border-blue-300 rounded-md p-4 bg-blue-50 max-h-32 overflow-y-auto">
                @foreach ($vendaSelecionada->pagamentos as $p)
                    <li class="flex justify-between text-blue-800 font-medium">
                        <span>{{ ucfirst($p->tipo) }}</span>
                        <span class="font-mono bg-blue-100 rounded px-2 py-0.5">R$ {{ number_format($p->valor, 2, ',', '.') }}</span>
                    </li>
                @endforeach
            </ul>
        </section>
        <div class="flex justify-end mt-6">
            <a href="{{ route('impressao.cupom', $vendaSelecionada->id) }}" target="_blank" class="btn btn-primary">
                Imprimir Cupom
            </a>
        </div>
    </div>
    
@endif
