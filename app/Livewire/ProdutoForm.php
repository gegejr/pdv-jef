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
    public $ncm;
    public $valor;
    public $estoque;
    public $unidade_medida = 'un';
    public $desconto_padrao = 0;    
    public $cst_icms;
    public $icms_rate;
    public $cst_ipi;
    public $ipi_rate;
    public $cst_pis;
    public $pis_rate;
    public $cst_cofins;
    public $cofins_rate;
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
        $this->ncm              = $produto->ncm;
        $this->valor            = $produto->valor;
        $this->estoque          = $produto->estoque;
        $this->unidade_medida   = $produto->unidade_medida;
        $this->desconto_padrao  = $produto->desconto_padrao;
        $this->categoria_id     = $produto->categoria_id;
        $this->status           = $produto->status;
        $this->imagem_existente = $produto->imagem;
        $this->cst_icms     = $produto->cst_icms;
        $this->icms_rate    = $produto->icms_rate;
        $this->cst_ipi      = $produto->cst_ipi;
        $this->ipi_rate     = $produto->ipi_rate;
        $this->cst_pis      = $produto->cst_pis;
        $this->pis_rate     = $produto->pis_rate;
        $this->cst_cofins   = $produto->cst_cofins;
        $this->cofins_rate  = $produto->cofins_rate;

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
            'ncm'             => $this->ncm,
            'unidade_medida'  => $this->unidade_medida,
            'imagem'          => $caminhoImagem,
            'desconto_padrao' => $this->desconto_padrao ?: 0,
            'categoria_id'    => $this->categoria_id,
            'status'          => $this->status,
            'cst_icms'    => $this->cst_icms,
            'icms_rate'   => $this->icms_rate,
            'cst_ipi'     => $this->cst_ipi,
            'ipi_rate'    => $this->ipi_rate,
            'cst_pis'     => $this->cst_pis,
            'pis_rate'    => $this->pis_rate,
            'cst_cofins'  => $this->cst_cofins,
            'cofins_rate' => $this->cofins_rate,
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
            'ncm'             => 'required|string|max:255',
            'categoria_id'    => 'required|exists:categorias,id',
            'status'          => 'required|in:ativo,inativo',
            'imagem'          => 'nullable|image|max:2048',
            'desconto_padrao' => 'nullable|numeric|min:0',
            'quantidade_perda'=> 'nullable|integer|min:1',
            'motivo_perda'    => 'nullable|in:quebra,descarte,perda',
            'cst_icms'    => 'nullable|string|max:3',
            'icms_rate'   => 'nullable|numeric|min:0',
            'cst_ipi'     => 'nullable|string|max:3',
            'ipi_rate'    => 'nullable|numeric|min:0',
            'cst_pis'     => 'nullable|string|max:3',
            'pis_rate'    => 'nullable|numeric|min:0',
            'cst_cofins'  => 'nullable|string|max:3',
            'cofins_rate' => 'nullable|numeric|min:0',
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
            'ncm',
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
