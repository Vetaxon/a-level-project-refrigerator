@extends('home')

@section('dashboard')

    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-2">
                    <span style="font-weight: bold">RECIPES</span>
                </div>
                <div class="col-md-7">
                    <form class="form-inline my-5" method="POST" action="{{ route('dashboard.recipes.search') }}">
                        <div class="form-group">
                            <input class="form-control mr-sm-2" style="width: inherit" name="search"
                                   placeholder="Search recipe" value="{{ old('search') }}">
                            {{ csrf_field() }}
                        </div>
                        <div class="form-group">
                            <button class="btn btn-success small" type="submit">Search</button>
                        </div>
                    </form>
                    @if ($errors->has('search'))
                        <span class="help-block" style="color: darkred">
                                        <strong>{{ $errors->first('search') }}</strong>
                                    </span>
                    @endif

                </div>
                <div class="col-md-3">
                    <a href="{{ route('dashboard.recipes.create') }}" class="card-link pull-right">Create a new
                        recipe</a>
                </div>
            </div>
        </div>

        @foreach($recipes as $recipe)
            <div class="row" style="padding: 10px">
                <div class="col-md-7">
                    <div class="card" style="padding: 10px">

                        <div class="card-body">
                            <h5 class="card-title"><a style="font-size: 120%"
                                        href="{{ route('dashboard.recipes.show', ['recipe' => $recipe->id]) }}">
                                    {!!  isset($recipe->highlight->name[0]) ? $recipe->highlight->name[0] : $recipe->name !!}
                                    .</a></h5>
                            <p class="card-text">{!! $recipe->text !!}</p>
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

        @if(!isset($paginate))
        <div class="row" style="padding: 10px">
            <div class="col-md-8">
                <div class="card" style="padding: 10px">
                    {!! $recipes->links() !!}
                </div>
            </div>
        </div>
        @endif
    </div>

@endsection