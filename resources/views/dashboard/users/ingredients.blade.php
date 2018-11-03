@extends('home')

@section('dashboard')

    <div class="panel panel-default">
        <div class="panel-heading">
            <span style="font-weight: bold">INGREDIENTS OF USER {{$user->name}}</span>
            <a href="{{ route('dashboard.user.index') }}" class="pull-right">Go back</a>
        </div>

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <table class="table table-bordered" style="text-align: center; vertical-align: inherit; font-size: small">
            <thead>
            <tr>
                <th scope="col" style="text-align: center; vertical-align: inherit; font-size: small">ID</th>
                <th scope="col" style="text-align: center; vertical-align: inherit; font-size: small">NAME</th>
                <th scope="col" style="text-align: center; vertical-align: inherit; font-size: small">CREATED_AT</th>
            </tr>
            </thead>
            <tbody>
            @foreach($ingredients as $ingredient)
                <tr>
                    <th style="text-align: center; vertical-align: inherit; font-size: small">{{ $ingredient->id }}</th>
                    <td style="text-align: left;">{{ $ingredient->name }}</td>
                    <td>{{ $ingredient->created_at }}</td>
                </tr>
            @endforeach()
            </tbody>
        </table>

    </div>
@endsection