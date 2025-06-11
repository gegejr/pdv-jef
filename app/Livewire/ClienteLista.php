<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Cliente;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Http;
use App\Models\RelacionamentoCliente;
use Illuminate\Support\Facades\Log;

class ClienteLista extends Component
{
    use WithPagination;

    public $nome, $cpf_cnpj, $data_nascimento, $telefone,
    $numero, $bairro, $cidade, $tipo_pessoa, $cep, $complemento, $ie, $im, $uf, $email
    , $nome_fantasia, $razao_social, $cnaes, $codigo_ibge, $endereco;
    public $clientes;
    public $clienteSelecionadoId = null;
    public $modalAberto = false;
    public $modoVisualizacao = false;
    public $modoEdicao = false;
    public $mensagemErro = '';
    public $cnae_id; // valor selecionado
    public $temWhatsapp;
    protected function rules()
    {
        return [
            'nome' => 'required|string|max:255',
            'cpf_cnpj' => 'required|string|max:20|unique:clientes,cpf_cnpj,' . $this->clienteSelecionadoId,
            'data_nascimento' => 'nullable|date',
            'telefone' => 'nullable|string',
            'email' => 'nullable|email',
            'cep' => 'nullable|string|max:10',
            'numero' => 'nullable|string|max:10',
            'complemento' => 'nullable|string|max:50',
            'bairro' => 'nullable|string|max:50',
            'cidade' => 'nullable|string|max:50',
            'uf' => 'nullable|string|max:2',
            'codigo_ibge' => 'nullable|regex:/^\d{7}$/',
            'tipo_pessoa' => 'required|in:fisica,juridica',
            'razao_social' => 'nullable|string|max:255',
            'nome_fantasia' => 'nullable|string|max:255',
            'cnae_id' => 'nullable|exists:cnaes,id',
            'ie' => 'nullable|string|max:50',
            'im' => 'nullable|string|max:50',
        ];
    }

    public function mount()
    {
        if (!auth()->user()->hasValidSubscription()) {
            return redirect()->route('subscription.expired');
        }
        $this->carregarClientes();
        $this->clientes = Cliente::all();
        $this->cnaes = \App\Models\Cnae::orderBy('codigo')->get();

    }

    public function carregarClientes()
    {
        $this->clientes = Cliente::all();
    }



    public function salvar()
    {
        try {
            $this->validate($this->regrasValidacao());

            // 1. Criar cliente
            $cliente = Cliente::create([
                'tipo_pessoa'     => $this->tipo_pessoa,
                'nome'            => $this->nome,
                'razao_social'    => $this->razao_social,
                'nome_fantasia'   => $this->nome_fantasia,
                'cnae_id'         => $this->cnae_id,
                'cpf_cnpj'        => $this->cpf_cnpj,
                'data_nascimento' => $this->data_nascimento,
                'telefone'        => $this->telefone,
                'email'           => $this->email,
                'cep'             => $this->cep,
                'numero'          => $this->numero,
                'bairro'          => $this->bairro,
                'cidade'          => $this->cidade,
                'uf'              => $this->uf,
                'complemento'     => $this->complemento,
                'codigo_ibge'     => $this->codigo_ibge,
                'ie'              => $this->ie,
                'im'              => $this->im,
            ]);

            // 2. Verificar se tem WhatsApp
           $telefoneLimpo = preg_replace('/\D/', '', $cliente->telefone);

            $response = Http::withHeaders([
                'Client-Token' => 'F72e79ed4e6244e699f85d5186dda9e0cS',
            ])->get("https://api.z-api.io/instances/3E28372FE24150EA84892A9B2850D0B6/token/58283665433F6AC13F7C9AED/phone-exists/{$telefoneLimpo}");

            $temWhatsapp = false;
            if ($response->ok()) {
                $data = $response->json();
                Log::info('Resposta da API: ' . print_r($data, true));
                $temWhatsapp = $data['exists'] ?? false;
            }
           // dd($temWhatsapp); // <- isso deve ser true
            RelacionamentoCliente::create([
                'cliente_id' => $cliente->id,
                'tem_whatsapp' => $temWhatsapp,
            ]);

            // 4. Se os componentes estiverem na mesma página
            $this->dispatch('clienteAtualizado');

            // 5. Limpar os campos
            $this->resetCampos();

            // 6. Opcional: redirecionar, se for outra página
            // return redirect()->route('relacionamento-clientes.index');

        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->mensagemErro = json_encode($e->errors());
        } catch (\Exception $e) {
            $this->mensagemErro = 'Erro ao salvar: ' . $e->getMessage();
        }
    }



