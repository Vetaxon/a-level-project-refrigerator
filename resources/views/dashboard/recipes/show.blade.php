@extends('home')

@section('dashboard')

    <div class="panel panel-default">
        <div class="panel-heading" style="">
            <span style="font-weight: bold">A RECIPE</span>
            <a href="{{ route('dashboard.recipes.edit', ['recipe' => $recipe->id]) }}"
               class="card-link pull-right">Edit recipe</a>
        </div>

        <div class="row" style="padding: 10px">
            <div class="col-md-7">
                <div class="card" style="padding: 10px">

                    <div class="card-body">
                        <h5 class="card-title">{{$recipe->name}}.</h5>
                        <p class="card-text">{!! $recipe->text !!}.</p>
                    </div>

                    <ul class="list-group">
                        @foreach($recipe->ingredients as $ingredient)

                            <div class="col-md-8">
                                <span>{{$ingredient->name}} - {{$ingredient->amount}}.</span>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-link" form="delete_{{$ingredient->id}}">Delete
                                </button>
                            </div>

                            <div>
                                <form id="delete_{{$ingredient->id}}" method="POST" action="{{ route('dashboard.recipes.delete.ingredient', [
                                'recipe' => $recipe->id,
                                'ingredient' => $ingredient->id
                                ]) }}">
                                    {{ method_field('DELETE') }}
                                    {{ csrf_field() }}
                                </form>
                            </div>

                        @endforeach
                    </ul>

                </div>
            </div>
            <div class="col-md-5">
                <img class="img-responsive" style="padding: 5px" src="{{ $recipe->picture }}">
            </div>

            <div class="row">
                <div class="col-md-12" style="padding-top: 30px">
                    <form class="form-horizontal" method="POST"
                          action="{{ route('dashboard.recipes.add.ingredient', ['recipe' => $recipe->id]) }}">
                        {{ csrf_field() }}
                        {{ method_field('POST') }}
                        <div class="col-md-6">
                            <input id="name" type="text" class="form-control" name="name"
                                   placeholder="Ingredient"
                                   value="{{ old('name') }}" required autofocus>
                            @if ($errors->has('name'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                            @endif
                            @if ($errors->has('ingredient_id'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('ingredient_id') }}</strong>
                                    </span>
                            @endif
                            @if (session('status'))
                                <span class="help-block" style="color: #1e7e34">
                                    <strong>{{ session('status') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <input id="amount" type="text" class="form-control" name="amount" placeholder="Amount"
                                   value="{{ old('amount') }}" required autofocus>
                            @if ($errors->has('amount'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('amount') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary">
                                Add ingredient
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection

