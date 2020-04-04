@extends('sign.layout')

@section('content')
<div class="box box-sign-user">
    <div class="box-head align-center">User</div>
    <div class="box-body">
        <table class="box-table">
            <tr>
                <td>SN</td>
                <td>{{$user->sn}}</td>
            </tr>
            <tr>
                <td>TS</td>
                <td>{{$user->ts}}</td>
            </tr>
            <tr>
                <th colspan="2">Sign</th>
            </tr>
            @foreach ($signs as $sign)
            <tr>
                <td>{{$sign['type']}}</td>
                <td>{{$sign['code']}}</td>
            </tr>
            @endforeach
            <tr>
                <th colspan="2">Info</th>
            </tr>
            @foreach ($infos as $info)
            <tr>
                <td>{{$info['item']}}</td>
                <td>{{$info['data']}}</td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection