<?php

namespace App\Http\Controllers;

use App\Events\SendNotificationEvent;
use App\Models\BillingLog;
use App\Models\Company;
use App\Models\Meta;
use App\Models\Vacancies\Complain;
use App\Models\Vacancies\ComplainVacancy;
use App\Models\Vars;
use App\Models\Widget;
use App\User;
use Carbon\Carbon;
use Event;
use App\Http\Requests;
use App\Http\Requests\StoreVacancyRequest;
use App\Models\Resumes\City;
use App\Models\Resumes\Resume;
use App\Models\Vacancies\Busyness;
use App\Models\Vacancies\Currency;
use App\Models\Vacancies\Education;
use App\Models\Vacancies\Scope;
use App\Models\Vacancies\Vacancy;
use App\Models\Vacancies\VacancyResponse;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class VacanciesController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $scopes = Scope::activeOrder()->get();
	    $cities = City::activeOrder()->get();
        $busynesses = Busyness::activeOrder()->get();
        $vacancies = Vacancy::moderatedFixed()->searched($request)->paginate(env('CONFIG_PAGINATE', 1));

        return view('vacancies.index', compact('scopes', 'cities', 'busynesses', 'vacancies'));
    }

    /**
     * Show the form for creating a new vacancy.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
	    $scopes = Scope::activeOrder()->get();
	    $educations = Education::activeOrder()->get();
	    $busynesses = Busyness::activeOrder()->get();
	    $currencies = Currency::activeOrder()->get();
	    $cities = City::activeOrder()->get();
	    if($request->get('lang') == 2)
		    return view('vacancies.en.createEdit', compact('scopes', 'educations', 'busynesses', 'currencies', 'cities'));
	    else
		    return view('vacancies.createEdit', compact('scopes', 'educations', 'busynesses', 'currencies', 'cities'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreVacancyRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreVacancyRequest $request)
    {
        $request->merge(['user_id' => Auth::id(), 'language_id' => $request->get('lang'), 'in_priority' => Carbon::now()]);
        $vacancy = Vacancy::create($request->all());

	    if ($request->has('preview')) {
		    $vacancy->update(['draft' => true]);
		    return redirect()->route('vacancies.show', $vacancy->id);
	    }

        return redirect()->route('employers.profile.checking')->with('success', 'Вы успешно разместили вакансию. Ваша вакансия будет опубликована после модерации. Успехов!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
	    $vacancy = Vacancy::whereId($id)->first();
	    if ($vacancy->user->id != Auth::id() && $vacancy->moderated == 0)
		    throw new NotFoundHttpException;

        $company = $vacancy->user->company;
        $vacancies = $vacancy->user->vacancies()->where('id', '<>', $vacancy->id)->moderated()->limit(8)->get();
        $similars = Vacancy::whereScopeId($vacancy->scope_id)->whereModerated(true)->where('id', '<>', $vacancy->id)->orderByRaw("RAND()")->take(3)->get();
	    $currentVacancy = $vacancy;

	    $lang = $vacancy->language_id;
	    if($lang == 2)
		    return view('vacancies.en.show', compact('vacancy', 'company', 'vacancies', 'similars', 'lang', 'currentVacancy'));
	    else
            return view('vacancies.show', compact('vacancy', 'company', 'vacancies', 'similars', 'currentVacancy'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
	    $vacancy = Vacancy::findOrFail($id);
	    $scopes = Scope::activeOrder()->get();
	    $educations = Education::activeOrder()->get();
	    $busynesses = Busyness::activeOrder()->get();
	    $currencies = Currency::activeOrder()->get();
	    $cities = City::activeOrder()->get();

	    if($request->get('lang') ? $request->get('lang') == 2 : $vacancy->language_id == 2)
		    return view('vacancies.en.createEdit', compact('vacancy', 'scopes', 'educations', 'busynesses', 'currencies', 'cities'));
	    else
	        return view('vacancies.createEdit', compact('vacancy', 'scopes', 'educations', 'busynesses', 'currencies', 'cities'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreVacancyRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreVacancyRequest $request, $id)
    {
	    $vacancy = Vacancy::findOrFail($id);
	    $request->merge(['user_id' => Auth::id(), 'language_id' => $request->get('lang'), 'draft' => false]);
	    $vacancy->update($request->all());

	    if ($request->has('preview')) {
		    $vacancy->update(['draft' => true]);
		    return redirect()->route('vacancies.show', $vacancy->id);
	    }

	    if($vacancy->moderated)
		    return redirect()->route('employers.profile')->with('success', 'Вакансия обновлена!');
	    else {
		    $vacancy->update(['in_priority' => Carbon::now()]);
		    return redirect()->route('employers.profile.checking')->with('success', 'Вакансия обновлена и отправлена на модерацию!');}
    }

	public function publishDraft($id)
	{
		$vacancy = Vacancy::findOrFail($id);
		$vacancy->update(['draft' => false]);

		if($vacancy->moderated)
			return redirect()->route('employers.profile')->with('success', 'Вакансия обновлена!');
		else {
			$vacancy->update(['in_priority' => Carbon::now()]);
			return redirect()->route('employers.profile.checking')->with('success', 'Вы успешно разместили вакансию. Ваша вакансия будет опубликована после модерации. Успехов!');}
	}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
	    $vacancy = Vacancy::findOrFail($id);
	    $vacancy->delete();

	    return redirect()->back()->with('success', 'Вакансия удалена!');
    }

	public function sendResponse(Request $request)
	{
		$vac = Vacancy::moderated()->findOrFail($request->get('vacancy_id'));
		$res = Resume::notDraft()->findOrFail($request->get('resume_id'));
		$resp = VacancyResponse::whereVacancyId($vac->id)->whereResumeId($res->id)->whereUserId(Auth::id())->first();
		if ($resp)
			return redirect()->back()->with('error', 'Вы уже подавали заявку на данную вакансию');
		$this->validate($request, [
			'vacancy_id' => 'required|exists:vacancies,id',
			'resume_id' => 'required|exists:resumes,id',
			'filename1' => 'required_with:file1|max:255',
			'file1' => 'max:255',
			'filename2' => 'required_with:file2|max:255',
			'file2' => 'max:255',
			'filename3' => 'required_with:file3|max:255',
			'file3' => 'max:255',
		]);

		$request->merge(['user_id' => Auth::id()]);
		$resp = VacancyResponse::create($request->all());

		$request->merge([
			'type' => 'informWorker',
			'title' => $vac->position,
			'recipientName' => $vac->user->name,
			'recipientEmail' => Auth::user()->email,
			'senderName' => Auth::user()->name,
			'data' => [
				'vac'=> $vac,
				'res'=> $res,
			],
		]);
		Event::fire(new SendNotificationEvent($request->all()));

		if ($vac->response_email_notifications) {
			$request->merge([
				'type' => 'vacancy',
				'recipientEmail' => $vac->user->email,
			]);
			Event::fire(new SendNotificationEvent($request->all()));
			return redirect()->back()->with('success', 'Спасибо, ваш отклик на вакансию "' . $vac->position . '" успешно отправлен. Вы всегда можете посмотреть
				все ваши отклики в своем <a href="'. route('workers.profile.vacancy_responses') .'">личном кабинете</a>');
		}

		return redirect()->back()->with('success', 'Спасибо, ваш отклик на вакансию "' . $vac->position . '" успешно отправлен. Вы всегда можете посмотреть
				все ваши отклики в своем <a href="'. route('workers.profile.vacancy_responses') .'">личном кабинете</a>');
	}

	public function destroyResponse(Request $request)
	{
		$vac = Vacancy::findOrFail($request->get('vacancy_id'));
		$res = Resume::findOrFail($request->get('resume_id'));
		$resp = VacancyResponse::whereVacancyId($vac->id)->whereResumeId($res->id)->whereUserId($res->user->id)->first();
		$resp->delete();

		return redirect()->back()->with('success', 'Отклик на вашу вакансию ' . $vac->position . ' успешно удалён');
	}

	public function toFavourites(Request $request, $id)
	{
		$vacancy = Vacancy::findOrFail($id);

		if (Auth::user()->savedVacancies()->where('id', $vacancy->id)->count() == 0) {
			Auth::user()->savedVacancies()->attach($vacancy);
			return redirect()->back()->with('success', 'Вакансия добавлена в Ваши "Сохранённые вакансии" в личном кабинете, которую можете посмотреть позже');}
		elseif (Auth::user()->savedVacancies()->where('id', $vacancy->id)->count() > 0 && $request->get('action') == 'remove') {
			Auth::user()->savedVacancies()->detach($vacancy);
			return redirect()->back()->with('success', 'Вакансия удалена из "Сохранённых вакансий"');}
	}

	public function getPdf(Request $request, $id)
	{
		$vacancy = Vacancy::findOrFail($id);
		$company = $vacancy->user->company;

		if($vacancy->language_id == 2)
			$pdf = \PDF::loadView('vacancies.en.showPdf', compact('vacancy', 'company'));
		else
			$pdf = \PDF::loadView('vacancies.showPdf', compact('vacancy', 'company'));

		return $pdf->download('vacancy_'. $id . '.pdf');
	}

	public function complain(Request $request)
	{
		$vac = Vacancy::moderated()->findOrFail($request->get('vacancy_id'));
		$complain = Complain::findOrFail($request->get('complain_id'));
		$resp = ComplainVacancy::whereVacancyId($vac->id)->whereUserId(Auth::id())->first();
		if ($resp)
			return redirect()->back()->with('error', 'Вы уже жаловались на данную вакансию');
		$this->validate($request, [
			'vacancy_id' => 'required|exists:vacancies,id',
			'complain_id' => 'required|exists:complains,id',
			'description' => 'max:255',
		]);

		$request->merge(['user_id' => Auth::id()]);
		$resp = ComplainVacancy::create($request->all());

		return redirect()->back()->with('success', 'Жалоба отправлена!');
	}
}
