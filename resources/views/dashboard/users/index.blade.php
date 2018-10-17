@extends('home')

@section('dashboard')

    <div class="panel panel-default">
        <div class="panel-heading">
            <span>Users</span>
            <a href="{{ route('dashboard.user.new') }}" class="pull-right">Create a new user</a>
        </div>


        <table class="table table-bordered">
            <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">NAME</th>
                <th scope="col">E-MAIL</th>
                <th scope="col">INGREDIENTS</th>
                <th scope="col">RECIPES</th>
                <th scope="col">IN REFRIGERATOR</th>
                <th scope="col">SOCIALITES</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                    <th scope="row">{{ $user->id }}</th>
                    <td><a href="{{ route('dashboard.user.editName', ['user' => $user->id]) }}">{{ $user->name }}</a></td>
                    <td>
                        @if(isset($user->email))
                        <a href="{{ route('dashboard.user.editEmail', ['user' => $user->id]) }}">{{ $user->email }}</a>
                        @else {{ "-"  }}
                        @endif
                    </td>
                    <td>
                        @if(isset($user->ingredients[0]->count))
                            <a href="{{ route('dashboard.user.ingredients', ['user' => $user->id]) }}">
                                {{ $user->ingredients[0]->count }} ingredients
                            </a>
                        @else {{ 0 }}
                        @endif
                    </td>
                    <td>
                        @if(isset($user->recipes[0]->count))
                            <a href="{{ route('dashboard.user.recipes', ['user' => $user->id]) }}">{{ $user->recipes[0]->count }}
                                recipes</a>
                        @else {{ 0 }}
                        @endif
                    </td>
                    <td>
                        @if(isset($user->refrigerators[0]->count))
                            <a href="{{ route('dashboard.user.refrigerators', ['user' => $user->id]) }}">
                                {{ $user->refrigerators[0]->count }} ingredients in refrigerator</a>
                        @else {{ 0 }}
                        @endif
                    </td>
                    <td>
                        @if(count($user->socialites) > 0)
                            @foreach($user->socialites as $socialite)
                                {{ $socialite->provider }} <br>
                            @endforeach
                        @endif
                    </td>
                </tr>
            @endforeach()
            </tbody>
        </table>
    </div>
@endsection