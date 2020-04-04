@extends('sign.layout')

@section('content')
<div class="box">
    <div class="box-head align-center">Sign Up</div>
    <div class="box-body">
        <form method="POST">
            <div class="box-row">
                <label for="identity">@lang('account')</label>
                <input id="identity" type="text" name="identity" />
                <button class="btn-sign-primary" type="commit">@lang('next')</button>
            </div>
        </form>
        @error('identity')
        <div class="box-row hint hint-error">
            {{$message}}
        </div>
        @enderror
    </div>
    <div class="box-foot">
    </div>
</div>
@endsection