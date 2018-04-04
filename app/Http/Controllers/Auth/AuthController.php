<?php

namespace App\Http\Controllers\Auth;

use App\Events\SendNotificationEvent;
use App\Events\UserRegisteredEvent;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\MailingEmail;
use App\Models\Profile;
use App\Models\Resumes\Resume;
use App\Role;
use App\User;
use Auth;
use Carbon\Carbon;
use Event;
use Hash;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Validator;

class AuthController extends BaseController
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;
    protected $redirectPath = '/';

    /**
     * Create a new authentication controller instance.
     *
     */
    public function __construct(Request $request)
    {
	    parent::__construct($request);
        $this->middleware('guest', ['except' => 'getLogout']);
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function getRegister()
    {
        return view('auth.register');
    }

    /**
     *
     * Auth via social networks
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getSocialAuth(Request $request)
    {
	    $social_user = $request->all();
	    $email = array_get($social_user, 'email');
        $user = User::where('email', $email)->first();

	    if (!$user) {
		    return $this->getAjaxResponseView([
			    'status' => [1, null, ['modalWindow' => 'doNotHide']],
			    'views' => ['auth.partials._registerBlock'],
			    'vars' => ['social_user' => $social_user]
		    ]);
        }
	    if(!$user->activated)
	    	$user->update(['activated' => true]);
        Auth::login($user);

        $views = ['auth.partials._authBlock', 'pages.partials._addItemBlock'];
        $vars = [];
	    if(strpos(\URL::previous(), '/resumes')) {
	    	array_push($views, 'partials._allResumesBlock');
		    $vars = array_add($vars, 'resumes', Resume::moderatedFixed()->paginate(env('CONFIG_PAGINATE', 1)));
	    }

	    return $this->getAjaxResponseView([
		    'status'  => [1, 'Пользователь успешно авторизован!', ($request->get('redirect_url') ? ['location' => '/'] : [])],
		    'views' => $views,
		    'vars' => $vars
	    ]);
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function postLogin(Request $request)
    {
        $this->validate($request, [
            $this->loginUsername() => 'required', 'password' => 'required',
        ],[
            'email.required' => 'Поле "ВАШ E-MAIL" обязательно для заполнения',
            'password.required' => 'Поле "ПАРОЛЬ" обязательно для заполнения',
        ]);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        $throttles = $this->isUsingThrottlesLoginsTrait();

        if ($throttles && $this->hasTooManyLoginAttempts($request)) {
            return $this->sendLockoutResponse($request);
        }

        $credentials = $this->getCredentials($request);

        $user = User::where('email', array_get($credentials, 'email'))->first();
        if (!$user) {
	        return $this->ajaxResponse(0, 'Пользователь не найден');
        }
        if (!$user->activated && $user->password) {
            return redirect('auth/login')->with('error', 'Пользователь не активирован! Для активации, перейдите по <a href="' . route('auth.send_activation', ['user_id' => $user->id]) . '">ссылке</a>');
        } elseif(!$user->activated && !$user->password)
	        return redirect('password/email')->with('error', 'Имя пользователя или пароль не совпадают.');

        if (Auth::attempt($credentials, $request->has('remember'))) {
            return $this->handleUserWasAuthenticated($request, $throttles);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        if ($throttles) {
            $this->incrementLoginAttempts($request);
        }

	    return $this->ajaxResponse(0, 'Неверный пароль!');
    }

	protected function handleUserWasAuthenticated(Request $request, $throttles)
	{
		if ($throttles) {
			$this->clearLoginAttempts($request);
		}

		if (method_exists($this, 'authenticated')) {
			return $this->authenticated($request, Auth::user());
		}

		$views = ['auth.partials._authBlock', 'pages.partials._addItemBlock'];
		$vars = [];
		if(strpos(\URL::previous(), '/resumes')) {
			array_push($views, 'partials._allResumesBlock');
			$vars = array_add($vars, 'resumes', Resume::moderatedFixed()->paginate(env('CONFIG_PAGINATE', 1)));
		}

		return $this->getAjaxResponseView([
			'status'  => [1, 'Пользователь успешно авторизован!', ($request->get('redirect_url') ? ['location' => '/'] : [])],
			'views' => $views,
			'vars' => $vars
		]);
	}

    public function sendActivation(Request $request, $user_id)
    {
        $user = User::find($user_id);
        if (!$user) {
            return redirect('auth/login')->with('error', 'Пользователь не найден');
        }
        $user['activation_token'] = $user->activation_token;


        $diff = Carbon::now()->diffInSeconds($user->activation_request_date);

        if ($diff < config('auth.activation_code_limit')) {
            return redirect('auth/login')->with('error', 'Для повтороной активации подождите ' . (config('auth.activation_code_limit') - $diff));
        }

        $user->activation_request_date = Carbon::now();
        $user->save();

        Event::fire(new UserRegisteredEvent($user));

        return redirect('auth/login')->with('success', 'Ссылка с инструкцией по активации отправлена на ' . $user->email);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postRegister(Request $request)
    {
	    $this->validate($request, [
		    'role' => 'required',
	    ],[
		    'role.required' => 'Необходимо выбрать роль: работодатель либо соискатель',
	    ]);

        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }
        $token = str_random();
        $request->merge([
            'activation_token' => $token,
            'password' => Hash::make($request->get('password'))
        ]);

        $request->merge([
            'personal_bill' => $this->generateCode()
        ]);
        $user = User::create($request->all());
	    MailingEmail::create(['user_id' => $user->id, 'email' => $user->email, 'subscribed' => true]);

        $role = Role::whereName($request->get('role'))->first();
        $user->attachRole($role);

        if (!$user->company && $user->hasRoleFix('employers')) {
            Company::create(['user_id' => $user->id, 'title' => $user->name]);
        }
        if (!$user->profile && $user->hasRoleFix('workers')) {
            Profile::create(['user_id' => $user->id]);
        }

        $user['activation_token'] = $token;

        Event::fire(new UserRegisteredEvent($user));

	    return $this->ajaxResponse(1, 'Инструкция по активации аккаунта отправлена на e-mail: ' . $user->email);
    }

	/**
	 * Log the user out of the application.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getLogout()
	{
		Auth::logout();
		return $this->getAjaxResponseView([
			'status'  => [1, 'Выход был успешно осуществлён!', ['location' => '/']],
			'views' => ['auth.partials._authBlock', 'pages.partials._addItemBlock'],
		]);
	}

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
            'role' => 'required|in:workers,employers'
//            'terms_agree' => 'required',
//            'terms_privacy' => 'required'
        ],[
            'name.required' => 'Поле "ИМЯ / НАЗВАНИЕ КОМПАНИИ" обязательно для заполнения',
            'email.required' => 'Поле "ВАШ E-MAIL" обязательно для заполнения',
            'password.required' => 'Поле "ПАРОЛЬ" обязательно для заполнения',
            'email.unique' => 'Такой электронный адрес уже существует.',
            'password.confirmed' => 'Пароли не совпадают'
        ]);
    }

    public function activate(Request $request)
    {
        $token = $request->get('token');
        if (!$token) {
            return redirect()->route('home')->with('error', 'Неверный токен авторизации');
        }

        $user = User::where('activation_token', $token)->first();
        if (!$user) {
            return redirect()->route('home')->with('error', 'Пользователь не найден');
        }
        if ($user->activated) {
            return redirect()->route('home')->with('success', 'Пользователь уже активирован!');
        }

        $user->activated = true;
        $user->activation_token = null;
        $user->save();
        Auth::login($user);

	    $request->merge([
		    'type' => 'greetings',
		    'recipientName' => $user->name,
		    'recipientEmail' => $user->email,
		    'user' => $user,
	    ]);
	    Event::fire(new SendNotificationEvent($request->all()));

	    if($user->hasRoleFix('workers'))
            return redirect()->route('workers.profile.edit')->with('success', 'Ваш аккаунт активирован. Рекомендуем начать пользоваться сайтом с заполнения данных Вашего профиля');
	    else
            return redirect()->route('employers.profile.edit')->with('success', 'Ваш аккаунт активирован! Рекомендуем начать пользоваться сайтом с заполнения данных Вашей компании');
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
}
