<?php

namespace App\Http\Controllers\Sign;

use App\Exceptions\SignException;
use App\Repositories\User;
use App\Repositories\UserSign as UserSignRepo;
use App\Services\Sign\Manager;
use App\Services\Sign\User as SignUser;
use App\Services\UserSign;
use Illuminate\Support\Facades\Log;

class Telephone extends _
{
    protected $gate;

    /**
     * @var $UserSign
     */
    protected $userSign;

    protected $manager;

    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
    }

    public function _GET_()
    {
        return view('sign.telephone', [
            'region' => session('region', '86'),
            'number' => session('number')
        ]);
    }

    public function _POST_()
    {
        $validator = validator($this->request->all(), [
            'region' => 'required|integer',
            'number' => 'required|integer',
        ]);
        $this->request->session()->flash('number', $this->request->number);
        $phoneNumber = $this->request->region . '-' . $this->request->number;
        try {
            $validated = $validator->validate();
            $phoneNumber = "$validated[region]-$validated[number]";
            $code = $this->manager->access('telephone')->prepare($phoneNumber);
            return view('sign.telephone', $validated + ['code' => $code]);
        } catch (SignException $e) {
            $this->request->session()->flash('region', $this->request->region);
            $this->request->session()->flash('number', $this->request->number);
            $validator->after(function ($validator) use ($e) {
                $validator->errors()->add(
                    'code',
                    __($e->getMessage())
                );
            });
            return view('sign.telephone',  $validator->validate());
        }
    }

    /**
     * request:
     *   - region
     *   - number
     *   - code
     */
    public function _PUT_()
    {
        $validator = validator($this->request->all(), [
            'region' => 'bail|required|integer',
            'number' => 'bail|required|integer',
        ]);
        $validated = $validator->validate();
        $phoneNumber = $this->request->region . '-' . $this->request->number;
        $credentials = [
            'phoneNumber' => $phoneNumber,
            'code' => $this->request->code
        ];
        if ($user = $this->manager->access('telephone')->verify($credentials)) {
            $res = $this->manager->entry()->checkIn($user);
            return redirect('/test-user');
        } else {
            $validator->after(function ($validator) {
                $validator->errors()->add(
                    'code',
                    __("Invalid verfification code.")
                );
            });
            return view('sign.telephone', $validator->validate());
        }
    }
}
