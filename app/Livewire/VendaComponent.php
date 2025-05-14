<?php

use Livewire\Component;


use App\Models\Produto;

class VendaComponent extends Component
{
    public $clienteId;
    public $produtos = [];
    
    protected $listeners = ['clienteSelecionado'];

    public function clienteSelecionado($id)
    {
        $this->clienteId = $id;

        // Carregar produtos com base no cliente
        $this->produtos = Produto::where('cliente_id', $id)->get(); // ou sua lógica
    }

    public function render()
    {
        return view('livewire.venda-component');
    }
}

