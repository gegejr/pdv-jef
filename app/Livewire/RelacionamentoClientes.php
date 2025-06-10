<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\RelacionamentoCliente;

class RelacionamentoClientes extends Component
{
    public $clientesRelacionamento = [];
    protected $listeners = ['clienteAtualizado' => 'carregarClientes'];
    public function mount()
    {
        $this->carregarClientes();
    }

    public function carregarClientes()
    {
        $this->clientesRelacionamento = RelacionamentoCliente::with('cliente')->latest()->get();
    }

    public function render()
    {
        return view('livewire.relacionamento-clientes');
    }

    public function enviarMensagem($clienteId)
    {
        $relacionamento = RelacionamentoCliente::with('cliente')->find($clienteId);

        if (!$relacionamento || !$relacionamento->tem_whatsapp) {
            return;
        }

        // Aqui você pode usar a API (ex: Z-API)
        \Illuminate\Support\Facades\Http::post('https://api.z-api.io/instances/3E28372FE24150EA84892A9B2850D0B6/token/3D1D1A89F97F705C7092ABC6/phone-exists', [
            'phone' => preg_replace('/\D/', '', $relacionamento->cliente->telefone),
            'message' => "Olá {$relacionamento->cliente->nome}, tudo bem? 👋 Estamos com novidades para você!",
        ]);

        $relacionamento->update([
            'ultima_interacao' => now(),
        ]);

        $this->carregarClientes();
    }
}