@extends('home')

@section('dashboard')

    <div class="panel panel-default">
        <div class="panel-heading">
            <span style="font-weight: bold">INGREDIENTS</span>
            @role('superadministrator|administrator|moderator')
            <a href="{{ route('dashboard.ingredients.create') }}" class="pull-right">Create new ingredient</a>
            @endrole
        </div>

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        @foreach($ingredients as $ingredient)
            <div class="row" style="padding: 10px">
                <div class="col-md-8">
                    <div class="card" style="padding: 10px">

                        <div class="card-body">

                            <label for="ingredient_{{$ingredient->id}}_name">ingredient id: {{$ingredient->id}}</label>
                            <form id="{{$ingredient->id}}" method="POST"
                                  action="{{route('dashboard.ingredients.update', ['ingredient' => $ingredient->id])}}">
                                {{ method_field('PUT') }}
                                {{ csrf_field() }}
                                <input id="ingredient_{{$ingredient->id}}_name" type="text" class="form-control"
                                       name="name" maxlength="255" required="required" value="{{$ingredient->name}}"
                                       @if(!Auth::user()->hasRole('superadministrator|administrator|moderator'))
                                    disabled="disabled"
                                    @endif
                                >
                            </form>
                            <div style="padding-top: 8px;">
                                @role('superadministrator|administrator|moderator')
                                <button type="submit" class="btn btn-primary" form="{{$ingredient->id}}">Save</button>
                                <button type="submit" class="btn btn-primary" form="delete_{{$ingredient->id}}">Delete
                                </button>
                                @endrole
                            </div>
                        </div>

                        {{-- This is hidden form for destroy ingredient --}}
                        <form id="delete_{{$ingredient->id}}" method="POST"
                              action="{{route('dashboard.ingredients.destroy', ['ingredient' => $ingredient->id])}}">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                        </form>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="row" style="padding: 10px">
            <div class="col-md-8">
                <div class="card" style="padding: 10px">
                    {{ $ingredients->links() }}
                </div>
            </div>
        </div>
    </div>

@endsection