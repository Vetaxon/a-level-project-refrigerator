@extends('home')

@section('dashboard')

    <div class="panel panel-default">
        <div class="panel-heading" style="">Recipes for {{ $user->name }}</div>

        @foreach($recipes as $recipe)
            <div class="row" style="padding: 10px">
                <div class="col-md-offset-2">
                    <div class="card" style="padding: 10px">

                        <div class="card-body">
                            <h5 class="card-title">{{$recipe->name}}.</h5>
                            <p class="card-text">{{$recipe->text}}.</p>
                        </div>

                        <ul class="list-group list-group-flush">
                            @foreach($recipe->ingredients as $ingredient)
                                <li class="list-group-item" style="padding: 5px; width: 50rem;">{{$ingredient->name}}
                                    - {{$ingredient->amount}}.
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <hr>
        @endforeach
    </div>
@endsection