@extends('home')

@section('dashboard')

    <div class="panel panel-default">
        <div class="panel-heading">Ingredients of {{$user->name}}</div>


        <table class="table table-bordered">
            <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">NAME</th>
                <th scope="col">CREATED_AT</th>
            </tr>
            </thead>
            <tbody>
            @foreach($ingredients as $ingredient)
                <tr>
                    <th scope="row">{{ $ingredient->id }}</th>
                    <td>{{ $ingredient->name }}</td>
                    <td>{{ $ingredient->created_at }}</td>

                </tr>
            @endforeach()
            </tbody>
        </table>
    </div>
@endsection