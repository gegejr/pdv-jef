<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Caixa;
use App\Models\Sangria;

class CaixaSangria extends Component
{
    public $valor_inicial = 0;
    public $caixa_id;
    public $valor_sangria;
    public $observacao_sangria;
    public $caixa;

    // Método para abrir o caixa
    public function index()
    {
        $caixa = Caixa::whereNull('fechado_em')->first(); // ou qualquer lógica para obter o caixa
        return view('painel', compact('caixa')); // Passando a variável para a view
    }

    public function abrirCaixa()
    {
        $user_id = auth()->id();

        // Verifica se já há um caixa aberto
        $caixaExistente = Caixa::whereNull('fechado_em')->first();

        if ($caixaExistente) {
            session()->flash('error', 'Já existe um caixa aberto!');
            return;
        }

        $caixa = Caixa::create([
            'user_id' => $user_id,
            'valor_inicial' => $this->valor_inicial,
            'aberto_em' => now(),
        ]);

        $this->caixa_id = $caixa->id;
        $this->caixa = $caixa;

        session()->flash('message', 'Caixa aberto com sucesso!');
    }

    // Método para fechar o caixa
    public function fecharCaixa()
    {
        $caixa = Caixa::find($this->caixa_id);

        if ($caixa) {
            $caixa->valor_final = $caixa->calcularValorFinal(); // Certifique-se de que esse método exista no modelo Caixa
            $caixa->fechado_em = now();
            $caixa->save();

            session()->flash('message', 'Caixa fechado com sucesso!');
        } else {
            session()->flash('error', 'Caixa não encontrado!');
        }
    }

    // Método para registrar sangria
    public function registrarSangria()
    {
        $caixa = Caixa::find($this->caixa_id);

        if ($caixa) {
            // Valida a entrada de sangria
            $this->validate([
                'valor_sangria' => 'required|numeric|min:0.01',
                'observacao_sangria' => 'required|string|max:255',
            ]);

            Sangria::create([
                'caixa_id' => $this->caixa_id,
                'valor' => $this->valor_sangria,
                'observacao' => $this->observacao_sangria,
            ]);

            // Atualiza o valor do caixa
            $caixa->valor_inicial -= $this->valor_sangria;
            $caixa->save();

            session()->flash('message', 'Sangria registrada com sucesso!');
        } else {
            session()->flash('error', 'Caixa não encontrado!');
        }
    }

    // Carregar dados na inicialização
    public function mount()
    {
        $this->caixa = Caixa::whereNull('fechado_em')->first();

        if ($this->caixa) {
            $this->caixa_id = $this->caixa->id;
        }
    }

    // Passar os dados do caixa para a view
    public function render()
    {
        return view('livewire.caixa-sangria', ['caixa' => $this->caixa]);
    }
}
