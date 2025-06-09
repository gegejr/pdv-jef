<?php

namespace App\Livewire\Usuarios;

use App\Models\User;
use Livewire\Component;

class EditarUsuario extends Component
{
    public $usuarioId;
    public $name;
    public $email;
    public $role = 'user';
    public $modalAberto = false;
    protected $listeners = ['abrirModalEdicao' => 'carregarUsuario'];

    public function editar($id)
    {
        $usuario = User::findOrFail($id);

        $this->usuarioId = $usuario->id;
        $this->name = $usuario->name;
        $this->email = $usuario->email;
        $this->role = $usuario->role ?? 'user';
        $this->modalAberto = true;
        $this->dispatch('abrir-modal-edicao');
    }

    public function atualizar()
    {
        $this->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $this->usuarioId,
            'role' => 'required|in:admin,user',
        ]);

        User::find($this->usuarioId)?->update([
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
        ]);

        $this->dispatch('fechar-modal-edicao');
        session()->flash('sucesso', 'Usuário atualizado com sucesso!');
    }

    public function render()
    {
        return view('livewire.usuarios.editar-usuario');
    }
}

