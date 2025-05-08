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

// Página inicial
Route::get('/', function () {
    /** @var \Illuminate\Contracts\Auth\Guard $auth */
    $auth = auth();

    if ($auth->check()) {
        return redirect()->route('painel');
    }

    return view('auth.login');
})->name('login'); // Define nome para rota de login

// Painel principal (dashboard)
Route::get('/painel', function (Request $request) {
    $inicio = $request->input('inicio');
    $fim = $request->input('fim');

    $query = Venda::query();

    if ($inicio && $fim) {
        $query->whereBetween('created_at', ["$inicio 00:00:00", "$fim 23:59:59"]);
    }

    $totalVendas = $query->sum('total');
    $numeroVendas = $query->count();

    $ultimasVendas = $query->orderBy('created_at', 'desc')->limit(5)->get();

    $vendasPorData = Venda::selectRaw('DATE(created_at) as data, SUM(total) as total')
        ->when($inicio && $fim, fn($q) => $q->whereBetween('created_at', ["$inicio 00:00:00", "$fim 23:59:59"]))
        ->groupBy('data')
        ->orderBy('data')
        ->get();

    $datas = $vendasPorData->pluck('data');
    $valores = $vendasPorData->pluck('total');

    return view('painel', compact('totalVendas', 'numeroVendas', 'inicio', 'fim', 'ultimasVendas', 'datas', 'valores'));
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
});

// Carrinho
Route::middleware('auth')->get('/carrinho', Carrinho::class)->name('carrinho');

// Fechar venda
Route::middleware('auth')->get('/fechar-venda', FecharVenda::class)->name('fechar-venda');

// Relatório de vendas
Route::middleware('auth')->get('/relatorio-vendas', RelatorioVendas::class)->name('relatorio-vendas');

// Caixa e Sangria
Route::middleware('auth')->get('/caixa-sangria', \App\Livewire\CaixaSangria::class)->name('caixa-sangria');


