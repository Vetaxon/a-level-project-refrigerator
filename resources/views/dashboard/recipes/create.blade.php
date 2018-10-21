@extends('home')

@section('dashboard')

    <div class="panel panel-default">
        <div class="panel-heading">
            <span>Create a new recipe</span>
            <a href="{{ route('dashboard.recipes.index') }}" class="pull-right">Recipes</a>
        </div>

        <div class="row" style="padding: 10px">
            <div class="col-md-12">
                <div class="card" style="padding: 10px">

                    <div class="card-body">

                        <div class="panel-body">
                            <form class="form-horizontal" method="POST" action="{{ route('dashboard.recipes.store') }}"
                                  enctype="multipart/form-data">
                                {{ csrf_field() }}

                                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                    <label for="name" class="col-md-3 control-label">Name</label>

                                    <div class="col-md-9">
                                        <input id="name" type="text" class="form-control" name="name"
                                               value="{{ old('name') }}" required autofocus>

                                        @if ($errors->has('name'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('text') ? ' has-error' : '' }}">
                                    <label for="text" class="col-md-3 control-label">Recipe description</label>

                                    <div class="col-md-9">
                                        <textarea id="text" rows="10" name="text" class="form-control"
                                                  value="{{ old('text') }}" required autofocus></textarea>

                                        @if ($errors->has('text'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('text') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('picture') ? ' has-error' : '' }}">
                                    <label for="picture" class="col-md-3 control-label">Image</label>

                                    <div class="col-md-9">
                                        <input id="picture" type="file" class="form-control-file" name="picture"
                                               autofocus>

                                        @if ($errors->has('picture'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('picture') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-8 col-md-offset-3">
                                    <button type="submit" class="btn btn-primary">
                                        Create recipe
                                    </button>
                                </div>
                            </form>

                        </div>

                        <div>
                            @if (session('status'))
                                <div class="alert alert-success">
                                    {{ session('status') }}
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection