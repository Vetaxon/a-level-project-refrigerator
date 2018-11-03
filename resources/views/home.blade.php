@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2">
                <div class="panel panel-default">
                    @role('superadministrator|administrator|moderator')
                    <div class="panel-heading"><a href="{{route('dashboard.user.index')}}">Users</a></div>
                    @endrole
                    @role('superadministrator|administrator|moderator')
                    <div class="panel-heading"><a href="{{route('dashboard.roles.index')}}">Roles</a></div>
                    @endrole
                    <div class="panel-heading"><a href="{{route('dashboard.ingredients.index')}}">Ingredients</a></div>
                    <div class="panel-heading"><a href="{{route('dashboard.recipes.index')}}">Recipes</a></div>
                    @role('superadministrator|administrator|moderator')
                    <div class="panel-heading"><a href="{{route('dashboard.analytics')}}">Analytics</a></div>
                    @endrole
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
