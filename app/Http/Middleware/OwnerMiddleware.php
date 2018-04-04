<?php

namespace App\Http\Middleware;

use App\Models\Company;
use App\Models\Resumes\Resume;
use App\Models\Training;
use App\Models\Vacancies\Vacancy;
use App\User;
use Closure;
use Illuminate\Contracts\Auth\Guard;

class OwnerMiddleware
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
		$this->auth = $auth;
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
//		if ($this->auth->getUser()->hasRoleFix('admin'))
//			return $next($request);

		$object = $request->segments()[0];
		$identifier = $request->segments()[1];

		$item = [];
		if ($object == 'vacancies')
			$item = Vacancy::findOrFail($identifier);
		if ($object == 'resumes')
			$item = Resume::findOrFail($identifier);
		if ($object == 'trainings')
			$item = Training::findOrFail($identifier);
		if ($object == 'companies')
			$item = Company::findOrFail($identifier);

		if ($item->user_id !== $this->auth->getUser()->id)
//			abort(403, 'Unauthorized action');
			return redirect()->route('home');

		return $next($request);
	}
}
