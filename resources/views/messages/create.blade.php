@extends('layout')

@section('content')
    <h1 class="mb-4">Nieuw bericht</h1>

    <form action="/messages" method="post">
        @csrf
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <select name="colleague_email" class="form-control">
                        <option value="">Selecteer een collega</option>
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

@endsection
