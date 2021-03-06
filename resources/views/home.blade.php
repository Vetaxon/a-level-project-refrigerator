@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2">
                <div class="panel panel-default">
                    @role('superadministrator|administrator|moderator')
                    <div class="panel-heading"><a
                                style="font-weight: {{ request()->route()->getName() === 'dashboard.roles.index' ? 'bold' : '' }}"
                                href="{{route('dashboard.roles.index')}}">Roles</a></div>
                    @endrole
                    @role('superadministrator|administrator')
                    <div class="panel-heading"><a
                                style="font-weight: {{ request()->route()->getName() === 'dashboard.user.index' ? 'bold' : '' }}"
                                href="{{route('dashboard.user.index')}}">Users</a></div>
                    @endrole
                    <div class="panel-heading"><a
                                style="font-weight: {{ request()->route()->getName() === 'dashboard.ingredients.index' ? 'bold' : '' }}"
                                href="{{route('dashboard.ingredients.index')}}">Ingredients</a></div>

                    <div class="panel-heading"><a
                                style="font-weight: {{ request()->route()->getName() === 'dashboard.recipes.index' ? 'bold' : '' }}"
                                href="{{route('dashboard.recipes.index')}}">Recipes</a></div>
                    @role('superadministrator|administrator')
                    <div class="panel-heading"><a href="{{route('dashboard.logs')}}">Logs</a></div>
                    @endrole
                </div>
            </div>
            <div class="col-md-8">
                @if(request()->route()->getName() === 'home')
                    <div class="card">
                        <div class="card-body" style="text-align: center">
                            <h1>Welcome to dashboard</h1>
                        </div>
                    </div>
                @else
                    @yield('dashboard')
                @endif
            </div>
            <div class="col-md-2">
                @role('superadministrator|administrator')
                <div class="card">
                    <div class="card-header">
                        <p>EVENTS</p>
                    </div>
                    <div id="app">
                        <event-message events="{{$events}}"></event-message>
                    </div>
                </div>
                @endrole
            </div>
        </div>
    </div>

@endsection