    protected function regrasValidacao()
    {
        $regras = [
            'tipo_pessoa' => 'required|in:fisica,juridica',
            'nome' => 'required|string|max:255',
            'cpf_cnpj' => 'required|string',
            'data_nascimento' => 'nullable|date',
            'telefone' => 'required|string',
            'email' => 'required|email',
            'cep' => 'required|string',
            'numero' => 'required|string',
            'bairro' => 'required|string',
            'cidade' => 'required|string',
            'uf' => 'required|string|size:2',
            'complemento' => 'nullable|string',
            'codigo_ibge' => 'nullable|regex:/^\d{7}$/',
        ];

        if ($this->tipo_pessoa === 'pj') {
            $regras = array_merge($regras, [
                'razao_social' => 'required|string',
                'nome_fantasia' => 'required|string',
                'cnae_id' => 'required|integer|exists:cnaes,id',
                'ie' => 'nullable|string',
                'im' => 'nullable|string',
            ]);
        }

        return $regras;
    }


    public function resetCampos()
    {
        $this->nome = $this->cpf_cnpj = $this->data_nascimento  = $this->telefone = $this->cep = 
        $this->endereco = $this->bairro = $this->cidade = $this->uf = $this->codigo_ibge = 
        $this->complemento = $this->tipo_pessoa = '';
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
        $this->tipo_pessoa = $cliente->tipo_pessoa;
        $this->nome = $cliente->nome;
        $this->razao_social = $cliente->razao_social;
        $this->nome_fantasia = $cliente->nome_fantasia;
        $this->cnae_id = $cliente->cnae_id ?? null;
        $this->cpf_cnpj = $cliente->cpf_cnpj;
        $this->data_nascimento = $cliente->data_nascimento?->format('Y-m-d');
        $this->telefone = $cliente->telefone;
        $this->email = $cliente->email;
        $this->cep = $cliente->cep;
        $this->numero = $cliente->numero;
        $this->bairro = $cliente->bairro;
        $this->cidade = $cliente->cidade;
        $this->uf = $cliente->uf;
        $this->complemento = $cliente->complemento;
        $this->codigo_ibge = $cliente->codigo_ibge;
        $this->ie = $cliente->ie;
        $this->im = $cliente->im;
        $this->modoEdicao = true;
        $this->modalAberto = true;
        
        // Atualiza regra para ignorar o cpf_cnpj único do próprio cliente
        //$this->rules['cpf_cnpj'] = 'required|string|max:20|unique:clientes,cpf_cnpj,' . $cliente->id;
    }

    public function atualizar()
    {
        $this->validate();

        $cliente = Cliente::findOrFail($this->clienteSelecionadoId);

        $cliente->update([
            'nome' => $this->nome,
            'cpf_cnpj' => $this->cpf_cnpj,
            'tipo_pessoa' => $this->tipo_pessoa,
            'razao_social' => $this->razao_social,
            'nome_fantasia' => $this->nome_fantasia,
            'cnae_id' => $this->cnae_id,
            'cpf_cnpj' => $this->cpf_cnpj,
            'data_nascimento' => $this->data_nascimento,
            'telefone' => $this->telefone,
            'email' => $this->email,
            'cep' => $this->cep,
            'numero' => $this->numero,
            'bairro' => $this->bairro,
            'cidade' => $this->cidade,
            'uf' => $this->uf,
            'complemento' => $this->complemento,
            'codigo_ibge' => $this->codigo_ibge,
            'ie' => $this->ie,
            'im' => $this->im,
        ]);

        $this->resetCampos();
        $this->carregarClientes();
    }


