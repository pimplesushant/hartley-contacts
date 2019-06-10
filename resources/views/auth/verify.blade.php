@extends('layouts.app')
@section('page_title')
    {{ __('Verify Your Email Address') }}
@endsection
@section('content')
    <div class="col-md-12">
        @if (session('resent'))
            <div class="alert alert-success" role="alert">
                {{ __('A fresh verification link has been sent to your email address.') }}
            </div>
        @endif
        <div class="box" style="padding: 100px;">
            <p class="text-center">
                {{ __('Before proceeding, please check your email for a verification link.') }}</br>
                {{ __('If you did not receive the email') }}, <a href="{{ route('verification.resend') }}">{{ __('click here to request another') }}</a>.
            </p> <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
@endsection
