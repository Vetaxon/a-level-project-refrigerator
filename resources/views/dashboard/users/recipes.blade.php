@extends('home')

@section('dashboard')

    <div class="panel panel-default">
        <div class="panel-heading">
            <span style="font-weight: bold">RECIPES OF USER {{$user->name}}</span>
            <a href="{{ route('dashboard.user.index') }}" class="pull-right">Go back</a>
        </div>

        @foreach($recipes as $recipe)
            <div class="row" style="padding: 10px">
                <div class="col-md-7">
                    <div class="card" style="padding: 10px">
                        <div class="card-body">
                            <h5 class="card-title">{{$recipe->name}}</h5>
                            <span class="card-text">{{$recipe->text}}.</span>
                        </div>
                        <ul class="list-group">
                            @foreach($recipe->ingredients as $ingredient)
                                <p class="card-text">{{$ingredient->name}} - {{$ingredient->amount}}.</p>
                            @endforeach
                        </ul>
                        <div>
                            <button type="submit" class="btn btn-link" form="delete_{{$recipe->id}}">DELETE</button>
                        </div>

                        <div>
                            <form id="delete_{{$recipe->id}}" method="POST" action="{{ route('dashboard.recipes.destroy', [
                                'recipe' => $recipe->id
                                ]) }}">
                                {{ method_field('DELETE') }}
                                {{ csrf_field() }}
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <img class="img-responsive" style="padding: 5px" src="{{ $recipe->picture }}">
                </div>

            </div>
        @endforeach
    </div>
@endsection