@extends('home')

@section('dashboard')

    <div class="panel panel-default">
        <div class="panel-heading">
            <span style="font-weight: bold">USERS</span>
            <a href="{{ route('dashboard.user.new') }}" class="pull-right">Create a new user</a>
        </div>

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <table class="table table-bordered" style="text-align: center; vertical-align: inherit; font-size: small">
            <thead>
            <tr>
                <th scope="col" style="text-align: center; vertical-align: inherit;">ID</th>
                <th scope="col" style="text-align: center; vertical-align: inherit;">NAME</th>
                <th scope="col" style="text-align: center; vertical-align: inherit;">E-MAIL</th>
                <th scope="col" style="text-align: center; vertical-align: inherit;">INGREDIENTS</th>
                <th scope="col" style="text-align: center; vertical-align: inherit;">RECIPES</th>
                <th scope="col" style="text-align: center; vertical-align: inherit;">IN REFRIGERATOR</th>
                <th scope="col" style="text-align: center; vertical-align: inherit;">SOCIALITES</th>
                <th scope="col" style="text-align: center; vertical-align: inherit;">ROLE</th>
                <th scope="col" style="text-align: center; vertical-align: inherit;">OPTION</th>
            </tr>
            </thead>
            <tbody style="text-align: center; vertical-align: inherit; color: darkslategray">
            @foreach($users as $user)
                <tr>
                    <th scope="row" style="text-align: center; vertical-align: inherit;">{{ $user->id }}</th>
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
                    <td>
                        <p>in development</p>
                    </td>
                    <td>
                        <button type="submit" class="btn btn-link" form="delete_{{$user->id}}"><span>delete</span>
                        </button>
                    </td>
                </tr>
                {{-- This is hidden form for destroy user --}}
                <form id="delete_{{$user->id}}" method="GET"
                      action="{{route('dashboard.user.delete', ['user' => $user->id])}}">
                    {{ csrf_field() }}
                </form>
            @endforeach()
            </tbody>
        </table>
        <div class="row" style="padding: 10px">
            <div class="col-md-8">
                <div class="card" style="padding: 10px">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>

    <style>

    </style>
@endsection

