@extends('home')

@section('dashboard')
    <div class="panel panel-default">
        <div class="panel-heading">
            <span style="font-weight: bold">USERS</span>
            <a href="{{ route('dashboard.user.index') }}" class="pull-right">Go back</a>
        </div>

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <div class="row" style="padding: 10px">
            <div class="col-md-8">
                <div class="card" style="padding: 10px">
                    <div class="card-body">
                        <div class="panel-body">
                            <form class="form-horizontal" method="POST"
                                  action="{{ route('dashboard.user.update', ['user' => $user->id]) }}">
                                {{ csrf_field() }}
                                {{ method_field('PUT') }}
                                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                    <label for="name" class="col-md-4 control-label">Name</label>

                                    <div class="col-md-6">
                                        <input id="name" type="text" class="form-control" name="name"
                                               value="{{ $user->name }}" required>

                                        @if ($errors->has('name'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-8 col-md-offset-6">
                                    <button type="submit" class="btn btn-primary">
                                        Edit
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection