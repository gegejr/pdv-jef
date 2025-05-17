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
    public $nome;

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
            'nome' => $this->nome ?? 'Caixa Sem identificação',
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
        $caixa = Caixa::with('vendas.pagamentos')->find($this->caixa_id);

        if ($caixa) {
            $caixa->valor_final = $caixa->calcularValorFinal();
            $caixa->fechado_em = now();
            $caixa->save();

            $this->caixa = $caixa; // <-- ESSENCIAL PARA A VIEW ATUALIZAR

            session()->flash('message', 'Caixa fechado com sucesso!');
        } else {
            session()->flash('error', 'Caixa não encontrado!');
        }
        $this->js("setTimeout(() => window.location.href = '".route('caixa-sangria')."', 1000)");
    }

    // Método para registrar sangria
    public function registrarSangria()
    {
        $caixa = Caixa::find($this->caixa_id);

        if ($caixa) {
            $this->validate([
                //'nome_sangria' => 'required|string|max:255',
                'valor_sangria' => 'required|numeric|min:0.01',
                'observacao_sangria' => 'required|string|max:255',
            ]);

            Sangria::create([
                'caixa_id' => $this->caixa_id,
               // 'nome' => $this->nome_sangria,
                'valor' => $this->valor_sangria,
                'observacao' => $this->observacao_sangria,
            ]);

            $caixa->valor_inicial -= $this->valor_sangria;
            $caixa->save();

            // Limpar campos e atualizar dados
            //$this->nome_caixa,
            $this->valor_sangria = null;
            $this->observacao_sangria = null;
            $this->caixa = Caixa::with('sangrias')->find($this->caixa_id);

            session()->flash('message', 'Sangria registrada com sucesso!');
        } else {
            session()->flash('error', 'Caixa não encontrado!');
        }
    }

    // Carregar dados na inicialização
    public function mount()
    {
        if (!auth()->user()->hasValidSubscription()) {
            return redirect()->route('subscription.expired');
        }
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
