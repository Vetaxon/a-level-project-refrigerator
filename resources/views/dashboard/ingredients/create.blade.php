@extends('home')
@section('dashboard')
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-heading">
                <span style="font-weight: bold">NEW INGREDIENT</span>
                <a href="{{ route('dashboard.ingredients.index') }}" class="pull-right">Go back</a>
            </div>
        </div>
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        <div class="row" style="padding: 10px">
            <div class="col-md-7">
                <form id="create_ingredient" method="POST" action="{{route('dashboard.ingredients.store')}}">
                    <div class="form-group">
                        {{ csrf_field() }}
                        <input type="text" name="name" class="form-control" maxlength="255" required="required" placeholder="Input new ingredient name here">
                    </div>
                </form>
            </div>
            <div class="col-md-5">
                <button type="submit" form="create_ingredient" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
@endsection