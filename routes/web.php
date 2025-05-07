<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Livewire\Produto;
use App\Livewire\ProdutoForm;
use App\Livewire\ProdutoLista;
use App\Livewire\Carrinho;
use App\Livewire\RelatorioVendas;
use App\Livewire\FecharVenda;
use App\Models\Venda;
use Illuminate\Http\Request;

// Página inicial
Route::get('/', function () {
    return view('welcome');
});

Route::get('/painel', function (Request $request) {
    $inicio = $request->input('inicio');
    $fim = $request->input('fim');

    $query = Venda::query();

    if ($inicio && $fim) {
        $query->whereBetween('created_at', [$inicio . ' 00:00:00', $fim . ' 23:59:59']);
    }

    $totalVendas = $query->sum('total');
    $numeroVendas = $query->count();

    // Últimas Vendas
    $ultimasVendas = $query->orderBy('created_at', 'desc')->limit(5)->get();

    // Dados para o gráfico
    // Dados para o gráfico (use nova query)
    $vendasPorData = Venda::selectRaw('DATE(created_at) as data, SUM(total) as total')
    ->when($inicio && $fim, function ($q) use ($inicio, $fim) {
        $q->whereBetween('created_at', [$inicio . ' 00:00:00', $fim . ' 23:59:59']);
    })
    ->groupBy('data')
    ->orderBy('data')
    ->get();

    $datas = $vendasPorData->pluck('data');
    $valores = $vendasPorData->pluck('total');

    return view('painel', compact('totalVendas', 'numeroVendas', 'inicio', 'fim', 'ultimasVendas', 'datas', 'valores'));
})->middleware(['auth'])->name('painel');

// Perfil do usuário
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Autenticação
require __DIR__.'/auth.php';

// Produtos
Route::get('/produtos', ProdutoForm::class)->name('produtos');
Route::get('/produtos/lista', ProdutoLista::class)->name('produtos.lista');
Route::get('/produtos/adicionar', ProdutoForm::class)->name('adicionar-produto');  // Rota para Adicionar Produto

// Carrinho
Route::get('/carrinho', Carrinho::class)->name('carrinho');

// Fechar venda
Route::get('/fechar-venda', FecharVenda::class)->name('fechar-venda')->middleware('auth');

// Relatório de vendas
Route::get('/relatorio-vendas', RelatorioVendas::class)->name('relatorio-vendas')->middleware('auth');
