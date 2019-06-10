@extends('layouts.app')

@section('page_title')
    Add Contact
@endsection
<style>
    input[type="file"] {
        visibility: hidden;
    }
</style>
@section('content')
    <div class="col-md-12">
        @if(Session::has('message.content'))
            <p class="alert text-center text-{{ Session::get('message.level') }}">{!! Session::get('message.content') !!}</p>
        @endif
        <form role="form" action="{{route('contacts.store')}}" method="POST" id="contact-add-form">
            <div class="box">
                {{ csrf_field() }}
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="body-content">
                                <div class="form-group {{ $errors->has('first_name') ? ' has-error' : '' }}">
                                    <label for="first_name">First Name</label>
                                    <input type="text" class="form-control" name="first_name" value="{{ old('first_name') }}" placeholder="First Name">

                                    @if ($errors->has('first_name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('first_name') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group {{ $errors->has('first_name') ? ' has-error' : '' }} ">
                                    <label for="middle_name">Middle Name</label>
                                    <input type="text" class="form-control" name="middle_name" value="{{ old('middle_name') }}" placeholder="Middle Name">

                                    @if ($errors->has('middle_name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('middle_name') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group {{ $errors->has('first_name') ? ' has-error' : '' }}">
                                    <label for="last_name">Last Name</label>
                                    <input type="text" class="form-control" name="last_name" value="{{ old('last_name') }}" placeholder="Last Name">

                                    @if ($errors->has('last_name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('last_name') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group {{ $errors->has('first_name') ? ' has-error' : '' }}">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email">

                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group {{ $errors->has('first_name') ? ' has-error' : '' }}">
                                    <label for="primary_phone">Primary Phone</label>
                                    <input type="text" class="form-control" name="primary_phone" value="{{ old('primary_phone') }}" placeholder="Primary Phone">

                                    @if ($errors->has('primary_phone'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('primary_phone') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group {{ $errors->has('first_name') ? ' has-error' : '' }}">
                                    <label for="secondary_phone">Secondary Phone</label>
                                    <input type="text" class="form-control" name="secondary_phone" value="{{ old('secondary_phone') }}" placeholder="Secondary Phone">

                                    @if ($errors->has('secondary_phone'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('secondary_phone') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group {{ $errors->has('photo') ? ' has-error' : '' }}">
                                <label for="email">Photo</label>
                                <div class="photo-block col-md-12">
                                    <div class="photo">
                                        <label class="drop-photo">
                                            <figure>
                                                <img src="" alt="Contact Photo" class="gambar img-responsive img-thumbnail"
                                                     id="item-logo-output"/>
                                            </figure>
                                            <input type="file" name="logo-file" class="item-logo file center-block"/>
                                            <input type="hidden" name="photo" id="photo"/>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box-footer">
                    <div class="col-md-12 text-right">
                        <a href="{{ route('contacts.index') }}" class="btn btn-default">Cancel</a>
                        <button class="btn btn-primary btn_submit" type="submit">Add</button>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </form>

        <div class="modal fade" id="cropLogoPop" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel1">
                            Crop Photo</h4>
                    </div>
                    <div class="modal-body">
                        <div id="upload-logo" class="center-block"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" id="cropLogoBtn" class="btn btn-primary">Crop</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.3.0/croppie.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.3.0/croppie.min.js"></script>
    <script src="{{ asset('/js/common.js') }}"></script>
    <script src="{{ asset('/js/Contact/addContacts.js') }}"></script>
@endsection