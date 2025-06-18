<?php

// app/Livewire/Funcionarios.php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Carbon\Carbon;
use App\Models\Venda;
use App\Models\Servico;
class Funcionarios extends Component
{
    public $usuarios;

    public $usuarioId;
    public $name, $email, $role = 'user';
    public $comissionado = false, $comissao_venda, $comissao_servico;
    public $showModal = false;
    // Propriedades para comissão
    public $usuarioComissao;
    public $comissaoInicio;
    public $comissaoFim;
    public $comissaoVendas = 0;
    public $comissaoServicos = 0;
    public $totalComissao = 0;
    public $showComissaoModal = false;
    public $usuarioHistorico;
    public $historicoInicio;
    public $historicoFim;
    public $vendasRealizadas = [];
    public $servicosRealizados = [];
    public $showHistoricoModal = false;
    public function mount()
    {
        $this->usuarios = User::all();
    }

    public function abrirModalEdicao($id)
    {
        $usuario = User::findOrFail($id);

        $this->usuarioId = $usuario->id;
        $this->name = $usuario->name;
        $this->email = $usuario->email;
        $this->role = $usuario->role;
        $this->comissionado = $usuario->comissionado;
        $this->comissao_venda = $usuario->comissao_venda;
        $this->comissao_servico = $usuario->comissao_servico;

        $this->showModal = true;
    }

    public function salvarUsuario()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->usuarioId,
            'role' => 'required|in:admin,user',
            'comissao_venda' => 'nullable|numeric|min:0|max:100',
            'comissao_servico' => 'nullable|numeric|min:0|max:100',
        ]);

        $usuario = User::findOrFail($this->usuarioId);

        $usuario->update([
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'comissionado' => $this->comissionado,
            'comissao_venda' => $this->comissionado ? $this->comissao_venda : null,
            'comissao_servico' => $this->comissionado ? $this->comissao_servico : null,
        ]);

        $this->showModal = false;
        $this->reset(['usuarioId', 'name', 'email', 'role', 'comissionado', 'comissao_venda', 'comissao_servico']);

        $this->usuarios = User::all(); // Atualiza listagem
        session()->flash('message', 'Usuário atualizado com sucesso!');
    }
    
    public function verComissao($id)
    {
        $this->usuarioComissao = User::findOrFail($id);
        $this->comissaoInicio = now()->startOfMonth()->format('Y-m-d');
        $this->comissaoFim = now()->endOfMonth()->format('Y-m-d');
        $this->comissaoVendas = 0;
        $this->comissaoServicos = 0;
        $this->totalComissao = 0;
        $this->showComissaoModal = true;

        $this->calcularComissao(); // já calcula na abertura
    }

    public function updatedComissaoInicio()
    {
        $this->calcularComissao();
    }

    public function updatedComissaoFim()
    {
        $this->calcularComissao();
    }
    public function calcularComissao()
    {
        $inicio = Carbon::parse($this->comissaoInicio)->startOfDay();
        $fim = Carbon::parse($this->comissaoFim)->endOfDay();

        $usuario = $this->usuarioComissao;

        // VENDAS
        $vendas = Venda::where('user_id', $usuario->id)
            ->whereBetween('created_at', [$inicio, $fim])
            ->get();

        $this->comissaoVendas = $vendas->sum(function ($venda) use ($usuario) {
            return ($usuario->comissao_venda ?? 0) * ($venda->total - $venda->desconto_total) / 100;
        });

        // SERVIÇOS (exemplo, se existir)
        
        $servicos = Servico::where('user_id', $usuario->id)
            ->whereBetween('created_at', [$inicio, $fim])
            ->get();

        $this->comissaoServicos = $servicos->sum(function ($servico) use ($usuario) {
            return ($usuario->comissao_servico ?? 0) * $servico->valor / 100;
        });
        

        $this->totalComissao = $this->comissaoVendas + $this->comissaoServicos;
    }

    public function verHistorico($id)
    {
        $this->usuarioHistorico = User::findOrFail($id);
        $this->historicoInicio = now()->startOfMonth()->format('Y-m-d');
        $this->historicoFim = now()->endOfMonth()->format('Y-m-d');
        $this->vendasRealizadas = [];
        $this->servicosRealizados = [];
        $this->showHistoricoModal = true;

        $this->buscarMovimentacoes(); // já busca ao abrir
    }

    public function updatedHistoricoInicio()
    {
        $this->buscarMovimentacoes();
    }

    public function updatedHistoricoFim()
    {
        $this->buscarMovimentacoes();
    }

    public function updated($property)
    {
        if (in_array($property, ['comissaoInicio', 'comissaoFim'])) {
            $this->calcularComissao();
        }

        if (in_array($property, ['historicoInicio', 'historicoFim'])) {
            $this->buscarMovimentacoes();
        }
    }

    public function buscarMovimentacoes()
    {
        $inicio = Carbon::parse($this->historicoInicio)->startOfDay();
        $fim = Carbon::parse($this->historicoFim)->endOfDay();

        // VENDAS
        $this->vendasRealizadas = Venda::where('user_id', $this->usuarioHistorico->id)
        ->whereBetween('created_at', [$inicio, $fim])
        ->orderByDesc('created_at')
        ->take(10)
        ->get()
        ->toArray();

        // SERVIÇOS (se houver)
        
        $this->servicosRealizados = Servico::where('user_id', $this->usuarioHistorico->id)
            ->whereBetween('created_at', [$inicio, $fim])
            ->orderByDesc('created_at')
            ->get()
            ->toArray();
        
    }
    public function render()
    {
        return view('livewire.funcionarios');
    }
}
