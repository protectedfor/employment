<?php

namespace App\Http\Middleware;

use App\Models\Company;
use App\Models\Resumes\Resume;
use App\Models\Training;
use App\Models\Vacancies\Vacancy;
use App\User;
use Closure;
use Illuminate\Contracts\Auth\Guard;

class ResumesMiddleware
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
		if (!\Auth::user())
			return redirect()->route('auth.login')->with(['error' => 'Пожалуйста, авторизуйтесь как работодатель, чтобы иметь возможность просматривать резюме!',
			'redirect_back' => $request->getUri()]);

		if (\Auth::user()->hasRoleFix('employers'))
			return $next($request);

		$object = $request->segments()[0];
		$identifier = $request->segments()[1];

		$item = [];
		if ($object == 'resumes')
			$item = Resume::findOrFail($identifier);

		if ($item->user_id !== $this->auth->getUser()->id)
			return redirect()->route('home')->with('error', 'Пожалуйста, авторизуйтесь как работодатель, чтобы иметь возможность просматривать чужие резюме!');

		return $next($request);
	}
}
