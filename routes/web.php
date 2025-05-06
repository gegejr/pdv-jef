<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Livewire\Produto;
use App\Livewire\ProdutoForm;
use App\Livewire\ProdutoLista;
use App\Livewire\Carrinho;
use App\Livewire\RelatorioVendas;
use App\Livewire\FecharVenda;

// Página inicial
Route::get('/', function () {
    return view('welcome');
});

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

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

// Carrinho
Route::get('/carrinho', Carrinho::class)->name('carrinho');

// Fechar venda
Route::get('/fechar-venda', FecharVenda::class)->name('fechar-venda')->middleware('auth');

// Relatório de vendas
Route::get('/relatorio-vendas', RelatorioVendas::class)->name('relatorio-vendas')->middleware('auth');
