@extends('front.layout.master')
@section('style')

@stop
@section('body')
    <form class="form-signin" method="post" action="{{route('reset.passw')}}">
        <div class="jumbotron">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <h1 class="h3 mb-3 font-weight-normal">Reset Password</h1>

            <div class="form-group">
                <label for="inputEmail" class="sr-only">Email</label>
                <input id="email" type="email" class="form-control" name="email" value="{{ $email }}" readonly>
            </div>

            <div class="form-group">
                <label for="inputPassword" class="sr-only">New Password</label>
                <input type="password" id="inputPassword" class="form-control" name="password" placeholder="New Password" required>
                @if ($errors->has('password'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <label for="password-confirm" class="sr-only">Confirm Password</label>
                <input type="password" id="password-confirm" class="form-control" name="password_confirmation" placeholder="Confirm Password" required>
                @if ($errors->has('password_confirmation'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                    </span>
                @endif
            </div>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Reset Password</button>
        </div>
    </form>
@endsection
