<button data-original-title="View Contact"
   class="btn btn-default btn-sm view_contact" data-tt="tooltip" title="View Contact" data-placement="top">
    <i class="fa fa-eye"></i>
</button>
<a href="{{route('contacts.edit', ['id'=>$id])}}" data-original-title="Edit Contact"
   class="btn btn-default btn-sm" data-tt="tooltip" title="Edit Contact" data-placement="top">
    <i class="fa fa-pencil"></i>
</a>
<a href="{{route('contacts.share', ['id'=>$id])}}" data-original-title="Share Contact" class="btn btn-default btn-sm"
   data-tt="tooltip" title="Share Contact" data-placement="top">
    <i class="fa fa-share"></i>
</a>

<form action="{{ route('contacts.destroy', ['id'=>$id]) }}" method="POST" id="delete{{$id}}" style="display: inline !important;">
    @method('DELETE')
    @csrf
    <button data-original-title="Delete Contact" class="btn btn-default btn-sm delete_contact" data-id="{{$id}}"
            data-tt="tooltip" title="Delete Contact" data-placement="top">
        <i class="fa fa-trash"></i>
    </button>
</form>