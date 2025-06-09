<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Livewire\ProdutoForm;
use App\Livewire\ProdutoLista;
use App\Livewire\Carrinho;
use App\Livewire\RelatorioVendas;
use App\Livewire\FecharVenda;
use App\Models\Venda;
use Illuminate\Http\Request;
use App\Livewire\CaixaSangria;
use App\Models\Caixa;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CupomController;
use App\Livewire\ProdutoPerdaLista;
use App\Livewire\ClienteLista;
use App\Livewire\ClientesContas;
use App\Http\Controllers\ExportarRelatorioController;
use App\Http\Controllers\SubscriptionController;
use App\Livewire\Mesas;
use App\Http\Controllers\ComandaController;
use App\Livewire\Search;
use App\Livewire\UsuariosCrud;
use App\Http\Controllers\ExportarProdutos;

// Página inicial
Route::get('/', function () {
    /** @var \Illuminate\Contracts\Auth\Guard $auth */
    $auth = auth();

    if ($auth->check()) {
        return redirect()->route('painel');
    }

    return view('auth.login');
})->name('login'); // Define nome para rota de login

Route::get('/assinatura-expirada', function () {
    return view('subscription.expired');
})->name('subscription.expired');

Route::post('/subscription/unlock', [SubscriptionController::class, 'unlock'])->name('subscription.unlock');

Route::get('/painel', function (Request $request) {
    $inicio = $request->input('inicio');
    $fim = $request->input('fim');

    // ✅ Inicializa a query
    $query = Venda::query();

    // Aplica filtro para ignorar estornadas
    $query->where('status', '!=', 'estornada');

    if ($inicio && $fim) {
        $query->whereBetween('created_at', ["$inicio 00:00:00", "$fim 23:59:59"]);
    }

    $totalVendas = $query->selectRaw('SUM(total - desconto_total) as total_corrigido')->value('total_corrigido');
    $numeroVendas = $query->count();

    $ultimasVendas = Venda::with('user', 'cliente') // Adiciona 'cliente' se você usa isso na view
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();

    $vendasPorData = Venda::selectRaw('DATE(created_at) as data, SUM(total) as total')
        ->when($inicio && $fim, fn($q) => $q->whereBetween('created_at', ["$inicio 00:00:00", "$fim 23:59:59"]))
        ->groupBy('data')
        ->orderBy('data')
        ->get();

    $datas = $vendasPorData->pluck('data');
    $valores = $vendasPorData->pluck('total');

    $historicoCaixas = Caixa::whereNotNull('fechado_em')
        ->orderByDesc('fechado_em')
        ->limit(10)
        ->get();

    $vendas = Venda::with('caixa')->get();
    $caixa = Caixa::whereNull('fechado_em')->first();

    return view('painel', compact(
        'totalVendas',
        'numeroVendas',
        'inicio',
        'fim',
        'ultimasVendas',
        'datas',
        'valores',
        'historicoCaixas',
        'vendas',
        'caixa'
    ));
})->middleware('auth')->name('painel');

// Perfil do usuário
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Autenticação (rotas geradas pelo Laravel Breeze / Fortify / Jetstream)
require __DIR__.'/auth.php';

// Produtos
Route::middleware('auth')->group(function () {
    Route::get('/produtos', ProdutoForm::class)->name('produtos');
    Route::get('/produtos/lista', ProdutoLista::class)->name('produtos.lista');
    Route::get('/produtos/adicionar', ProdutoForm::class)->name('adicionar-produto');
    Route::get('/produtos/editar/{id}', ProdutoForm::class)->name('produto-edit');
    Route::get('produtos/perda/lista/{produto_id?}', ProdutoPerdaLista::class)->name('produtos-perda-lista');
});

// Carrinho
Route::middleware('auth')->get('/carrinho', Carrinho::class)->name('carrinho');

// Fechar venda
Route::middleware('auth')->get('/fechar-venda', FecharVenda::class)->name('fechar-venda');

// Relatório de vendas
Route::middleware('auth')->get('/relatorio-vendas', RelatorioVendas::class)->name('relatorio-vendas');

// Caixa e Sangria
Route::middleware('auth')->get('/caixa-sangria', \App\Livewire\CaixaSangria::class)->name('caixa-sangria');

Route::get('/usuarios', UsuariosCrud::class)->name('usuarios.index');

/*Route::middleware('auth')->prefix('usuarios')->name('usuarios.')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('/criar', [UserController::class, 'create'])->name('criar');
    Route::post('/', [UserController::class, 'store'])->name('store');
    Route::get('/editar/{id}', [UserController::class, 'edit'])->name('edit');
    Route::put('/{id}', [UserController::class, 'update'])->name('update');
    Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
});
*/
/* impressao de cupom */
Route::get('/imprimir-cupom/{venda_id}', [CupomController::class, 'imprimir'])->name('imprimir.cupom');

Route::get('/venda/{venda}/cupom', function (Venda $venda) {
    return view('impressao.cupom', ['vendaSelecionada' => $venda]);
})->name('impressao.cupom');

Route::middleware('auth')->get('/clientes', ClienteLista::class)->name('clientes');

Route::get('/cliente.conta', ClientesContas::class)->name('cliente.conta');

Route::get('/relatorio/exportar', [ExportarRelatorioController::class, 'exportar'])->name('relatorio.exportar');


Route::middleware('auth')->get('/mesa', Mesas::class)->name('mesas');

Route::get('/comanda/print/{id}', [ComandaController::class, 'print'])->name('comanda.print');

Route::get('/comanda/print/{venda}', function (\App\Models\Venda $venda) {
    return view('comandas.print', compact('venda'));
});

Route::get('/search', Search::class)->name('search');



Route::get('/relatorio-produtos', [ExportarProdutos::class, 'exportar'])->name('produtos.exportar');
