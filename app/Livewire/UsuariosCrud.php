<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;

class UsuariosCrud extends Component
{
    public $usuarios;
    public $name, $email, $password, $password_confirmation, $role = 'user';
    public $editarId = null;
    public $modalAberto = false;

    protected function rules()
    {
        return [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $this->editarId,
            'password' => $this->editarId ? 'nullable|min:6|confirmed' : 'required|min:6|confirmed',
            'role' => 'required|in:admin,user',
        ];
    }

    public function mount()
    {
        $this->carregarUsuarios();
    }

    public function carregarUsuarios()
    {
        $this->usuarios = User::orderBy('name')->get();
    }

    public function salvar()
    {
        $this->validate();

        if ($this->editarId) {
            $user = User::findOrFail($this->editarId);
            $user->update([
                'name' => $this->name,
                'email' => $this->email,
                'role' => $this->role,
                'password' => $this->password ? Hash::make($this->password) : $user->password,
            ]);
            session()->flash('sucesso', 'Usuário atualizado com sucesso!');
        } else {
            User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'role' => $this->role,
            ]);
            session()->flash('sucesso', 'Usuário criado com sucesso!');
        }

        $this->resetarFormulario();
        $this->carregarUsuarios();
    }

    public function editar($id)
    {
        $user = User::findOrFail($id);
        
        $this->editarId = $id;
        $this->name = $user->name;
        $this->email = $user->email;
        //$this->password = $user->password;
        //$this->password_confirmation = $user->password_confirmation;
        $this->role = $user->role ?? 'user';
        //dd($this);
    }

    public function deletar($id)
    {
        User::destroy($id);
        session()->flash('sucesso', 'Usuário excluído com sucesso!');
        $this->carregarUsuarios();
    }

    public function resetarFormulario()
    {
        $this->reset(['name', 'email', 'password', 'password_confirmation', 'role', 'editarId', 'modalAberto']);
    }

    public function render()
    {
        return view('livewire.usuarios-crud');
    }
}
