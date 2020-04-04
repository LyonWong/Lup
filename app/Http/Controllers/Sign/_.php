<?php

namespace App\Http\Controllers\Sign;

use App\Exceptions\SignException;
use App\Http\Controllers\Controller;
use App\Http\Traits\ActionTrait;
use App\Services\Sign\Manager;
use App\Services\Sign\User;
use App\Services\UserSign as UserSignService;
use App\Repositories\UserSign as UserSignRepo;

class _ extends Controller
{
    //
    use ActionTrait;

    protected $userSignService;

    protected $manager;


    public function __construct(UserSignService $userSignService, Manager $manager)
    {
        $this->userSignService = $userSignService;
        $this->manager = $manager;
    }

    public function _GET_in()
    {
        return view('sign.in');
    }

    public function POST_in()
    {
        $validator = validator($this->request->all(), [
            'identity' => 'required',
            'password' => 'required',
        ]);
        $res = $this->userSignService->passwordVerify(
            $this->request->identity,
            $this->request->password
        );
        if ($res) {
            $checkUser = new User($this->userSignService->repo->user);
            $this->manger->entry()->checkIn($checkUser);
            return redirect('/test-user');
        } else {
            $validator->after(function ($validator) {
                $validator->errors()->add(
                    'identity',
                    __("Account or password error.")
                );
            });
            return view('sign.in', $validator->validate());
        }
    }

    public function _POST_in()
    {
        $validator = validator($this->request->all(), [
            'identity' => 'required',
            'password' => 'required',
        ]);
        if ($user = $this->manager->access('password')->verify($validator->validated())) {
            $this->manager->entry()->checkIn($user);
            return redirect('/test-user');
        } else {
            $validator->after(function ($validator) {
                $validator->errors()->add(
                    'identity',
                    __("Account or password error.")
                );
            });
            return view('sign.in', $validator->validate());
        }
    }

    public function _GET_up()
    {
        return view('sign.up');
    }

    public function _POST_up()
    {
        try {
            $userSignRepo = $this->userSignService->register(
                UserSignRepo::TYPE_ACCOUNT,
                $this->request->account
            );
            $this->userSignService->passwordModify(
                $userSignRepo->user_id,
                $this->request->password
            );
        } catch (SignException $e) {
            return $e->getMessage();
        }
    }
}
