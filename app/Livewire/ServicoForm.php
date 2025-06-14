<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Servico;

class ServicoForm extends Component
{
    //começamos primeiro pelas váriaveis 
    public $nome, $descricao, $valor, $duracao, $ativo = true;
    public $servico_id;

    // regras
    public function rules()
    {
        //return
        return [
            /*'modelo' => 'required ou nullable|tipo de dado(string, numeric, boolean)|quantidade de caracteres */
            'nome' => 'required|string|max:255',
            'valor' => 'required|numeric|min:0',
            'duracao' => 'required|integer|min:0',
            'descricao' => 'required|string|',
            'ativo' => 'boolean',   

        ];
    }

    //metodo de cadastro
    public function salvar()
    {
        $this->validate();

        $dados = $this->only(['nome','valor', 'duracao', 'descricao', 'ativo']);

        if($this->servico_id){
            $servico = Servico::findOrFail($this->servico_id);
            $servico->update($dados);
            session()->flash('sucesso', 'Serviço atualizado com sucesso!');
        } else {
            Servico::create($dados);
            session()->flash('sucesso', 'Serviço cadastrado com sucesso!');
        }

        $this->reset(['nome', 'descricao', 'valor', 'duracao', 'ativo', 'servico_id']);
        $this->dispatch('servicoAtualizado');
    }
    
    public function render()
    {
        return view('livewire.servico-form');
    }

}
