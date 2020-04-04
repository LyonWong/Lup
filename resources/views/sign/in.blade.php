@extends('sign.layout')

@section('content')
<div class="box box-sign-in">
    <div class="box-head flex-center">Sign In</div>
    <div class="box-body">
        <form method="POST">
            <div class="box-row">
                <label for="identity">@lang('account')</label>
                <input id="identity" type="text" name="identity" required/>
            </div>
            <div class="box-row">
                <label for="password">@lang('password')</label>
                <input id="password" type="password" name="password" required/>
            </div>
            @error('identity')
            <div class="box-row hint hint-error">
                {{$message}}
            </div>
            @enderror
            <div class="box-row flex-between">
                <a class="a-sign-vice flex-col" href="/sign-password-forget">@lang('forget password')</a>
                <button class="btn-sign-primary" type="commit">@lang('sign-in')</button>
            </div>
        </form>
    </div>
</div>
@endsection