@extends('home')

@section('dashboard')

    <div class="panel panel-default">
        <div class="panel-heading" style="">
            <span>Recipes</span>
            <a href="{{ route('dashboard.recipes.create') }}" class="pull-right">Create a new recipe</a>
        </div>

        @foreach($recipes as $recipe)
            <div class="row" style="padding: 10px">
                <div class="col-md-7">
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
                        <div>
                            <a href="{{route('dashboard.recipes.edit', ['$recipe' => $recipe->id])}}">EDIT</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <img class="card-img-top" style="padding: 5px" src="{{asset('images/recipe-red-pepper-deviled-eggs.jpg')}}"
                         alt="Card image cap">
                </div>
                <hr>
                {{--<div class="card-body">--}}
                {{--<a href="#" class="card-link">Ссылка карты</a>--}}
                {{--<a href="#" class="card-link">Другая ссылка</a>--}}
                {{--</div>--}}
            </div>
        @endforeach
    </div>

@endsection