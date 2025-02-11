<td>
    <a href="{{ route('posts.show', $model->id) }}" class="btn btn-outline-success btn-sm">
        <i class="fa fa-eye"></i>
    </a>

    <a href="{{ route('posts.edit', $model->id) }}" class="btn btn-outline-primary btn-sm">
      <i class="fa fa-pencil-alt"></i>
    </a>

    <form action="{{ route('posts.destroy', $model->id) }}" method="post" class="d-inline" onsubmit="return confirm('Are you sure to delete this record?')">
        @csrf
        @method('delete')

        <button class="btn btn-outline-danger btn-sm">
          <i class="ace-icon fa fa-trash-alt"></i>
        </button>
    </form>
</td>
