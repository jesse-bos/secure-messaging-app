@extends('layout')

@section('content')
    <h1 class="mb-4">Nieuw bericht</h1>

    <form action="/messages" method="post">
        @csrf
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <select name="mail_to" class="form-control">
                        <option value="">Selecteer een collega</option>
                        @foreach ($colleagues as $colleague)
                            <option value="{{ $colleague['email'] }}">{{ $colleague['name'] }} -
                                {{ $colleague['email'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <textarea required name="body" class="form-control" rows="5" placeholder="Plaats hier je bericht"></textarea>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Versleutel bericht</button>
    </form>

    @if (session('messageStored'))
        <x-notifications.success>
            <div class="mb-2">
                Bericht succesvol versleuteld! Gebruik onderstaande gegevens om het bericht te openen. De
                URL is 30 minuten
                geldig.
            </div>
            <div class="mb-2">
                <a href="{{ session('messageStored')['url'] }}">Ontsleutel pagina</a>
            </div>
            <div>
                Wachtwoord:
            </div>
            <div>
                {{ session('messageStored')['password'] }}
            </div>
        </x-notifications.success>
    @endif
@endsection
