@extends('sign.layout')

@section('content')
<div class="box box-sign-in">
    <div class="box-head flex-center">Sign In Telephone</div>
    <div class="box-body">
        <form method="POST">
            @if ($number)
            @method('PUT')
            @endif
            <div class="box-row">
                <label for="number">@lang('tele')</label>
                <div class="input flex flex-grow" id="phoneNumber">
                    <select name="region">
                        <option>86</option>
                    </select>
                    <input id="number" class="flex-grow" type="number" name="number" value="{{$number}}" oninput="onModifyTele()" />
                </div>
            </div>
            <div class="box-row">
                <label for="verify">@lang('code')</label>
                <input id="code" type="number" name="code" @isset($code) autofocus @endisset />
                <button type="commit" name="commit" value="prepare" @if($number) disabled @endif>Send</button>
            </div>
            <div class="box-row hint hint-error">
                @foreach ($errors->all() as $error)
                <div>{{$error}}</div>
                @endforeach
                @isset($code)
                <div>Testing code is [ {{$code}} ]</div>
                @endisset($code)
            </div>
            <div class="box-row flex-between">
                <a></a>
                <button class="btn-sign-primary" type="commit" name="commit" value="verify" @empty($code) disabled @endempty>@lang('sign-in')</button>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    btnPrepare = document.querySelector('button[value=prepare]');
    btnVerify = document.querySelector('button[value=verify]');

    function onModifyTele() {
        btnPrepare.disabled = false
        btnVerify.disabled = true
    }
    window.onload = function() {}
</script>
@endsection