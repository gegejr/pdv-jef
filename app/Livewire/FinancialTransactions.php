<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\FinancialTransaction;

class FinancialTransactions extends Component
{
    public $descricao, $tipo = 'pagar', $valor, $data_vencimento, $categoria;
    public $filtro = 'todos';

    public function rules()
    {
        return [
            'descricao' => 'required|string|max:255',
            'tipo' => 'required|in:pagar,receber',
            'valor' => 'required|numeric|min:0.01',
            'data_vencimento' => 'required|date',
            'categoria' => 'nullable|string|max:100',
        ];
    }

    public function resetInput()
    {
        $this->descricao = '';
        $this->tipo = 'pagar';
        $this->valor = '';
        $this->data_vencimento = '';
        $this->categoria = '';
    }

    public function salvar()
    {
        $this->validate();

        FinancialTransaction::create([
            'descricao' => $this->descricao,
            'tipo' => $this->tipo,
            'valor' => $this->valor,
            'data_vencimento' => $this->data_vencimento,
            'categoria' => $this->categoria,
            'pago' => false,
        ]);

        session()->flash('message', 'Lançamento criado com sucesso!');

        $this->resetInput();
    }

    public function marcarComoPago($id)
    {
        $transacao = FinancialTransaction::find($id);
        if ($transacao && !$transacao->pago) {
            $transacao->update([
                'pago' => true,
                'data_pagamento' => now(),
            ]);
            session()->flash('message', "Lançamento marcado como pago.");
        }
    }

    public function render()
    {
        $query = FinancialTransaction::query();

        if ($this->filtro === 'pagar') {
            $query->where('tipo', 'pagar')->where('pago', false);
        } elseif ($this->filtro === 'receber') {
            $query->where('tipo', 'receber')->where('pago', false);
        }

        $transactions = $query->orderBy('data_vencimento', 'asc')->get();

        return view('livewire.financial-transactions', [
            'transactions' => $transactions,
        ]);
    }
}