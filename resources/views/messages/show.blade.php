@extends('layout')

@section('content')

    @if ($authenticated)
        <h1 class="mb-4">Bericht</h1>
        <p>{{ $body }}</p>
        <form method="POST" action="{{ route('messages.destroy', $token) }}">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-primary">Bericht gelezen</button>
        </form>
    @else
        <h1 class="mb-4">Open bericht</h1>

        <form method="POST" action="{{ route('messages.authenticate', $token) }}">
            @csrf
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="password">Wachtwoord</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Bekijk bericht</button>
        </form>

        @if (session('error'))
            <x-notifications.error>
                {{ session('error') }}
            </x-notifications.error>
        @endif
    @endif

@endsection
