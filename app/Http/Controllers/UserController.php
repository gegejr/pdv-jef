<?php

// app/Http/Controllers/UserController.php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function create()
    {
        $usuarios = \App\Models\User::all(); // Carrega todos os usuários
        return view('usuarios.criar', compact('usuarios'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6',
            'role' => 'required|in:admin,user',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('painel')->with('sucesso', 'Usuário criado com sucesso!');
    }

        public function index()
    {
        $usuarios = User::all();
        return view('usuarios.criar', compact('usuarios'));
    }

    public function edit($id)
    {
        $usuario = User::findOrFail($id);
        return view('usuarios.editar', compact('usuario'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => "required|email|unique:users,email,$id",
            'role' => 'required|in:admin,user',
        ]);
    
        $usuario = User::findOrFail($id);
        $usuario->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);
    
        return redirect()->route('usuarios.index')->with('sucesso', 'Usuário atualizado com sucesso!');
    }

    public function destroy($id)
    {
        User::destroy($id);
        return redirect()->route('usuarios.index')->with('sucesso', 'Usuário excluído com sucesso!');
    }
}
