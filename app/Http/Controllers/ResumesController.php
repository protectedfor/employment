<?php

namespace App\Http\Controllers;

use App\Events\SendNotificationEvent;
use App\Http\Requests;
use App\Http\Requests\StoreResumeRequest;
use App\Models\BillingLog;
use App\Models\Company;
use App\Models\Profile;
use App\Models\Resumes\Citizenship;
use App\Models\Resumes\City;
use App\Models\Resumes\ComplainResume;
use App\Models\Resumes\ExtraInstitutions;
use App\Models\Resumes\Institution;
use App\Models\Resumes\Language;
use App\Models\Resumes\LanguageProficiency;
use App\Models\Resumes\Resume;
use App\Models\Resumes\ResumeLanguage;
use App\Models\Resumes\ResumeResponse;
use App\Models\Resumes\WorkExperience;
use App\Models\Vacancies\Busyness;
use App\Models\Vacancies\Complain;
use App\Models\Vacancies\Currency;
use App\Models\Vacancies\Education;
use App\Models\Vacancies\Scope;
use App\Models\Vacancies\Vacancy;
use Auth;
use Event;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ResumesController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $scopes = Scope::activeOrder()->get();
        $cities = City::activeOrder()->get();
        $resumes = Resume::moderatedFixed()->searched($request)->paginate(env('CONFIG_PAGINATE', 1));
        return view('resumes.index', compact('scopes', 'cities', 'resumes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $citizenships = Citizenship::activeOrder()->get();
        $cities = City::activeOrder()->get();
        $filteredCities = City::activeOrder()->where('used_for', null)->get();
        $currencies = Currency::activeOrder()->get();
        $scopes = Scope::activeOrder()->get();
        $busynesses = Busyness::activeOrder()->get();
        $educations = Education::activeOrder()->get();
        $languages = Language::activeOrder()->get();
        $language_proficiencies = LanguageProficiency::activeOrder()->get();
	    $profile = Profile::where('user_id', Auth::id())->first();

	    if($request->get('lang') == 2)
		    return view('resumes.en.createEdit', compact(
			    'citizenships', 'cities', 'filteredCities', 'currencies',
			    'scopes', 'busynesses', 'educations',
			    'languages', 'language_proficiencies', 'profile'
		    ));
	    else
	        return view('resumes.createEdit', compact(
	            'citizenships', 'cities', 'filteredCities', 'currencies',
	            'scopes', 'busynesses', 'educations',
	            'languages', 'language_proficiencies', 'profile'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreResumeRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreResumeRequest $request)
    {
        $institutions = [];
	    $extraInstitutions = [];
        $languages = [];
        $experiences = [];
	    $worker_languages = $request->get("language_id");
        $request->merge(['user_id' => Auth::id(), 'language_id' => $request->get('lang')]);
        $resume = Resume::create($request->all());

        foreach ($request->get('institutions') as $key => $value) {
            $institutions[] = Institution::create([
                'resume_id' => $resume->id,
                'education_id' => array_get($request->get("education_id"), $key),
                'institution' => array_get($request->get("institution"), $key),
                'department' => array_get($request->get("department"), $key),
                'specialty' => array_get($request->get("specialty"), $key),
                'year_of_ending' => array_get($request->get("year_of_ending"), $key),
            ]);
        }
        $resume->institutions()->saveMany($institutions);
        if(head($request->get('extra_inst_title')) || head($request->get('extra_inst_organizer')) ||
           head($request->get('extra_inst_date')) || head($request->get('extra_inst_location'))) {
	        foreach ($request->get('extraInstitutions') as $key => $value) {
		        $extraInstitutions[] = ExtraInstitutions::create([
			        'resume_id' => $resume->id,
			        'extra_inst_title' => array_get($request->get("extra_inst_title"), $key),
			        'extra_inst_organizer' => array_get($request->get("extra_inst_organizer"), $key),
			        'extra_inst_date' => array_get($request->get("extra_inst_date"), $key),
			        'extra_inst_location' => array_get($request->get("extra_inst_location"), $key),
		        ]);
	        }
	        $resume->extraInstitutions()->saveMany($extraInstitutions);
        }
        foreach ($request->get('languages') as $key => $value) {
            $languages[] = ResumeLanguage::create([
                'resume_id' => $resume->id,
                'language_id' => array_get($worker_languages, $key),
                'language_proficiency_id' => array_get($request->get("language_proficiency_id"), $key),
            ]);
        }
        $resume->resumeLanguages()->saveMany($languages);

        foreach ($request->get('work_experiences') as $key => $value) {
            $experiences[] = WorkExperience::create([
                'resume_id' => $resume->id,
                'position' => array_get($request->get("position"), $key),
                'organization' => array_get($request->get("organization"), $key),
                'scope' => array_get($request->get("scope"), $key),
                'exp_scope_id' => array_get($request->get("exp_scope_id"), $key),
                'exp_city_id' => array_get($request->get("exp_city_id"), $key),
                'exp_org_site' => array_get($request->get("exp_org_site"), $key),
                'exp_start_work' => array_get($request->get("exp_start_work"), $key),
                'exp_is_working' => array_get($request->get("exp_is_working"), $key),
                'exp_end_work' => array_get($request->get("exp_end_work"), $key),
                'exp_achievements' => array_get($request->get("exp_achievements"), $key),
            ]);
        }
        $resume->workExperiences()->saveMany($experiences);

	    if(!Auth::user()->profile->name)
		    Auth::user()->profile->update($request->all());

	    if ($request->has('preview')) {
		    $resume->update(['draft' => true]);
		    return redirect()->route('resumes.show', $resume->id);
	    }

	    return redirect()->route('workers.profile.checking')->with('success', 'Вы успешно разместили резюме. Ваше резюме будет опубликовано после модерации. Успехов!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
	    $resume = Resume::with('resumeLanguages.language', 'resumeLanguages.languageProficiency', 'workExperiences.jobField',
		    'workExperiences.city', 'workExperiences.resume')->findOrFail($id);
//	    if ($resume->user->id != Auth::id() && $resume->moderated == 0)
//		    throw new NotFoundHttpException;
	    $checkUserIdForOffers = $resume->offers->sortByDesc('created_at')->load('vacancy')->where('vacancy.user_id', Auth::id())->first();
	    $accessToContacts = (Auth::check() && Auth::id() == $resume->user_id) || (isset(Auth::user()->company) && Auth::user()->company->get_contacts) ||
		    ($checkUserIdForOffers && $checkUserIdForOffers->created_at >= \Carbon\Carbon::now()->subMonth());

	    $listedVacancies = false;
	    if(Auth::user()) {
		    $listedVacancies = Auth::user()->vacancies->map(function ($item) {
			    if (($item->expires_at && $item->expires_at < \Carbon\Carbon::now()) || (!$item->expires_at && $item->created_at->addDays(30) < \Carbon\Carbon::now()))
				    $item['newPosition'] = $item['position'] . ' (Истёк срок подачи откликов)';
			    return $item;
		    });
	    }

	    $similars = Resume::whereHas('scope', function ($query) use ($resume) {
		    $query->where('scope_id', $resume->scope_id);
	    })->orderByRaw("RAND()")->moderated()->get()->except($resume->id)->take(5);

	    $lang = $resume->language_id;
	    if($lang == 2)
		    return view('resumes.en.show', compact('resume', 'accessToContacts', 'similars', 'lang', 'listedVacancies'));
	    else
	        return view('resumes.show', compact('resume', 'accessToContacts', 'similars', 'listedVacancies'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
	    $resume = Resume::findOrFail($id);
	    $citizenships = Citizenship::activeOrder()->get();
	    $cities = City::activeOrder()->get();
	    $filteredCities = City::activeOrder()->where('used_for', null)->get();
	    $currencies = Currency::activeOrder()->get();
	    $scopes = Scope::activeOrder()->get();
	    $busynesses = Busyness::activeOrder()->get();
	    $educations = Education::activeOrder()->get();
	    $languages = Language::activeOrder()->get();
	    $language_proficiencies = LanguageProficiency::activeOrder()->get();
	    $profile = Profile::where('user_id', Auth::id())->first();

	    if($request->get('lang') ? $request->get('lang') == 2 : $resume->language_id == 2)
		    return view('resumes.en.createEdit', compact(
			    'resume', 'citizenships', 'cities', 'filteredCities', 'currencies',
			    'scopes', 'busynesses', 'educations',
			    'languages', 'language_proficiencies', 'profile'
		    ));
	    else
		    return view('resumes.createEdit', compact(
			    'resume', 'citizenships', 'cities', 'filteredCities', 'currencies',
			    'scopes', 'busynesses', 'educations',
			    'languages', 'language_proficiencies', 'profile'
		    ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreResumeRequest $request, $id)
    {
	    $resume = Resume::findOrFail($id);
	    $institutions = [];
	    $extraInstitutions = [];
	    $languages = [];
	    $experiences = [];
	    $worker_languages = $request->get("language_id");
	    $request->merge(['user_id' => Auth::id(), 'language_id' => $request->get('lang'), 'draft' => false]);
	    $resume->update($request->all());

	    $resume->institutions()->delete();
	    foreach ($request->get('institutions') as $key => $value) {
		    $institutions[] = Institution::create([
			    'resume_id' => $resume->id,
			    'education_id' => array_get($request->get("education_id"), $key),
			    'institution' => array_get($request->get("institution"), $key),
			    'department' => array_get($request->get("department"), $key),
			    'specialty' => array_get($request->get("specialty"), $key),
			    'year_of_ending' => array_get($request->get("year_of_ending"), $key),
		    ]);
	    }
	    $resume->institutions()->saveMany($institutions);

	    $resume->extraInstitutions()->delete();
	    if(head($request->get('extra_inst_title')) || head($request->get('extra_inst_organizer')) ||
	       head($request->get('extra_inst_date')) || head($request->get('extra_inst_location'))) {
		    foreach ( $request->get( 'extraInstitutions' ) as $key => $value ) {
			    $extraInstitutions[] = ExtraInstitutions::create( [
				    'resume_id'            => $resume->id,
				    'extra_inst_title'     => array_get( $request->get( "extra_inst_title" ), $key ),
				    'extra_inst_organizer' => array_get( $request->get( "extra_inst_organizer" ), $key ),
				    'extra_inst_date'      => array_get( $request->get( "extra_inst_date" ), $key ),
				    'extra_inst_location'  => array_get( $request->get( "extra_inst_location" ), $key ),
			    ] );
		    }
		    $resume->extraInstitutions()->saveMany( $extraInstitutions );
	    }

	    $resume->resumeLanguages()->delete();
	    foreach ($request->get('languages') as $key => $value) {
		    $languages[] = ResumeLanguage::create([
			    'resume_id' => $resume->id,
			    'language_id' => array_get($worker_languages, $key),
			    'language_proficiency_id' => array_get($request->get("language_proficiency_id"), $key),
		    ]);
	    }
	    $resume->resumeLanguages()->saveMany($languages);

	    $resume->workExperiences()->delete();
	    foreach ($request->get('work_experiences') as $key => $value) {
		    $experiences[] = WorkExperience::create([
			    'resume_id' => $resume->id,
			    'position' => array_get($request->get("position"), $key),
			    'organization' => array_get($request->get("organization"), $key),
			    'scope' => array_get($request->get("scope"), $key),
			    'exp_scope_id' => array_get($request->get("exp_scope_id"), $key),
			    'exp_city_id' => array_get($request->get("exp_city_id"), $key),
			    'exp_org_site' => array_get($request->get("exp_org_site"), $key),
			    'exp_start_work' => array_get($request->get("exp_start_work"), $key),
			    'exp_is_working' => array_get($request->get("exp_is_working"), $key),
			    'exp_end_work' => array_get($request->get("exp_end_work"), $key),
			    'exp_achievements' => array_get($request->get("exp_achievements"), $key),
		    ]);
	    }
	    $resume->workExperiences()->saveMany($experiences);

	    if ($request->has('preview')) {
		    $resume->update(['draft' => true]);
		    return redirect()->route('resumes.show', $resume->id);
	    }

	    if($resume->moderated)
		    return redirect()->route('workers.profile')->with('success', 'Резюме обновлено!');
	    else
		    return redirect()->route('workers.profile.checking')->with('success', 'Вы успешно разместили свое резюме. Ваше резюме опубликуется после модерации');
    }

	public function publishDraft($id)
	{
		$resume = Resume::findOrFail($id);
		$resume->update(['draft' => false]);

		if($resume->moderated)
			return redirect()->route('workers.profile')->with('success', 'Резюме обновлено!');
		else
			return redirect()->route('workers.profile.checking')->with('success', 'Вы успешно разместили свое резюме. Ваше резюме опубликуется после модерации');
	}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
	    $resume = Resume::findOrFail($id);
	    $resume->delete();

	    return redirect()->back()->with('success', 'Резюме удалено!');
    }

	public function sendResponse(Request $request)
	{
		$vac = Vacancy::moderated()->findOrFail($request->get('vacancy_id'));
		$res = Resume::moderated()->findOrFail($request->get('resume_id'));
		$resp = ResumeResponse::whereResumeId($res->id)->whereVacancyId($vac->id)->whereUserId(Auth::id())->first();
		if ($resp)
			return redirect()->back()->with('error', 'Вы уже предлагали эту вакансию для данного резюме');
		$this->validate($request, [
			'vacancy_id' => 'required|exists:vacancies,id',
			'resume_id' => 'required|exists:resumes,id',
		]);
		$request->merge(['user_id' => Auth::id()]);
		$resp = ResumeResponse::create($request->all());

		$request->merge([
			'type' => 'informEmployer',
			'title' => $res->career_objective,
			'recipientName' => $res->user->name,
			'recipientEmail' => Auth::user()->email,
			'senderName' => Auth::user()->company->title,
			'data' => [
				'vac'=> $vac,
				'res'=> $res,
			],
		]);
		Event::fire(new SendNotificationEvent($request->all()));

		$request->merge([
			'type' => 'resume',
			'recipientEmail' => $res->user->email,
		]);
		Event::fire(new SendNotificationEvent($request->all()));

		return redirect()->back()->with('success', 'Спасибо, ваше предложение соискателю ' . $res->FullName . ' успешно отправлено');
	}

	public function destroyResponse(Request $request)
	{
		$vac = Vacancy::findOrFail($request->get('vacancy_id'));
		$res = Resume::findOrFail($request->get('resume_id'));
		$resp = ResumeResponse::whereVacancyId($vac->id)->whereResumeId($res->id)->whereUserId($vac->user->id)->first();
		$resp->delete();

		return redirect()->back()->with('success', 'Предложение на ваше резюме ' . $res->position . ' успешно удалено');
	}

	public function toFavourites(Request $request, $id)
	{
		$resume = Resume::findOrFail($id);

		if (Auth::user()->savedResumes()->where('id', $resume->id)->count() == 0) {
			Auth::user()->savedResumes()->attach($resume);
			return redirect()->back()->with('success', 'Резюме добавлено в Ваши "Сохранённые резюме" в личном кабинете, которое можете посмотреть позже');}
		elseif (Auth::user()->savedResumes()->where('id', $resume->id)->count() > 0 && $request->get('action') == 'remove') {
			Auth::user()->savedResumes()->detach($resume);
			return redirect()->back()->with('success', 'Резюме удалено из "Сохранённых резюме"');}
	}

	public function getPdf($id)
	{
		$resume = Resume::findOrFail($id);
		$checkUserIdForOffers = $resume->offers->sortByDesc('created_at')->load('vacancy')->where('vacancy.user_id', Auth::id())->first();
		$accessToContacts = (Auth::check() && Auth::id() == $resume->user_id) || (isset(Auth::user()->company) && Auth::user()->company->get_contacts) ||
		                    ($checkUserIdForOffers && $checkUserIdForOffers->created_at >= \Carbon\Carbon::now()->subMonth());

		$lang = $resume->language_id;
		if($lang == 2)
			$pdf = \PDF::loadView('resumes.en.showPdf', compact('resume', 'lang', 'accessToContacts'));
		else
			$pdf = \PDF::loadView('resumes.showPdf', compact('resume', 'accessToContacts'));

		return $pdf->download('resume_'. $id . '.pdf');
	}

	public function makeHidden(Request $request)
	{
		$resume = Resume::findOrFail($request->get('item_id'));

		if($resume->is_hidden) {
			$resume->update(['is_hidden' => false]);
			return ['is_hidden' => false];}
		else {
			$resume->update(['is_hidden' => true]);
			return ['is_hidden' => true];}
	}

	public function complain(Request $request)
	{
		$resume = Resume::moderated()->findOrFail($request->get('resume_id'));
		$complain = Complain::findOrFail($request->get('complain_id'));
		$resp = ComplainResume::whereResumeId($resume->id)->whereUserId(Auth::id())->first();
		if ($resp)
			return redirect()->back()->with('error', 'Вы уже жаловались на данное резюме');
		$this->validate($request, [
			'resume_id' => 'required|exists:resumes,id',
			'complain_id' => 'required|exists:complains,id',
			'description' => 'max:255',
		]);

		$request->merge(['user_id' => Auth::id()]);
		$resp = ComplainResume::create($request->all());

		return redirect()->back()->with('success', 'Жалоба отправлена!');
	}
}
