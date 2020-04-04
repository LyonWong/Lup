<?php

namespace App\Services\Sign\Guards;

use App\Exceptions\SignException;
use App\Services\Sign\Storages\Storage;
use App\Services\Sign\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class TokenGuard implements Guard
{
    protected $storage;

    protected $request;

    /**
     * fields:
     *   - life_time
     *   - input_name
     *   - token_length
     *   - storage_prefix
     */
    protected $conf;

    protected $LIFE_TIME = SECONDS_DAY*7;

    protected $INPUT_NAME = 'sign-token';

    protected $TOKEN_LENGTH = 32;

    protected $STORAGE_PREFIX = 'sign_';


    private $token;

    /**
     * @var User
     */
    private $user;

    public function __construct(
        Storage $storage,
        Request $request,
        array $conf
    ) {
        $this->storage = $storage;
        $this->request = $request;
        $this->conf = $conf;
        foreach ($conf as $key => $val) {
            $KEY = strtoupper($key);
            $this->$KEY = $val;
        }
        $this->storage->prefix = $this->STORAGE_PREFIX;
    }

    public function check()
    {
        $this->token = $this->request->header("x-$this->INPUT_NAME") ?? $this->request->cookie($this->INPUT_NAME);
        if (!$this->user = $this->storage->get($this->token)) {
            return false;
        }
        if ($this->user->stamp != $this->stamp($this->user)) {
            return false;
        }
        return true;
    }


    public function checkIn(User $user)
    {
        $attempts = 10;
        while ($attempts--) {
            $token = $user->sn . '_' . Str::random($this->TOKEN_LENGTH);
            $user->stamp = $this->stamp($user);
            if (!$this->storage->exists($token)) {
                $this->storage->set($token, $user, $this->LIFE_TIME);
                Cookie::queue($this->INPUT_NAME, $token, $this->LIFE_TIME);
                return $token;
            }
            throw new SignException("Faild to generate token.");
        }
    }

    public function checkOut()
    {
        $this->user = null;
        return $this->storage->delete($this->token);
    }

    public function checkUser(): User
    {
        if (!$this->user) {
            $this->check();
        }
        return $this->user;
    }

    public function flush()
    {
        if ($this->user) {
            $this->user->flush();
        }
    }

    protected function stamp(User $user)
    {
        $stamps = [
            $user->salt()
        ];
        foreach ($this->conf['stamps'] as $field) {
            $stamps[] = $this->request->$field();
        }
        return crc32(serialize($stamps));
    }
}
