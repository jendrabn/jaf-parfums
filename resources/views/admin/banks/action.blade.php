<div class="btn-group btn-group-xs"
     role="group">
    {{-- <a class="btn btn-primary"
       href="">
        View
    </a> --}}
    <a class="btn btn-info"
       href="{{ route('admin.banks.edit', $id) }}">
        Edit
    </a>
    <a class="btn btn-danger btn-delete"
       href="{{ route('admin.banks.destroy', $id) }}">
        Delete
    </a>
</div>
