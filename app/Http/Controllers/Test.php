<?php

namespace App\Http\Controllers;

use App\Services\Sign\Manager as Signer;
use App\Services\UserSign;
use CreateUserSignTable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class Test extends Controller
{
    protected $signer;

    public function __construct(Signer $signer)
    {
        $this->middleware('sign');
        $this->signer = $signer;
    }

    public function __invoke()
    {
        echo 'test';
        return Cache::get("code_123");
    }

    public function _POST_()
    {
        echo "test post";
    }

    public function _GET_()
    {
        echo "test get";
    }

    public function _GET_foo()
    {
    }

    public function bar()
    {
        echo "test bar";
    }

    public function user()
    {
        $user = $this->signer->entry()->checkUser();
        print_r($user->id.":".$user->sn);
        // return $user;
    }

    public function log()
    {
        Log::info($this->request->msg);
    }
}
