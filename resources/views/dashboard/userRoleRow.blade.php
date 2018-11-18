{{-- Show editable user and his roles --}}

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

                        @userCanBeModified($editableUser)
                            <input type="hidden" name="roleId[{{ $role->id }}]" value="">
                            <input class="form-check-input" type="checkbox"
                               name="roleId[{{ $role->id }}]"
                               value="1"

                               @disabledRole($role)
                                   disabled="disabled"
                               @enddisabledRole

                               @checkedRole($editableUser, $role)
                                   checked="checked"
                               @endcheckedRole
                            >

                            @superadminHidden($role, $editableUser)
                                <input type="hidden" name="roleId[{{ $role->id }}]" value="1">
                            @endsuperadminHidden

                            <label class="form-check-label" for="defaultCheck2">
                                {{ $role->display_name }}
                            </label>
                            <small class="form-text text-muted">
                                {{ $role->description }}
                            </small>
                        @else
                            @printNotEditableRole($editableUser, $role)
                                <label class="form-check-label" for="defaultCheck2">
                                    {{ $role->display_name }}
                                </label>
                                <small class="form-text text-muted">
                                    {{ $role->description }}
                                </small>
                            @endprintNotEditableRole
                        @enduserCanBeModified

                    </div>
                @endforeach

                @userCanBeModified($editableUser)
                    <button type="submit" class="btn btn-primary">Save</button>
                @enduserCanBeModified

            </div>
        </form>
    </td>
</tr>