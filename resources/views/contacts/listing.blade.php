@extends('layouts.app')
@section('page_title')
    Manage Contacts
@endsection
@section('content')
    <div class="col-md-12">
        @if(Session::has('message.content'))
            <p class="alert text-center text-{{ Session::get('message.level') }}">{!! Session::get('message.content') !!}</p>
        @endif
        <div class="box">
            <div class="box-header with-border">
                <a href="{{route('contacts.create')}}" class="btn btn-sm btn-primary pull-right margin-top-15"><i
                            class="fa fa-plus"></i>&nbsp;&nbsp;Add New Contact</a>
                <form action="{{ route('contacts.export') }}" method="POST" id="export_vcfs"
                      style="display: inline !important;">
                    @csrf
                    <input type="hidden" name="contacts" id="vcfs">
                    <button href="" id="download_selected" class="btn btn-sm btn-info margin-top-15"><i
                                class="fa fa-download"></i>&nbsp;Download</button>
                    </button>
                </form>

            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <!-- Custom Tabs -->
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab">My Contacts</a></li>
                        <li><a href="#tab_2" data-toggle="tab">Shared Contacts</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
                            <div class="table-responsive">
                                <table id="contacts-list-1" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th><input type="checkbox" class="select_all" name="id[]"></th>
                                        <th>Sr. No.</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Contact(s)</th>
                                        <th>Photo</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="tab_2">
                            <div class="table-responsive">
                                <table id="contacts-list-2" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th><input type="checkbox" class="select_all" name="id[]"></th>
                                        <th>Sr. No.</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Contact(s)</th>
                                        <th>Photo</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- /.tab-content -->
                </div>
                <!-- nav-tabs-custom -->
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
    <div id="viewContactDetails" class="modal fade in" role="dialog" aria-hidden="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title">Contact Details of <span class="detail-name"></span></h4>
                </div>
                <div class="modal-body">
                    <div class="box-body box-profile">
                        <span><img class="profile-user-img img img-circle img-responsive" id="detail-image" src="#"
                                   alt=""/></span>

                        <h3 class="profile-username text-center detail-name"></h3>

                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                <b>Email</b> <a class="pull-right" id="detail-email"></a>
                            </li>
                            <li class="list-group-item">
                                <b>Primary Contact</b> <a class="pull-right" id="detail-primary"></a>
                            </li>
                            <li class="list-group-item">
                                <b>Secondary Contact</b> <a class="pull-right" id="detail-secondary"></a>
                            </li>
                        </ul>
                    </div>
                    <!-- /.box-body -->
                </div>
                <div class="modal-footer">
                    <a href="#" data-original-title="Download Contact"
                       class="btn btn-info btn-sm" id="download_contact" data-tt="tooltip" title="Download Contact"
                       data-placement="top">
                        <i class="fa fa-download"> Download</i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div id="shareContactDetails" class="modal fade in" role="dialog" aria-hidden="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title">Share Details of <span class="share-name"></span></h4>
                </div>
                <div class="modal-body">
                    <form action="{{ route('contacts.share') }}" method="POST" id="share_form" style="display: inline !important;">
                        {{ csrf_field() }}
                        <div class="box-body box-profile">
                        <span><img class="profile-user-img img img-circle img-responsive" id="share-image" src="#"
                                   alt=""/></span>

                        <h3 class="profile-username text-center share-name"></h3>

                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                <b>Email</b> <a class="pull-right" id="share-email"></a>
                            </li>
                            <li class="list-group-item">
                                <b>Primary Contact</b> <a class="pull-right" id="share-primary"></a>
                            </li>
                            <li class="list-group-item">
                                <b>Secondary Contact</b> <a class="pull-right" id="share-secondary"></a>
                            </li>
                        </ul>
                        <select name="user_id" id="user_id" required
                                class="form-control">
                            <option value="">Select User</option>
                            @foreach($users as $user)
                                <option value="{{$user->id}}">
                                    {{ preg_replace('!\s+!', ' ', $user->first_name . " " . $user->middle_name . " " . $user->last_name)}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <!-- /.box-body -->
                </div>
                <div class="modal-footer">
                    <input name="contact_id" id="contact_id" type="hidden">
                    <button type="submit" class="btn btn-primary btn-sm share_contact" id="share_now">
                        <i class="fa fa-share"> Share Now</i>
                    </button>
                </div>

                </form>
            </div>
        </div>
    </div>
    <div id="confirmContactDelete" class="modal fade in" role="dialog" aria-hidden="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title">Delete Contact?</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this contact?</p>
                    <!-- /.box-body -->
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm" data-dismiss="modal">No</button>
                    <button class="btn btn-sm" id="delete_it">Yes</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('/js/common.js') }}"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>
    <script src="{{ asset('/js/Contact/manageContacts.js') }}"></script>
@endsection
