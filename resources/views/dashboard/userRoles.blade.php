@extends('home')

@section('dashboard')

    <div class="panel panel-default">
        <div class="panel-heading">
            <span style="font-weight: bold">ROLES</span>
        </div>
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
                    <tr>
                        <th scope="row"
                            style="text-align: center; vertical-align: inherit;">{{ $editableUser->id }}  </th>
                        <td>{{ $editableUser->name }}</td>
                        <td><a href="mailto:{{ $editableUser->email }}">{{ $editableUser->email }}</a></td>
                        <td style="text-align: left; vertical-align: inherit; font-size: small">
                            <form method="post" action="{{route('dashboard.roles.update', ['user' => $editableUser])}}">
                                {{ method_field('PUT') }}
                                {{ csrf_field() }}
                                <div class="form-group">
                                    @foreach ($roles as $role)
                                        <div class="form-check">
                                            @if((!$editableUser->hasRole('administrator|superadministrator') || Auth::user()->hasRole('superadministrator')) &&
                                            !(Auth::user()->hasRole('moderator') && !Auth::user()->hasRole('administrator|superadministrator')))
                                                <input type="hidden" name="roleId[{{ $role->id }}]" value="">
                                                <input class="form-check-input" type="checkbox"
                                                       name="roleId[{{ $role->id }}]"
                                                       value="1"
                                                       @if($role->disabled || ($role->name === 'administrator' && !Auth::user()->hasRole('superadministrator')))
                                                       disabled="disabled"
                                                       @endif
                                                       @if ($editableUser->hasRole($role->name))
                                                       checked="checked"
                                                        @endif >

                                                @if($role->disabled && $editableUser->hasRole('superadministrator'))
                                                    <input type="hidden" name="roleId[{{ $role->id }}]" value="1">
                                                @endif
                                                <label class="form-check-label" for="defaultCheck2">
                                                    {{ $role->display_name }}
                                                </label>
                                                <small id="emailHelp"
                                                       class="form-text text-muted">{{ $role->description }}
                                                </small>
                                            @elseif($editableUser->hasRole($role->name))
                                                <label class="form-check-label" for="defaultCheck2">
                                                    {{ $role->display_name }}
                                                </label>
                                                <small id="emailHelp"
                                                       class="form-text text-muted">{{ $role->description }}
                                                </small>
                                            @endif
                                        </div>
                                    @endforeach
                                    @if((!$editableUser->hasRole('administrator|superadministrator') || Auth::user()->hasRole('superadministrator')) &&
                                    !(Auth::user()->hasRole('moderator') && !Auth::user()->hasRole('administrator|superadministrator')))
                                        <button type="submit" class="btn btn-primary">Save</button>
                                    @endif
                                </div>
                            </form>
                        </td>
                    </tr>
                @endforeach()
                </tbody>
            </table>
        </div>
    </div>

@endsection