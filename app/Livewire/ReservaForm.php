<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Cliente;
use App\Models\Reserva;
use Livewire\WithPagination;
//incluir outro model no livewire
use App\Models\Servico;
use Illuminate\Support\Facades\DB;
class ReservaForm extends Component
{
    use WithPagination;
    public $clientes;
    //public $reservas;
    //incluir váriavel de acordo com outro model no livewire
    public $servicos;
    public $cliente_id;
    public $servico;
    public $data;
    public $hora_inicio;
    public $hora_fim;
    public $observacoes;
    public $filtroData;
    public $filtroCliente;
    public $showModal = false;
    public $reservaId;
    public $showPagamentoModal = false; //Váriavel para abrir modal de pagamentos
    public $reservaSelecionadaId = null;
    public $metodoPagamentoSelecionado = null;
    public $metodosPagamento = ['dinheiro', 'debito', 'crédito', 'pix']; //métodos pagamentos

    protected $rules = [
        'cliente_id' => 'required|exists:clientes,id',
        'servico' => 'nullable|string|max:255',
        'data' => 'required|date',
        'hora_inicio' => 'required',
        'hora_fim' => 'required|after:hora_inicio',
        'observacoes' => 'nullable|string|max:500',
    ];

    public function mount()
    {
        $this->clientes = Cliente::orderBy('nome')->get();
        $this->servicos = Servico::orderBy('nome')->get(); // carrega os serviços de outro model
    }

    

    public function abrirModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function fecharModal()
    {
        $this->showModal = false;
    }

    public function resetForm()
    {
        $this->reset([
            'cliente_id', 'servico', 'data', 'hora_inicio', 'hora_fim', 'observacoes'
        ]);
    }

    public function salvar()
    {
        $this->validate();

        $conflito = Reserva::where('data', $this->data)
            ->where(function ($q) {
                $q->whereBetween('hora_inicio', [$this->hora_inicio, $this->hora_fim])
                    ->orWhereBetween('hora_fim', [$this->hora_inicio, $this->hora_fim]);
            })->exists();

        if ($conflito) {
            $this->addError('hora_inicio', 'Já existe uma reserva nesse horário.');
            return;
        }

        Reserva::create([
            'cliente_id' => $this->cliente_id,
            'servico' => $this->servico,
            'data' => $this->data,
            'hora_inicio' => $this->hora_inicio,
            'hora_fim' => $this->hora_fim,
            'status' => 'pendente',
            'observacoes' => $this->observacoes,
        ]);

        session()->flash('message', 'Reserva criada com sucesso!');
        $this->fecharModal();
        //$this->loadReservas(); // atualiza lista
    }

    public function render()
    {
        return view('livewire.reserva-form', [
            'reservas' => $this->reservas, // isso chama o "getReservasProperty"
        ]);
    }

    public function getReservasProperty()
    {
        return Reserva::query()
            ->when($this->filtroData, fn($q) => $q->whereDate('data', $this->filtroData))
            ->when($this->filtroCliente, fn($q) => $q->whereHas('cliente', fn($q2) =>
                $q2->where('nome', 'like', '%' . $this->filtroCliente . '%')))
            ->latest()
            ->paginate(10);
    }
        
    public function editar($id)
    {
        $this->reservaId = $id;
        $this->abrirModal(); // carrega os dados no formulário
    }

    public function cancelar($id)
    {
        Reserva::findOrFail($id)->update(['status' => 'cancelada']);
        session()->flash('message', 'Reserva cancelada com sucesso.');
    }

    public function updatingFiltroData()
    {
        $this->resetPage();
    }

    public function updatingFiltroCliente()
    {
        $this->resetPage();
    }

    public function concluir($id)
    {
        $reserva = Reserva::findOrFail($id);

        if ($reserva->status === 'pendente') {
            $reserva->update(['status' => 'concluida']);
            session()->flash('message', 'Reserva marcada como concluída.');
        }
    }

    public function finalizarServico()
{
    $reservaId = $this->reservaSelecionadaId;
    $metodoPagamento = $this->metodoPagamentoSelecionado;

    if (!$metodoPagamento) {
        session()->flash('error', 'Selecione um método de pagamento.');
        return;
    }

    $user_id = auth()->id();
    $reserva = Reserva::findOrFail($reservaId);

    if ($reserva->status !== 'pendente') {
        session()->flash('error', 'Essa reserva já foi finalizada ou cancelada.');
        $this->showPagamentoModal = false;
        return;
    }

    $servico = \App\Models\Servico::where('nome', $reserva->servico)->first();
    $valorServico = $servico?->valor ?? 0;

    if ($valorServico <= 0) {
        session()->flash('error', 'Valor do serviço não encontrado ou inválido.');
        $this->showPagamentoModal = false;
        return;
    }

    try {
        DB::beginTransaction();

        $caixa = \App\Models\Caixa::firstOrCreate(
            ['user_id' => $user_id, 'fechado_em' => null],
            ['nome' => 'Caixa', 'valor_inicial' => 0, 'aberto_em' => now()]
        );

        $venda = \App\Models\Venda::create([
            'user_id'         => $user_id,
            'caixa_id'        => $caixa->id,
            'cliente_id'      => $reserva->cliente_id,
            'total'           => $valorServico,
            'desconto_total'  => 0,
            'metodo_pagamento'=> $metodoPagamento,
        ]);

        \App\Models\Pagamento::create([
            'venda_id' => $venda->id,
            'tipo'     => $metodoPagamento,
            'valor'    => $valorServico,
        ]);

        \App\Models\FinancialTransaction::create([
            'descricao'       => 'Recebimento de serviço: ' . $reserva->servico,
            'tipo'            => 'receber',
            'valor'           => $valorServico,
            'data_vencimento' => now(),
            'data_pagamento'  => now(),
            'pago'            => true,
            'categoria'       => 'Serviço',
            'cliente_id'      => $reserva->cliente_id,
        ]);

        $reserva->update(['status' => 'concluida']);

        DB::commit();

        session()->flash('message', 'Serviço finalizado e venda registrada com sucesso!');
    } catch (\Throwable $e) {
        DB::rollBack();
        session()->flash('error', 'Erro ao finalizar serviço: ' . $e->getMessage());
    }

    $this->showPagamentoModal = false;
}


    public function abrirModalPagamento($reservaId)
    {  
        $this->reservaSelecionadaId = $reservaId;
        $this->metodoPagamentoSelecionado = null;
        $this->showPagamentoModal = true;
    }
}