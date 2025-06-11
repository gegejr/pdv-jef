<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Cliente;
use App\Models\Reserva;
use Livewire\WithPagination;

class ReservaForm extends Component
{
    use WithPagination;
    public $clientes;
    //public $reservas;

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
        //$this->loadReservas();
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
}