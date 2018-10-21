@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2">
                <div class="panel panel-default">
                    <div class="panel-heading"><a href="{{route('dashboard.rules')}}">Rules</a></div>
                    <div class="panel-heading"><a href="{{route('dashboard.user.index')}}">Users</a></div>
                    <div class="panel-heading"><a href="{{route('dashboard.ingredients.index')}}">Ingredients</a></div>
                    <div class="panel-heading"><a href="{{route('dashboard.recipes.index')}}">Recipes</a></div>
                    <div class="panel-heading"><a href="{{route('dashboard.analytics')}}">Analytics</a></div>

                </div>
            </div>
            <div class="col-md-8">
                @yield('dashboard')
            </div>

            <div class="col-md-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Events</div>
                    <div class="panel-heading">Events</div>
                    <div class="panel-heading">Events</div>

                </div>
            </div>
        </div>
    </div>
@endsection
