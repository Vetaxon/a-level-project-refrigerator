@extends('home')

@section('dashboard')

    <div class="panel panel-default">
        <div class="panel-heading">
            <span style="font-weight: bold">INGREDIENTS IN THE REFRIGERATOR OF USER {{$user->name}}</span>
            <a href="{{ route('dashboard.user.index') }}" class="pull-right">Go back</a>
        </div>

        <table class="table table-bordered" class="table table-bordered"
               style="text-align: center; vertical-align: inherit; font-size: small">
            <thead>
            <tr>
                <th scope="col" style="text-align: center; vertical-align: inherit; font-size: small">ID</th>
                <th scope="col" style="text-align: center; vertical-align: inherit; font-size: small">NAME</th>
                <th scope="col" style="text-align: center; vertical-align: inherit; font-size: small">Amount</th>
            </tr>
            </thead>
            <tbody>
            @foreach($refrigerator as $ingredient)
                <tr>
                    <th style="text-align: center; vertical-align: inherit; font-size: small"
                        scope="row">{{ $ingredient->id }}</th>
                    <td style="text-align: left">{{ $ingredient->name }}</td>
                    <td>{{ $ingredient->amount }}</td>
                </tr>
            @endforeach()
            </tbody>
        </table>
    </div>
@endsection