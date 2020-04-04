<?php

namespace App\Http\Traits;

use Illuminate\Http\Request;

trait ActionTrait 
{
    protected $request;
    
    public function __invoke(Request $request, ...$args)
    {
        $this->request = $request;
        $action = '_' . $request->method() . '_' . $request->action;
        return $this->$action(...$args);
    }
}