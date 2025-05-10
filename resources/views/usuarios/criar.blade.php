@extends('layouts.app')

@section('content')
<x-sidebar />
<x-topbar />

<div class="max-w-lg mx-auto mt-24 p-6 bg-white shadow-md rounded-lg">
    <h2 class="text-2xl font-semibold mb-4">Criar Novo Usuário</h2>

    @if ($errors->any())
        <div class="mb-4 text-red-600">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('usuarios.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Nome</label>
            <input type="text" name="name" class="w-full border p-2 rounded" required>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">E-mail</label>
            <input type="email" name="email" class="w-full border p-2 rounded" required>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Senha</label>
            <input type="password" name="password" class="w-full border p-2 rounded" required>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Confirmar Senha</label>
            <input type="password" name="password_confirmation" class="w-full border p-2 rounded" required>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Função</label>
            <select name="role" class="w-full border p-2 rounded">
                <option value="user">Usuário</option>
                <option value="admin">Administrador</option>
            </select>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Criar</button>
    </form>
</div>

@if(session('sucesso'))
    <div class="max-w-lg mx-auto mt-4 text-green-600">{{ session('sucesso') }}</div>
@endif

@if(isset($usuarios) && $usuarios->count())
    <div class="max-w-4xl mx-auto mt-12 p-4 bg-white shadow rounded-lg">
        <h3 class="text-lg font-semibold mb-4">Usuários Cadastrados</h3>

        <table class="min-w-full bg-white border">
            <thead>
                <tr>
                    <th class="px-4 py-2 border">Nome</th>
                    <th class="px-4 py-2 border">Email</th>
                    <th class="px-4 py-2 border">Função</th>
                    <th class="px-4 py-2 border">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($usuarios as $usuario)
                    <tr>
                        <td class="px-4 py-2 border">{{ $usuario->name }}</td>
                        <td class="px-4 py-2 border">{{ $usuario->email }}</td>
                        <td class="px-4 py-2 border">{{ $usuario->role ?? 'user' }}</td>
                        <td class="px-4 py-2 border space-x-2">
                        <a href="javascript:void(0)" onclick='abrirModal(@json($usuario))' class="text-blue-500 hover:underline">Editar</a>
                            <form action="{{ route('usuarios.destroy', $usuario->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Tem certeza que deseja excluir?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Excluir</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <!-- Modal de Edição -->
<div x-data="{ open: false }">
    <script>
        function abrirModal(usuario) {
            document.getElementById('editForm').action = '/usuarios/' + usuario.id;
            document.getElementById('editName').value = usuario.name;
            document.getElementById('editEmail').value = usuario.email;
            document.getElementById('editRole').value = usuario.role ?? 'user';
            document.querySelector('[x-data]').__x.$data.open = true;
        }
    </script>

    <div x-show="open" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white rounded-lg shadow p-6 w-full max-w-md">
            <h2 class="text-xl font-semibold mb-4">Editar Usuário</h2>

            <form method="POST" id="editForm">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-sm">Nome</label>
                    <input type="text" name="name" id="editName" class="w-full border p-2 rounded" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm">E-mail</label>
                    <input type="email" name="email" id="editEmail" class="w-full border p-2 rounded" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm">Função</label>
                    <select name="role" id="editRole" class="w-full border p-2 rounded">
                        <option value="user">Usuário</option>
                        <option value="admin">Administrador</option>
                    </select>
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" @click="open = false" class="bg-gray-400 text-white px-4 py-2 rounded">Cancelar</button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

    </div>
@endif
@endsection
