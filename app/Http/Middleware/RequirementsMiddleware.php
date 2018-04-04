<?php

namespace App\Http\Middleware;

use Request;
use Closure;
use Auth;
use Session;
use Illuminate\Contracts\Auth\Guard;

class RequirementsMiddleware
{
	/**
	 * The Guard implementation.
	 *
	 * @var Guard
	 */
	protected $auth;

	/**
	 * Create a new filter instance.
	 *
	 * @param  Guard  $auth
	 * @return void
	 */
	public function __construct(Guard $auth)
	{
//		$this->auth = $auth;
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if(Auth::user() && Request::url() != route('employers.profile.edit') && Request::url() != route('workers.profile.edit') &&
			Request::url() != route('resumes.create')) {
			$role = Auth::user()->roles->first()->name;

			if (($role == 'employers' && !Auth::user()->company->phone) || ($role == 'workers' && !Auth::user()->profile->phone)) {
				$role == 'employers' ? $route = 'employers.profile.edit' : $route = 'workers.profile.edit';
				Session::now('success', 'Пожалуйста, укажите номер телефона в своем профиле в <a href="'. route($route) .'">Личном Кабинете</a>. Это только для внутреннего использования, чтобы Модератор мог связаться с Вами в случае необходимости.');
			}
		}

		return $next($request);
	}
}
