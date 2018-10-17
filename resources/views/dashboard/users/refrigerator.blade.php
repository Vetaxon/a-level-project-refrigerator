@extends('home')

@section('dashboard')

    <div class="panel panel-default">
        <div class="panel-heading">Ingredients in a refrigerator for {{$user->name}}</div>

        <table class="table table-bordered">
            <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">NAME</th>
                <th scope="col">Amount</th>
            </tr>
            </thead>
            <tbody>
            @foreach($refrigerator as $ingredient)
                <tr>
                    <th scope="row">{{ $ingredient->id }}</th>
                    <td>{{ $ingredient->name }}</td>
                    <td>{{ $ingredient->amount }}</td>
                </tr>
            @endforeach()
            </tbody>
        </table>
    </div>
@endsection