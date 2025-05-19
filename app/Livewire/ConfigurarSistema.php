<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SistemaConfiguracao;

class ConfigurarSistema extends Component
{
    public $mostrarConfiguracoes = false;
    public $configuracoes = [];

    protected $listeners = ['abrirConfiguracoes' => 'mostrarModal'];

    public function mount()
    {
        if (!auth()->user()->hasValidSubscription()) {
            return redirect()->route('subscription.expired');
        }
    }

    public function mostrarModal()
    {
        $this->mostrarConfiguracoes = true;
        $this->carregarConfiguracoes();
    }

    public function carregarConfiguracoes()
    {
        $this->configuracoes = SistemaConfiguracao::all()->groupBy('categoria')->toArray();
    }

    public function atualizarConfiguracao($id, $valor)
    {
        $config = SistemaConfiguracao::find($id);
        if ($config) {
            $config->ativo = $valor;
            $config->save();
            $this->carregarConfiguracoes();
        }
    }

    public function render()
    {
        return view('livewire.configurar-sistema');
    }
}
