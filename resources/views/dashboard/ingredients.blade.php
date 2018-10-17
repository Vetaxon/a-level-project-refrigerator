@extends('home')

@section('dashboard')

    <div class="panel panel-default">
        <div class="panel-heading">
            <a class="text-primary" href="{{route('dashboard.ingredients.create')}}">Create new ingredient</a>
        </div>

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        @foreach($ingredients as $ingredient)
            <div class="row" style="padding: 10px">
                <div class="col-md-8">
                    <div class="card" style="padding: 10px">

                        <div class="card-body">

                            <label for="ingredient_{{$ingredient->id}}_name">ingredient id: {{$ingredient->id}}</label>
                            <form id="{{$ingredient->id}}" method="POST" action="{{route('dashboard.ingredients.update', ['ingredient' => $ingredient->id])}}">
                                {{ method_field('PUT') }}
                                {{ csrf_field() }}
                                <input id="ingredient_{{$ingredient->id}}_name" type="text" class="form-control" name="name" maxlength="255" required="required" value="{{$ingredient->name}}">
                            </form>
                            <div style="padding-top: 8px;">
                                <button type="submit" class="btn btn-primary" form="{{$ingredient->id}}">Save</button>
                                <button type="submit" class="btn btn-primary" form="delete_{{$ingredient->id}}">Delete</button>
                            </div>
                        </div>

                        {{-- This is hidden form for destroy ingredient --}}
                        <form id="delete_{{$ingredient->id}}" method="POST" action="{{route('dashboard.ingredients.destroy', ['ingredient' => $ingredient->id])}}">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                        </form>
                    </div>
                </div>

                <div class="col-md-5">

                </div>


                {{--<div class="card-body">--}}
                {{--<a href="#" class="card-link">Ссылка карты</a>--}}
                {{--<a href="#" class="card-link">Другая ссылка</a>--}}
                {{--</div>--}}
            </div>
        @endforeach

        <div class="row" style="padding: 10px">
            <div class="col-md-8">
                <div class="card" style="padding: 10px">
                        {{ $ingredients->links() }}
                </div>
            </div>
        </div>
    </div>

@endsection