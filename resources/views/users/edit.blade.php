<x-app-layout>
    <x-slot name="header">
        <h2>Editar Usuario</h2>
    </x-slot>

    <div class="p-6">

        <form method="POST" action="{{ route('users.update', $user->id) }}">
            @csrf
            @method('PUT')

            <input name="name" value="{{ $user->name }}" required>
            <input name="email" type="email" value="{{ $user->email }}" required>
            <input name="password" type="password" placeholder="Nueva contraseña (opcional)">

            <select name="role" required>
                <option value="admin" {{ $user->role=='admin'?'selected':'' }}>Admin</option>
                <option value="jefe" {{ $user->role=='jefe'?'selected':'' }}>Jefe</option>
                <option value="supervisor" {{ $user->role=='supervisor'?'selected':'' }}>Supervisor</option>
                <option value="asesor" {{ $user->role=='asesor'?'selected':'' }}>Asesor</option>
            </select>

            <select name="parent_id">
                <option value="">Sin superior</option>
                @foreach($users as $u)
                    <option value="{{ $u->id }}"
                        {{ $user->parent_id == $u->id ? 'selected' : '' }}>
                        {{ $u->name }} ({{ $u->role }})
                    </option>
                @endforeach
            </select>

            <button type="submit">Actualizar</button>
        </form>

    </div>
</x-app-layout>