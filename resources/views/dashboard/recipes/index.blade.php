@extends('home')

@section('dashboard')

    <div class="panel panel-default">
        <div class="panel-heading" style="">
            <span>Recipes</span>
            <a href="{{ route('dashboard.recipes.create') }}" class="card-link pull-right">Create a new recipe</a>
        </div>

        @foreach($recipes as $recipe)
            <div class="row" style="padding: 10px">
                <div class="col-md-7">
                    <div class="card" style="padding: 10px">

                        <div class="card-body">
                            <h5 class="card-title"><a
                                        href="{{ route('dashboard.recipes.show', ['recipe' => $recipe->id]) }}">{{$recipe->name}}
                                    .</a></h5>
                            <p class="card-text">{{$recipe->text}}.</p>
                        </div>

                        <ul class="list-group">
                            @foreach($recipe->ingredients as $ingredient)
                                <p class="card-text">{{$ingredient->name}} - {{$ingredient->amount}}.</p>
                            @endforeach
                        </ul>
                        <div>
                            <a href="{{route('dashboard.recipes.edit', ['$recipe' => $recipe->id])}}">EDIT</a>
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