<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Usuario
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-md mx-auto bg-white shadow rounded p-6">

            <form action="{{ route('usuarios.update', $usuario) }}" method="POST">
                @csrf
                @method('PUT')

                <div>
                    <label class="font-semibold">Nombre</label>
                    <input type="text" name="name" class="w-full mt-1 border-gray-300 rounded"
                           value="{{ old('name', $usuario->name) }}">
                    @error('name') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                </div>

                <div class="mt-4">
                    <label class="font-semibold">Email</label>
                    <input type="email" name="email" class="w-full mt-1 border-gray-300 rounded"
                           value="{{ old('email', $usuario->email) }}">
                    @error('email') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                </div>

                <div class="mt-4">
                    <label class="font-semibold">Rol</label>
                    <select name="role" class="w-full mt-1 border-gray-300 rounded">
                        <option value="Administrador" {{ $usuario->roles->first()->name == 'Administrador' ? 'selected' : '' }}>Administrador</option>
                        <option value="Tabulador" {{ $usuario->roles->first()->name == 'Tabulador' ? 'selected' : '' }}>Tabulador</option>
                    </select>
                    @error('role') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                </div>

                <div class="mt-6 flex justify-between">
                    <a href="{{ route('usuarios.index') }}"
                       class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                        Cancelar
                    </a>

                    <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Actualizar
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
