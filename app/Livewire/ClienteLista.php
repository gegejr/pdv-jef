<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Cliente;

class ClienteLista extends Component
{
    public $nome, $cpf_cnpj, $data_nascimento, $endereco, $telefone;
    public $clientes;
    public $clienteSelecionadoId = null;
    public $modalAberto = false;
    public $modoVisualizacao = false;
    public $modoEdicao = false;

    
    protected $rules = [
        'nome' => 'required|string|max:255',
        'cpf_cnpj' => 'required|string|max:20|unique:clientes,cpf_cnpj',
        'data_nascimento' => 'nullable|date',
        'endereco' => 'nullable|string',
        'telefone' => 'nullable|string',
    ];

    public function mount()
    {
        $this->carregarClientes();
        $this->clientes = Cliente::all();

    }

    public function carregarClientes()
    {
        $this->clientes = Cliente::all();
    }

    public function salvar()
    {
        $this->validate();

        Cliente::create([
            'nome' => $this->nome,
            'cpf_cnpj' => $this->cpf_cnpj,
            'data_nascimento' => $this->data_nascimento,
            'endereco' => $this->endereco,
            'telefone' => $this->telefone,
        ]);

        $this->resetCampos();
        $this->carregarClientes();
    }

    public function resetCampos()
    {
        $this->nome = $this->cpf_cnpj = $this->data_nascimento = $this->endereco = $this->telefone = '';
        $this->clienteSelecionadoId = null;
        $this->modalAberto = false;
        $this->modoVisualizacao = false;
        $this->modoEdicao = false;
    }

    public function render()
    {
        return view('livewire.cliente-lista');
    }

        public function editar($id)
    {
        $cliente = Cliente::findOrFail($id);

        $this->clienteSelecionadoId = $cliente->id;
        $this->nome = $cliente->nome;
        $this->cpf_cnpj = $cliente->cpf_cnpj;
        $this->data_nascimento = $cliente->data_nascimento?->format('Y-m-d');
        $this->endereco = $cliente->endereco;
        $this->telefone = $cliente->telefone;
        $this->modoEdicao = true;
        $this->modalAberto = true;

        // Atualiza regra para ignorar o cpf_cnpj único do próprio cliente
        $this->rules['cpf_cnpj'] = 'required|string|max:20|unique:clientes,cpf_cnpj,' . $cliente->id;
    }

    public function atualizar()
    {
        $this->validate();

        $cliente = Cliente::findOrFail($this->clienteSelecionadoId);

        $cliente->update([
            'nome' => $this->nome,
            'cpf_cnpj' => $this->cpf_cnpj,
            'data_nascimento' => $this->data_nascimento,
            'endereco' => $this->endereco,
            'telefone' => $this->telefone,
        ]);

        $this->resetCampos();
        $this->carregarClientes();
    }

    public function excluir($id)
    {
        Cliente::destroy($id);
        $this->carregarClientes();
    }

    public function ver($id)
    {
        $cliente = Cliente::findOrFail($id);

        $this->clienteSelecionadoId = $cliente->id;
        $this->nome = $cliente->nome;
        $this->cpf_cnpj = $cliente->cpf_cnpj;
        $this->data_nascimento = $cliente->data_nascimento?->format('Y-m-d');
        $this->endereco = $cliente->endereco;
        $this->telefone = $cliente->telefone;
        $this->preencherDados($cliente);
        $this->modoVisualizacao = true;
        $this->modalAberto = true;
    }

    private function preencherDados($cliente)
    {
        $this->clienteSelecionadoId = $cliente->id;
        $this->nome = $cliente->nome;
        $this->cpf_cnpj = $cliente->cpf_cnpj;
        $this->data_nascimento = optional($cliente->data_nascimento)->format('Y-m-d');
        $this->endereco = $cliente->endereco;
        $this->telefone = $cliente->telefone;
    }


    public function selecionarCliente($id)
    {
        $this->clienteSelecionadoId = $id;

        // Emitindo evento com ID do cliente
        $this->dispatch('clienteSelecionado', id: $id);
    }

    
}
