<?php

namespace App\Http\Controllers\Auth;

use App\Events\SendNotificationEvent;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Password;
use Session;
use Auth;
use Event;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PasswordController extends BaseController
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

	use ResetsPasswords;

	protected $redirectTo = '/';

	/**
	 * Create a new password controller instance.
	 *
	 * @return void
	 */
	public function __construct(Request $request)
	{
		parent::__construct($request);
		$this->middleware('guest');
	}

	/**
	 * Send a reset link to the given user.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function postEmail(Request $request)
	{
		$this->validate($request, ['email' => 'required|email']);

		$response = Password::sendResetLink($request->only('email'), function (Message $message) {
			$message->subject($this->getEmailSubject());
		});

		switch ($response) {
			case Password::RESET_LINK_SENT:
				return $this->getAjaxResponseView([
					'status'  => [1, "Ссылка с инструкцией по сбросу пароля отправлена на {$request->email}"],
				]);
			case Password::INVALID_USER:
				return $this->getAjaxResponseView([
					'status'  => [0, "Пользователя с такой почтой не существует"],
				]);
		}
	}

	/**
	 * Get the e-mail subject line to be used for the reset link email.
	 *
	 * @return string
	 */
	protected function getEmailSubject()
	{
		return property_exists($this, 'subject') ? $this->subject : 'Восстановление пароля на сайте employment.kg';
	}

	/**
	 * Display the password reset view for the given token.
	 *
	 * @param  string  $token
	 * @return \Illuminate\Http\Response
	 */
	public function getReset($token = null)
	{
		if (is_null($token)) {
			throw new NotFoundHttpException;
		}
		return redirect()->route('home', ['showModal' => true, 'action' => 'reset', 'type' => 'password', 'parameters' => $token]);
	}

	/**
	 * Reset the given user's password.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function postReset(Request $request)
	{
		$this->validate($request, [
			'token' => 'required',
			'email' => 'required|email',
			'password' => 'required|confirmed|min:6',
		]);

		$credentials = $request->only(
			'email', 'password', 'password_confirmation', 'token'
		);

		$current_user = User::where('email', $request->email)->first();
		$user_status = $current_user->activated;

		$response = Password::reset($credentials, function ($user, $password) {
			$this->resetPassword($user, $password);
		});

		switch ($response) {
			case Password::PASSWORD_RESET:
				if($user_status)
					return $this->getAjaxResponseView([
						'status'  => [1, 'Ваш пароль изменён'],
						'views' => ['auth.partials._authBlock'],
					]);
				else {
					$request = collect([
						'type' => 'greetings',
						'recipientName' => $current_user->name,
						'recipientEmail' => $current_user->email,
						'user' => $current_user,
					]);
					Event::fire(new SendNotificationEvent($request->all()));

					if ($current_user->hasRoleFix('workers'))
						return $this->getAjaxResponseView([
							'status' => [1, "Ваш аккаунт активирован. Рекомендуем начать пользоваться сайтом с заполнения данных Вашего профиля",
											['location' => route('workers.profile.edit')]],
						]);
					else
						return $this->getAjaxResponseView([
							'status' => [1, "Ваш аккаунт активирован. Рекомендуем начать пользоваться сайтом с заполнения данных Вашей компании",
								['location' => route('employers.profile.edit')]],
						]);
				}
			default:
				return $this->getAjaxResponseView([
					'status'  => [0, trans($response)],
					'views' => ['auth.partials._authBlock'],
				]);
		}
	}

	/**
	 * Reset the given user's password.
	 *
	 * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
	 * @param  string  $password
	 * @return void
	 */
	protected function resetPassword($user, $password)
	{
		$user->password = bcrypt($password);
		$user->activated = 1;

		$user->save();

		Auth::login($user);
	}
}
