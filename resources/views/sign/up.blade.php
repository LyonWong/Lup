@extends('sign.layout')

@section('content')
<div class="box">
    <div class="box-head align-center">Sign Up Account</div>
    <div class="box-body">
        <form method="POST">
            <div class="box-row">
                <label>账号</label>
                <input type="text" name="identity" placeholder="账号/邮箱/电话" />
            </div>
            <div class="box-row">
                <label>密码</label>
                <input type="password" name="password" />
            </div>
            <div class="box-row flex-between">
                <a class="a-sign-vice" href="/sign-password-forget">忘记密码</a>
                <button class="btn-sign-primary" type="commit">登录</button>
            </div>
        </form>
    </div>
</div>
@endsection