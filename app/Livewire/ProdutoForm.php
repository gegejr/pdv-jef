<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Produto;

class ProdutoForm extends Component
{
    use WithFileUploads;

    public $produto_id;
    public $nome, $codigo_barras, $descricao, $valor, $estoque, $desconto_padrao = 0, $imagem, $imagem_existente;

    protected $listeners = ['editarProduto'];

    public function editarProduto($id)
    {
        $produto = Produto::findOrFail($id);
        $this->produto_id = $produto->id;
        $this->nome = $produto->nome;
        $this->codigo_barras = $produto->codigo_barras;
        $this->descricao = $produto->descricao;
        $this->valor = $produto->valor;
        $this->estoque = $produto->estoque;
        $this->desconto_padrao = $produto->desconto_padrao;
        $this->imagem_existente = $produto->imagem;
    }

    public function salvar()
    {
        $this->validate([
            'nome' => 'required|string|max:255',
            'codigo_barras' => 'nullable|string|max:100',
            'valor' => 'required|numeric|min:0',
            'estoque' => 'required|integer|min:0',
            'imagem' => 'nullable|image|max:2048',
            'desconto_padrao' => 'nullable|numeric|min:0'
        ]);

        $caminhoImagem = $this->imagem 
            ? $this->imagem->store('produtos', 'public') 
            : $this->imagem_existente;

        if ($this->produto_id) {
            $produto = Produto::findOrFail($this->produto_id);
            $produto->update([
                'nome' => $this->nome,
                'codigo_barras' => $this->codigo_barras,
                'descricao' => $this->descricao,
                'valor' => $this->valor,
                'estoque' => $this->estoque,
                'imagem' => $caminhoImagem,
                'desconto_padrao' => $this->desconto_padrao ?? 0,
            ]);

            session()->flash('sucesso', 'Produto atualizado com sucesso!');
        } else {
            Produto::create([
                'nome' => $this->nome,
                'codigo_barras' => $this->codigo_barras,
                'descricao' => $this->descricao,
                'valor' => $this->valor,
                'estoque' => $this->estoque,
                'imagem' => $caminhoImagem,
                'desconto_padrao' => $this->desconto_padrao ?? 0,
            ]);

            session()->flash('sucesso', 'Produto cadastrado com sucesso!');
        }

        $this->reset(['nome', 'codigo_barras', 'descricao', 'valor', 'estoque', 'imagem', 'produto_id', 'imagem_existente', 'desconto_padrao']);
        $this->dispatch('produtoAtualizado'); // opcional para atualizar a lista
    }

    public function render()
    {
        return view('livewire.produto-form');
    }
}
