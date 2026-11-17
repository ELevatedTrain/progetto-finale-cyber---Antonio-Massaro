<x-layout>
    <div class="container my-5" style="max-width: 640px">
        <h1 class="mb-4">Profilo</h1>

        <form method="POST" action="{{ route('user-profile-information.update') }}" class="mb-5">
            @csrf
            @method('PUT')

            <h2 class="h4">Dati personali</h2>

            @if (session('status') === 'profile-information-updated')
                <div class="alert alert-success">Dati personali aggiornati.</div>
            @endif

            <div class="mb-3">
                <label for="name" class="form-label">Nome</label>
                <input id="name" name="name" type="text" class="form-control"
                    value="{{ old('name', auth()->user()->name) }}" autocomplete="name" required>
                @error('name', 'updateProfileInformation')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input id="email" name="email" type="email" class="form-control"
                    value="{{ old('email', auth()->user()->email) }}" autocomplete="email" required>
                @error('email', 'updateProfileInformation')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Salva dati personali</button>
        </form>

        <form method="POST" action="{{ route('user-password.update') }}">
            @csrf
            @method('PUT')

            <h2 class="h4">Cambia password</h2>

            @if (session('status') === 'password-updated')
                <div class="alert alert-success">Password aggiornata.</div>
            @endif

            <div class="mb-3">
                <label for="current_password" class="form-label">Password attuale</label>
                <input id="current_password" name="current_password" type="password"
                    class="form-control" autocomplete="current-password" required>
                @error('current_password', 'updatePassword')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Nuova password</label>
                <input id="password" name="password" type="password"
                    class="form-control" autocomplete="new-password" required>
                @error('password', 'updatePassword')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Conferma nuova password</label>
                <input id="password_confirmation" name="password_confirmation" type="password"
                    class="form-control" autocomplete="new-password" required>
            </div>

            <button type="submit" class="btn btn-primary">Aggiorna password</button>
        </form>
    </div>
</x-layout>