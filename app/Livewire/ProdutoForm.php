<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Produto;
use App\Models\Categoria;
use App\Models\ProdutoPerda;

class ProdutoForm extends Component
{
    use WithFileUploads;

    // --- Propriedades públicas do formulário ---
    public $produto_id;      // usado para update
    public $produtoId;       // compatibilidade com o emit anterior

    public $nome;
    public $codigo_barras;
    public $sku;
    public $descricao;
    public $valor;
    public $estoque;
    public $unidade_medida = 'un';
    public $desconto_padrao = 0;

    public $categoria_id;
    public $status = 'ativo';

    public $imagem;
    public $imagem_existente;

    // Listas auxiliares
    public array $categorias = [];

    // Perdas
    public $registrar_perda = false;
    public $quantidade_perda;
    public $motivo_perda;

    protected $listeners = ['editarProduto'];

    /* ----------------------------------- */
    /*  Métodos de ciclo de vida           */
    /* ----------------------------------- */
    public function mount($id = null)
    {
        // Verificação de assinatura (conforme seu fluxo)
        if (!auth()->user()->hasValidSubscription()) {
            return redirect()->route('subscription.expired');
        }

        // Carrega categorias 1× (evita consulta a cada render)
        $this->categorias = Categoria::orderBy('nome')->get()->toArray();

        if ($id) {
            $this->editarProduto($id);
        }
    }

    public function render()
    {
        return view('livewire.produto-form', [
            'categorias' => $this->categorias,
        ]);
    }

    /* ----------------------------------- */
    /*  CRUD                               */
    /* ----------------------------------- */
    public function editarProduto($id)
    {
        $this->produtoId = $id;
        $produto = Produto::findOrFail($id);

        // Preenche as propriedades
        $this->produto_id       = $produto->id;
        $this->nome             = $produto->nome;
        $this->codigo_barras    = $produto->codigo_barras;
        $this->sku              = $produto->sku;
        $this->descricao        = $produto->descricao;
        $this->valor            = $produto->valor;
        $this->estoque          = $produto->estoque;
        $this->unidade_medida   = $produto->unidade_medida;
        $this->desconto_padrao  = $produto->desconto_padrao;
        $this->categoria_id     = $produto->categoria_id;
        $this->status           = $produto->status;
        $this->imagem_existente = $produto->imagem;

        $this->dispatch('openModal');
    }

    public function salvar()
    {
        $this->validate($this->rules());

        // Upload (se existir imagem nova)
        $caminhoImagem = $this->imagem ?
            $this->imagem->store('produtos', 'public') :
            $this->imagem_existente;

        $dados = [
            'nome'            => $this->nome,
            'codigo_barras'   => $this->codigo_barras,
            'sku'             => $this->sku,
            'descricao'       => $this->descricao,
            'valor'           => $this->valor,
            'estoque'         => $this->estoque,
            'unidade_medida'  => $this->unidade_medida,
            'imagem'          => $caminhoImagem,
            'desconto_padrao' => $this->desconto_padrao ?: 0,
            'categoria_id'    => $this->categoria_id,
            'status'          => $this->status,
        ];

        if ($this->produto_id) {
            $produto = Produto::findOrFail($this->produto_id);
            $produto->update($dados);

            // Perda de estoque (opcional)
            if ($this->registrar_perda && $this->quantidade_perda > 0) {
                ProdutoPerda::create([
                    'produto_id' => $produto->id,
                    'quantidade' => $this->quantidade_perda,
                    'valor'      => $produto->valor * $this->quantidade_perda,
                    'motivo'     => $this->motivo_perda,
                ]);

                $produto->decrement('estoque', $this->quantidade_perda);
            }

            session()->flash('sucesso', 'Produto atualizado com sucesso!');
        } else {
            Produto::create($dados);
            session()->flash('sucesso', 'Produto cadastrado com sucesso!');
        }

        $this->resetForm();
        $this->dispatch('produtoAtualizado');
    }

    /* ----------------------------------- */
    /*  Utilitários                        */
    /* ----------------------------------- */
    protected function rules()
    {
        return [
            'nome'            => 'required|string|max:255',
            'codigo_barras'   => 'nullable|string|max:100',
            'sku'             => 'nullable|string|max:100|unique:produtos,sku,' . $this->produto_id,
            'valor'           => 'required|numeric|min:0',
            'estoque'         => 'required|integer|min:0',
            'unidade_medida'  => 'required|in:un,kg,l,cx',
            'categoria_id'    => 'required|exists:categorias,id',
            'status'          => 'required|in:ativo,inativo',
            'imagem'          => 'nullable|image|max:2048',
            'desconto_padrao' => 'nullable|numeric|min:0',
            'quantidade_perda'=> 'nullable|integer|min:1',
            'motivo_perda'    => 'nullable|in:quebra,descarte,perda',
        ];
    }

    protected function resetForm()
    {
        $this->reset([
            'produto_id',
            'produtoId',
            'nome',
            'codigo_barras',
            'sku',
            'descricao',
            'valor',
            'estoque',
            'unidade_medida',
            'imagem',
            'imagem_existente',
            'desconto_padrao',
            'categoria_id',
            'status',
            'quantidade_perda',
            'motivo_perda',
            'registrar_perda',
        ]);
    }

    public function fecharModal()
    {
        $this->resetForm();
    }
}
