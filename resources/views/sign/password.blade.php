@extends('sign.layout')

@section('content')
<div class="box">
    <div class="box-head align-center">Set Password</div>
    <div class="box-body">
        <form method="POST">
            <input type="hidden" name="_method" value="PUT"/>
            <div class="box-row">
                <label for="identity">@lang('account')</label>
                <input id="identity" type="text" name="identity" value="{{$identity}}" readonly/>
            </div>
            <div class="box-row">
                <label for="password">@lang('password')</label>
                <input id="password" type="password" name="password"/>
            </div>
            <div class="box-row flex-between">
                <a class="a-sign-vice">@lang('previous')</a>
                <button class="btn-sign-primary" type="commit">@lang('commit')</button>
            </div>
        </form>
    </div>
    <div class="box-foot">
        <div class="hint hint-error">
            @error('identity')
                {{$message}}
            @enderror
        </div>
    </div>
</div>
@endsection