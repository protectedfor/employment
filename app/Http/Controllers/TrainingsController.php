<?php

namespace App\Http\Controllers;

use App\Events\SendNotificationEvent;
use App\Models\BillingLog;
use App\Models\Company;
use App\Models\Training;
use App\Models\TrainingCategory;
use App\Models\TrainingResponse;
use App\User;
use Carbon\Carbon;
use Event;
use App\Http\Requests;
use App\Http\Requests\StoreTrainingRequest;
use App\Models\Resumes\City;
use App\Models\Vacancies\Currency;
use App\Models\Vacancies\Scope;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TrainingsController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
	    $trainings = Training::moderated()->searched($request)->paginate(9);

        return view('trainings.index', compact('trainings'));
    }

    /**
     * Show the form for creating a new vacancy.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categoriesInternal = TrainingCategory::activeOrder()->whereLocation('0')->get();
        $categoriesExternal = TrainingCategory::activeOrder()->whereLocation('1')->get();
        $currencies = Currency::activeOrder()->get();
        $cities = City::activeOrder()->get();
	    $company = Company::where('user_id', Auth::id())->first();

        return view('trainings.createEdit', compact('categoriesInternal', 'categoriesExternal', 'currencies', 'cities', 'company'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreVacancyRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTrainingRequest $request)
    {
        $request->merge(['user_id' => Auth::id()]);
        $training = Training::create($request->all());

	    return redirect()->route('trainings.profile', ['modal_show' => $training->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
	    $training = Training::findOrFail($id);
	    if (Auth::check() && $training->user->id != Auth::id() && $training->moderated == 0)
		    throw new NotFoundHttpException;

        $company = $training->user->company;

        return view('trainings.show', compact('training', 'company'));
    }

	public function sendResponse(Request $request, $id)
	{
		$this->validate($request, [
			'name' => 'required',
			'email' => 'required|email',
			'phone' => 'required',
//			'description' => 'required',
		]);

		$training = Training::moderated()->findOrFail($id);

		if(Auth::check()) {
			$resp = TrainingResponse::whereTrainingId($training->id)->whereUserId(Auth::id())->first();
			if ($resp)
				return redirect()->back()->with('error', 'Вы уже подавали заявку на данный тренинг');
			$request->merge(['training_id' => $training->id, 'user_id' => Auth::id()]);}
		else
			$request->merge(['training_id' => $training->id, 'user_id' => '0']);

		$resp = TrainingResponse::create($request->all());

		$request->merge([
			'type' => 'informApplicant',
			'title' => $training->title,
			'recipientName' => $training->coordinator,
			'recipientEmail' => $request->get('email'),
			'senderName' => $request->get('name'),
			'senderEmail' => $request->get('email'),
			'senderPhone' => $request->get('phone'),
			'senderDescription' => $request->get('description')
		]);
		Event::fire(new SendNotificationEvent($request->all()));

		$request->merge([
			'type' => 'training',
			'recipientEmail' => $training->email,
		]);

		Event::fire(new SendNotificationEvent($request->all()));

		return redirect()->back()->with('success', 'Спасибо, заявка на тренинг ' . $training->title . ' успешно подана. Автор тренинга будет проинформирован по e-mail и вскоре свяжется с вами');
	}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
	    $training = Training::findOrFail($id);
	    $categoriesInternal = TrainingCategory::activeOrder()->whereLocation('0')->get();
	    $categoriesExternal = TrainingCategory::activeOrder()->whereLocation('1')->get();
	    $currencies = Currency::activeOrder()->get();
	    $cities = City::activeOrder()->get();

	    return view('trainings.createEdit', compact('training', 'categoriesInternal', 'categoriesExternal', 'currencies', 'cities'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreVacancyRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreTrainingRequest $request, $id)
    {
	    $request->merge(['user_id' => Auth::id()]);
	    $training = Training::findOrFail($id);
	    $training->update($request->all());

	    return redirect()->route('trainings.profile')->with('success', 'Тренинг обновлен!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
	    $training = Training::findOrFail($id);
	    $training->delete();

	    return redirect()->back()->with('success', 'Тренинг удален!');
    }
}
