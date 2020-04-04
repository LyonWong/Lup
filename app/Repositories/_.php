<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

class _ extends Model
{
    public static function const($name)
    {
        $const = static::class.'::'.strtoupper($name);
        if (defined($const)) {
            return constant($const);
        } else {
            return null;
        }
    }
    public function match($operator='=')
    {
        $where = [];
        foreach ($this->attributes as $key => $val) {
            $where[] = [$key, $operator, $val];
        } 
        return self::where($where)->first();
    }
}
