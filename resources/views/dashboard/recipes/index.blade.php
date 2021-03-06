@extends('home')

@section('dashboard')
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-2">
                    <span style="font-weight: bold">RECIPES</span>
                    @if(isset($user))
                    <span style="font-weight: bold"> for User {{ $user->name }}</span>
                    @endif
                </div>
                @if(!isset($user))
                <div class="col-md-7">
                    <form class="form-inline" method="POST" action="{{ route('dashboard.recipes.search') }}">
                        <div class="form-group">
                            <input class="form-control" style="width: inherit" name="search"
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
                    @role('superadministrator|administrator|moderator')
                    <a href="{{ route('dashboard.recipes.create') }}" class="card-link pull-right">Create a new
                        recipe</a>
                    @endrole
                </div>
                    @else
                    <div class="col-md-10">
                        <a href="{{ route('dashboard.user.refrigerators', ['user' => $user]) }}" class="card-link pull-right">Go Back</a>
                    </div>
                @endif
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
                            <p class="card-text">{!! isset($recipe->highlight->text[0]) ? $recipe->highlight->text[0] : $recipe->text !!}</p>
                            <p class="card-text" style="font-weight: 700">{!! isset($recipe->highlight->ingredients[0]) ? "Matches: " . $recipe->highlight->ingredients[0] : "" !!}</p>
                        </div>

                        <ul class="list-group">
                            @foreach($recipe->ingredients as $ingredient)
                                <p class="card-text">{{$ingredient->name}} - {{$ingredient->amount}}.</p>
                            @endforeach
                        </ul>
                        <div>
                            @role('superadministrator|administrator|moderator')
                            <a href="{{route('dashboard.recipes.edit', ['$recipe' => $recipe->id])}}">EDIT</a>
                            <button type="submit" class="btn btn-link" form="delete_{{$recipe->id}}">DELETE</button>
                            @endrole
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