    public function excluir($clienteId)
    {
        $cliente = Cliente::findOrFail($clienteId);

        if (method_exists($cliente, 'vendas') && $cliente->vendas()->exists()) {
            $this->mensagemErro = "Não é possível excluir o cliente '{$cliente->nome}' porque ele possui vendas registradas.";
            return;
        }

        $cliente->delete();
        $this->mensagemErro = '';

        // Atualiza a lista de clientes
        $this->clientes = Cliente::all();
    }

    public function ver($id)
    {
        $cliente = Cliente::findOrFail($id);

        $this->clienteSelecionadoId = $cliente->id;
        $this->tipo_pessoa = $cliente->tipo_pessoa;
        $this->nome = $cliente->nome;
        $this->razao_social = $cliente->razao_social;
        $this->nome_fantasia = $cliente->nome_fantasia;
        $this->cnae_id = $cliente->cnae_id;
        $this->cpf_cnpj = $cliente->cpf_cnpj;
        $this->data_nascimento = $cliente->data_nascimento?->format('Y-m-d');
        $this->telefone = $cliente->telefone;
        $this->email = $cliente->email;
        $this->cep = $cliente->cep;
        $this->numero = $cliente->numero;
        $this->bairro = $cliente->bairro;
        $this->cidade = $cliente->cidade;
        $this->uf = $cliente->uf;
        $this->complemento = $cliente->complemento;
        $this->codigo_ibge = $cliente->codigo_ibge;
        $this->ie = $cliente->ie;
        $this->im = $cliente->im;
        $this->preencherDados($cliente);
        $this->modoVisualizacao = true;
        $this->modalAberto = true;
    }

    private function preencherDados($cliente)
    {
        $this->clienteSelecionadoId = $cliente->id;
        $this->tipo_pessoa = $cliente->tipo_pessoa;
        $this->nome = $cliente->nome;
        $this->razao_social = $cliente->razao_social;
        $this->nome_fantasia = $cliente->nome_fantasia;
        $this->cnae_id = $cliente->cnae->id ?? null;
        $this->cpf_cnpj = $cliente->cpf_cnpj;
        $this->data_nascimento = optional($cliente->data_nascimento)->format('Y-m-d');
        $this->telefone = $cliente->telefone;
        $this->email = $cliente->email;
        $this->cep = $cliente->cep;
        $this->numero = $cliente->numero;
        $this->bairro = $cliente->bairro;
        $this->cidade = $cliente->cidade;
        $this->uf = $cliente->uf;
        $this->complemento = $cliente->complemento;
        $this->codigo_ibge = $cliente->codigo_ibge;
        $this->ie = $cliente->ie;
        $this->im = $cliente->im;
    }


    public function selecionarCliente($id)
    {
        $this->clienteSelecionadoId = $id;

        // Emitindo evento com ID do cliente
        $this->dispatch('clienteSelecionado', id: $id);
    }

    public function buscarEnderecoPorCep()
    {
        if (!$this->cep) return;

        try {
            $cepLimpo = preg_replace('/\D/', '', $this->cep);
            $response = Http::withOptions(['verify' => false])
                ->get("https://viacep.com.br/ws/{$this->cep}/json/");

            if ($response->failed() || isset($response['erro'])) {
                $this->mensagemErro = 'CEP não encontrado.';
                return;
            }

            $dados = $response->json();

            $this->cidade = $dados['localidade'] ?? '';
            $this->uf = $dados['uf'] ?? '';
            $this->bairro = $dados['bairro'] ?? '';
            $this->endereco = $dados['logradouro'] ?? '';
            $this->codigo_ibge = $dados['ibge'] ?? '';

            $this->dispatch('atualizarCamposEndereco');
        } catch (\Exception $e) {
            $this->mensagemErro = 'Erro ao buscar endereço: ' . $e->getMessage();
        }
        /*
        $ch = curl_init("https://viacep.com.br/ws/{$this->cep}/json/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = json_decode(curl_exec($ch));
        curl_close($ch);

        $this->cidade = $dados['localidade'] ?? '';
        $this->uf = $dados['uf'] ?? '';
        $this->bairro = $dados['bairro'] ?? '';
        $this->endereco = $dados['logradouro'] ?? '';
        $this->codigo_ibge = $dados['ibge'] ?? '';
        */
    }

}
