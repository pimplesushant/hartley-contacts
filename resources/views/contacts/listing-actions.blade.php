<button data-original-title="View Contact"
        class="btn btn-default btn-sm view_contact" data-tt="tooltip" title="View Contact" data-placement="top">
    <i class="fa fa-eye"></i>
</button>
<a href="{{route('contacts.show', ['id'=>$id])}}" data-original-title="Download Contact"
   class="btn btn-info btn-sm" data-tt="tooltip" title="Download Contact" data-placement="top">
    <i class="fa fa-download"></i>
</a>
@if(!$shared)
    <a href="{{route('contacts.edit', ['id'=>$id])}}" data-original-title="Edit Contact"
       class="btn btn-warning btn-sm" data-tt="tooltip" title="Edit Contact" data-placement="top">
        <i class="fa fa-pencil"></i>
    </a>
    
    <button data-original-title="Share Contact" class="btn btn-primary btn-sm share_contact" data-id="{{$id}}"
            data-tt="tooltip" title="Share Contact" data-placement="top">
        <i class="fa fa-share"></i>
    </button>

    <form action="{{ route('contacts.destroy', ['id'=>$id]) }}" method="POST" id="delete{{$id}}"
          style="display: inline !important;">
        @method('DELETE')
        @csrf
        <button data-original-title="Delete Contact" class="btn btn-danger btn-sm delete_contact" data-id="{{$id}}"
                data-tt="tooltip" title="Delete Contact" data-placement="top">
            <i class="fa fa-trash"></i>
        </button>
    </form>
@endif