@isset($edit)
    <a href="{{ $edit }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> Edit</a>
@endisset

@isset($destroy)
    <a class="btn btn-sm btn-danger" onclick="Delete(this.id)" id="{{ $destroy }}"><i class="bi bi-trash"></i> Delete</a>
@endisset

@isset($access)
    <a href="{{ $access }}" class="btn btn-sm btn-success"><i class="bi bi-lock"></i> Access</a>
@endisset

@isset($password)
    <a href="{{ $password }}" class="btn btn-sm btn-success"><i class="bi bi-lock"></i> Password</a>
@endisset