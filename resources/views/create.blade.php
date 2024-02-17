@extends('layout')

@section('content')
    <div class="row">

        <form action="" method="post">
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
                    <textarea required name="messagecontent" class="form-control" rows="5"
                              placeholder="Plaats hier je bericht*"></textarea>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Versleutel bericht</button>
        </form>

    </div>
@endsection
