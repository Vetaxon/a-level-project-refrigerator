@extends('home')

@section('dashboard')

    <div class="panel panel-default">
        <div class="panel-heading">
            <span style="font-weight: bold">ROLES</span>
        </div>

        {{-- show errors and messages --}}
        @if (session('status'))
            <div class="alert alert-success">
                @foreach (session('status') as $statusMessage)
                    <p>{{ $statusMessage }}</p>
                @endforeach
            </div>
        @endif
        @if(!empty($errors->first()))
            <div class="alert alert-danger">
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <div style="max-width: 100%; overflow: auto">
            <table class="table table-bordered table-responsive"
                   style="text-align: center; vertical-align: inherit; font-size: small">
                <thead>
                <tr>
                    <th scope="col" style="text-align: center; vertical-align: inherit;">USER_ID</th>
                    <th scope="col" style="text-align: center; vertical-align: inherit;">NAME</th>
                    <th scope="col" style="text-align: center; vertical-align: inherit;">EMAIL</th>
                    <th scope="col" style="text-align: center; vertical-align: inherit;">ROLES</th>
                </tr>
                </thead>
                <tbody style="vertical-align: inherit; color: darkslategray">

                @foreach($users as $editableUser)
                    {{-- Show editable user and his roles --}}
                    @include('dashboard.userRoleRow')
                @endforeach()

                </tbody>
            </table>
        </div>
    </div>

@endsection