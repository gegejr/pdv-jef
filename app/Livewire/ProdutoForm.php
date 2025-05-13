<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Produto;
use App\Livewire\ProdutoLista;
use App\Models\ProdutoPerda;

class ProdutoForm extends Component
{
    use WithFileUploads;

    public $produto_id;
    public $produtoId;
    public $nome, $codigo_barras, $descricao, $valor, $estoque, $desconto_padrao = 0, $imagem, $imagem_existente;

    protected $listeners = ['editarProduto'];
    public $registrar_perda = false;
    public $quantidade_perda;
    public $motivo_perda;
    // Editar produto
    public function editarProduto($id)
    {
        $this->produtoId = $id;  // Guardar o ID do produto a ser editado
        $produto = Produto::findOrFail($id);
    
        // Preencher os campos do formulário com os dados do produto
        $this->nome = $produto->nome;
        $this->codigo_barras = $produto->codigo_barras;
        $this->descricao = $produto->descricao;
        $this->valor = $produto->valor;
        $this->estoque = $produto->estoque;
        $this->desconto_padrao = $produto->desconto_padrao;
        $this->imagem_existente = $produto->imagem;
    
        // Emitir o evento para abrir o modal
        $this->dispatch('openModal');
    }

    // Função de salvar (para cadastro e edição)
    public function salvar()
    {
        $this->validate([
            'nome' => 'required|string|max:255',
            'codigo_barras' => 'nullable|string|max:100',
            'valor' => 'required|numeric|min:0',
            'estoque' => 'required|integer|min:0',
            'imagem' => 'nullable|image|max:2048',
            'desconto_padrao' => 'nullable|numeric|min:0',
            'quantidade_perda' => 'nullable|integer|min:1',
            'motivo_perda' => 'nullable|in:quebra,descarte,perda',
        ]);

        // Verifica se foi enviado uma imagem nova
        $caminhoImagem = $this->imagem
            ? $this->imagem->store('produtos', 'public')
            : $this->imagem_existente;

        if ($this->produto_id) {
            // Edição de produto
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

            // Registra a perda, caso haja
            if ($this->registrar_perda && $this->quantidade_perda > 0) {
                ProdutoPerda::create([
                    'produto_id' => $produto->id,
                    'quantidade' => $this->quantidade_perda,
                    'valor' => $produto->valor * $this->quantidade_perda,
                    'motivo' => $this->motivo_perda,
                ]);

                // Atualiza o estoque
                $produto->decrement('estoque', $this->quantidade_perda);
            }

            session()->flash('sucesso', 'Produto atualizado com sucesso!');
        } else {
            // Cadastro de novo produto
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

        // Resetando os dados após a ação
        $this->reset(['nome', 'codigo_barras', 'descricao', 'valor', 'estoque', 'imagem', 'produto_id', 'imagem_existente', 'desconto_padrao', 'quantidade_perda', 'motivo_perda']);
        $this->dispatch('produtoAtualizado'); // opcional para atualizar a lista de produtos
    }


    // Função render para mostrar o formulário
    public function render()
    {
        return view('livewire.produto-form');
    }

    // Função para fechar o modal e resetar os dados
    public function fecharModal()
    {
        $this->reset(['produto_id', 'nome', 'codigo_barras', 'descricao', 'valor', 'estoque', 'imagem', 'imagem_existente', 'desconto_padrao']);
    }

    // Função chamada ao iniciar o componente, caso passe um id, edita o produto
    public function mount($id = null)
    {
        if ($id) {
            $this->editarProduto($id);
        }
    }
}
