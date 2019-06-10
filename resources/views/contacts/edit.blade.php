@extends('layouts.admin.admin')
@section('back')
    <a href="{{ route('admin.clinics') }}" class="btnBack"><i class="fa fa-long-arrow-left"></i> Back</a>
@endsection
@section('page_title')
    Manage Clinics
@endsection
@section('content')
    <div class="col-md-12">
        @if(Session::has('message.content'))
            <p class="alert text-center text-{{ Session::get('message.level') }}">{!! Session::get('message.content') !!}</p>
        @endif
        <form role="form" action="{{route('admin.clinics.update')}}" method="POST" id="admin-clinics-add-form">
            <div class="box">
                <div class="profile-information-section">
                    <div class="box-header with-border">
                        <i class="fa fa-hospital-o" aria-hidden="true"></i>
                        <h3 class="box-title">Edit Clinic Information</h3>
                    </div>
                    <!-- /.box-header -->
                    {{ csrf_field() }}
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="body-content">
                                    <div class="">
                                        <div class="form-group">
                                            <label>Country</label>
                                            <select class="form-control" name="country" id="country"
                                                    style="width: 100%;">
                                                <option value="" data-country="'Singapore'" data-code="SG">Select
                                                    Country
                                                </option>
                                                @foreach($countries['data'] as $country)
                                                    <option value="{{$country['id']}}"
                                                            data-country="'{{ $country['name'] }}'"
                                                            data-code="{{ $country['code'] }}"
                                                            @if ($country['id'] == $countries_selected)
                                                            selected="selected"
                                                            @endif
                                                    >{{ $country['name'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <!-- /.form-group -->
                                    </div>

                                    <div class="">
                                        <div class="form-group">
                                            <label for="clinic-name">Clinic Name</label>
                                            <input type="text" name="clinic-name" class="form-control" id="clinic-name"
                                                   placeholder="Enter Clinic Name" value="{{ $clinic['name'] }}">
                                            <input type="hidden" class="form-control" name="_id" id="clinic-id" placeholder="Enter Clinic ID" value="{{ $clinic['id'] }}">
                                        </div>
                                    </div>

                                    <div class="">
                                        <div class="form-group">
                                            <label for="phone-number">Phone Number</label>
                                            <input type="text" name="full-phone-number" class="form-control"
                                                   id="full-phone-number" placeholder="Enter Phone Number" value="{{ $clinic['mobile'] }}">
                                        </div>
                                    </div>

                                    <div class="">
                                        <div class="form-group">
                                            <label for="email-address">Email Address</label>
                                            <input type="text" readonly class="form-control"
                                                   id="email-address" placeholder="Enter Email Address" value="{{ $clinic['email'] }}">
                                        </div>
                                    </div>

                                    <div class="">
                                        <div class="form-group">
                                            <label for="email-address">Tax</label>
                                            <div class="clearfix"></div>
                                            <div class="radio-inline">
                                                <label>
                                                    <input type="radio" name="apply_tax" id="tax_A" value="1" {!! (($tax_selected == '1') ? 'checked="checked"' : '') !!}>
                                                    Apply
                                                </label>
                                            </div>
                                            <div class="radio-inline">
                                                <label>
                                                    <input type="radio" name="apply_tax" id="tax_D" value="0" {!! (($tax_selected == '0') ? 'checked="checked"' : '') !!}>
                                                    Do Not Apply
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="">
                                        <div class="form-group">
                                            <label for="location">Location</label>
                                            <input type="text" name="location" class="form-control" id="location"
                                                   placeholder="Enter Location" value="{{ $clinic['location'] }}">
                                            <input type="hidden" name="lat" class="form-control" id="lat" value="{{ $clinic['lat'] }}">
                                            <input type="hidden" name="lon" class="form-control" id="lon" value="{{ $clinic['long'] }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>

                <div class="map-section">
                    <div class="col-md-12">
                        <div class="body-content">
                            <div id="map-canvas"></div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <hr>

                <div class="images-section">
                    <div class="box-body">
                        <div class="row">
                            <div class="body-content">
                                <div class="clinic-logo-block col-md-12">
                                    <h5>Clinic Logo</h5>
                                    <div class="clinic-logo">
                                        <label class="drop-clinic-logo">
                                            <figure>
                                                <img src="{{ asset($clinic['profile_image']) }}" alt="{{ $clinic['name'] }}" class="gambar img-responsive img-thumbnail"
                                                     id="item-logo-output"/>
                                                <p>Add Clinic Logo</p>
                                            </figure>
                                            <input type="file" name="logo-file" id="logo-file" class="item-logo file center-block"/>
                                            <input type="hidden" name="clinic-logo" id="clinic-logo"/>
                                            <input type="hidden" name="profile-image" id="profile-image" value="{{ $clinic['profile_image'] }}"/>
                                        </label>
                                    </div>
                                </div>
                                <div class="clinic-images-block col-md-12">
                                    <h5>Clinic Images</h5>
                                    <div class="row" id="clinic-images">
                                        @foreach($clinic['clinic_images'] as $key => $value)
                                            <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 clinic-image">
                                                <div class="clinic-image-item">
                                                    <label class="drop-clinic-image">
                                                        <img src="{{ asset($value['image']) }}" class="gambar img-responsive img-thumbnail" style="padding: 5px; height: 140px; width: 280px;" alt="Clinic Image"/>
                                                        <input type="hidden" name="clinic-existing-images[]" value="{{$value['image']}}"/>
                                                    </label>
                                                    <div class="remove-circle remove-icon remove-image">
                                                        <a><i class="fa fa-times"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                                            <div class="clinic-image-item">
                                                <label class="drop-clinic-image">
                                                    <input type="file" class="item-image file center-block"/>
                                                    <p>Add Clinic Image</p>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="btn-wrapper margin-right">
                    <div class="col-md-12 text-right">
                        <a href="{{ route('admin.clinics') }}" class="btn btn-default">Cancel</a>
                        <button class="btn btn-primary btn_submit" type="submit">Save Changes</button>
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
                            Crop Logo</h4>
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
        <div class="modal fade" id="cropImagesPop" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel2">
                            Crop Image</h4>
                    </div>
                    <div class="modal-body">
                        <div id="upload-image" class="center-block"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" id="cropImageBtn" class="btn btn-primary">Crop</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <link rel="stylesheet" href="{{ asset('/css/plugin/croppie.css') }}">
    <script src="http://maps.googleapis.com/maps/api/js?libraries=places&key={!! config('constants.GOOGLE_MAPS_KEY') !!}"
            type="text/javascript"></script>
    <script src="{{ asset('/js/plugin/croppie.js') }}"></script>
    <script src="{{ asset('/js/Admin/common.js') }}"></script>
    <script src="{{asset('/js/plugin/intlTelInput.min.js')}}"></script>
    <script src="{{ asset('/js/Admin/editClinics.js') }}"></script>
@endsection