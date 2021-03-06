@extends('layouts.basic')

@section('body')
    <body class="hold-transition login-page">
        <div class="login-box">
            <div class="login-logo">
                <a href="{{ url('/home') }}"><b>{{ env('APP_TITLE') }} </b></a>
            </div>

            <!-- /.login-logo -->
            <div class="login-box-body">
                <p class="login-box-msg">{{ ucfirst(trans('auth.reset-your-password')) }}</p>

                <form method="post" action="{{ url('/password/reset') }}">
                    {!! csrf_field() !!}

                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="form-group has-feedback {{ $errors->has('email') ? ' has-error' : '' }}">
                        <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="{{ ucfirst(trans('common.email')) }}">
                        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                        @if ($errors->has('email'))
                            <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                        @endif
                    </div>

                    <div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }}">
                        <input type="password" class="form-control" name="password" placeholder="{{ ucfirst(trans('common.password')) }}">
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>

                        @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group has-feedback{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                        <input type="password" name="password_confirmation" class="form-control" placeholder="{{ ucfirst(trans('common.confirm')) }} {{ ucfirst(trans('common.password')) }}">
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>

                        @if ($errors->has('password_confirmation'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary pull-right">
                                <i class="fa fa-btn fa-refresh"></i>{{ ucfirst(trans('common.save')) }}
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>

        <script src="{{ asset('js/admin.js') }}"></script>
    </body>
@endsection
