<?php

namespace App\Http\Middleware;

use App\Exceptions\SignException;
use App\Services\Sign\Manager;
use Closure;

class Sign
{
    protected $manager;

    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $entry=null)
    {
        if ($this->manager->entry($entry)->check()) {
            return $next($request);
        } else if ($redirect = $this->manager->config('redirect')) {
            return redirect($redirect);
        }
        throw new SignException("Illegal sign.");
    }
}
