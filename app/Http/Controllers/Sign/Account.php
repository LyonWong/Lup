<?php

namespace App\Http\Controllers\Sign;

use App\Repositories\UserSign as UserSignRepo;

class Account extends _
{

    public function _GET_()
    {
        return view('sign.account');
    }

    public function _POST_()
    {
        $validator = validator($this->request->all(), [
            'identity' => 'required|min:4|max:32'
        ]);
        $validator->after(function ($validator) {
            $validated = $validator->validated();
            if ($this->userSignService->match(
                UserSignRepo::TYPE_ACCOUNT,
                $validated['identity']
            )) {
                $validator->errors()->add(
                    "identity",
                    __('sign.account-duplicate')
                );
            }
        });
        return view('sign.password', $validator->validate());
    }

    public function _PUT_()
    {
        $userSignRepo = $this->userSignService->register(
            UserSignRepo::TYPE_ACCOUNT,
            $this->request->identity
        );
        $this->userSignService->passwordModify(
            $userSignRepo->user_id,
            $this->request->password
        );
        return redirect('/sign-in');
    }
}
