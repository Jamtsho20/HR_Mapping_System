<div class="col-md-4">
    <div class="card">
        <div class="card-body p-0">
            <table id="user-roles" class="table table-bordered table-striped table-condensed table-sm">
                <thead class="thead-light">
                    <tr>
                        <th>Role *</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $role)
                        <tr>
                            <td>
                                <label class="css-control css-checkbox">
                                    <input type="checkbox" class="css-control-input" value="{{ $role->id }}" name="roles[]" @if (isset($rolesAssigned)){{ in_array($role->id, $rolesAssigned) ? 'checked' : ''}} @endif>
                                    <span class="css-control-indicator"></span> {{ $role->name }}
                                </label>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>