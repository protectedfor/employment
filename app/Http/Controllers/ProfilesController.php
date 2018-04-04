<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Meta;
use App\Models\Profile;
use App\Models\Resumes\Citizenship;
use App\Models\Resumes\City;
use App\Models\Training;
use App\Models\Vacancies\Scope;
use App\Models\Vacancies\Vacancy;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCompanyRequest;

class ProfilesController extends Controller
{
    /**
     * Generates employers profile page
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getEmployersProfile(Request $request)
    {
        return view('profiles.employers');
    }

    /**
     * Generates worker profile page
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getWorkerProfile(Request $request)
    {
        return view('profiles.workers');
    }

	/**
	 * Generates trainings profile page
	 *
	 * @param Request $request
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function getTrainingsProfile(Request $request)
	{
		return view('profiles.employer_pages.trainings');
	}

	/**
	 * Generates pages with list of responses to vacancies from workers
	 *
	 * @param Request $request
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function getWorkersResponsesForVacancies(Request $request)
	{
		$resumes = Auth::user()->resumes->load('offers.vacancy')->sortByDesc('offers.created_at');
		$type = 'offers';

		return view('profiles.worker_pages.responses', compact('resumes', 'type'));
	}

	/**
	 * Generates pages with list of responses to resumes from employers
	 *
	 * @param Request $request
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function getWorkersResponsesForResumes(Request $request)
	{
		$resumes = Auth::user()->resumes->load('responses.vacancy')->sortByDesc('responses.created_at');
		foreach($resumes as $resume) {
			foreach($resume->responses->filter(function ($item) {return $item['created_at'] == $item->updated_at;}) as $response) {
				$response->update(['updated_at' => Carbon::now()]);
			}
		}
		$type = 'responses';

		return view('profiles.worker_pages.responses', compact('resumes', 'type'));
	}

	/**
	 * Generates pages with list of responses to trainings from workers
	 *
	 * @param Request $request
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function getWorkersResponsesForTrainings(Request $request)
	{
		$trainings = Training::whereHas('responses', function($query){
			$query->where('user_id', Auth::id());
		})->moderated()->orderBy('created_at', 'desc')->get();

		return view('profiles.worker_pages.training_responses', compact('trainings'));
	}

	/**
	 * Generates page with form for edit info about worker
	 *
	 * @param Request $request
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function getWorkersProfileEdit(Request $request)
	{
		$profile = Auth::user()->profile;
		$citizenships = Citizenship::activeOrder()->get();

		return view('profiles.worker_pages.edit', compact('profile', 'citizenships'));
	}

    /**
     * Stores user's profile info
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getWorkersProfileEditUpdate(Request $request)
	{
		$this->validate($request, [
			'name' => 'required|max:50',
			'sname' => 'required|max:50',
//			'mname' => 'required|max:50',
			'phone' => 'required|max:50',
//			'citizenship_id' => 'required|exists:citizenships,id',
			'date_of_birth' => 'required|max:255|date_format:d.m.Y|after:' . Carbon::now()->subYears(100) . '|before:' . Carbon::now(),
		]);
		if(!$request->has('show_phone'))
			$request->merge(['show_phone' => 0]);

		Auth::user()->profile->update($request->all());

		return redirect()->back()->with('success', 'Ваш профиль обновлен!');
	}

	/**
	 * Generates page with list of all saved vacancies for currently authenticated worker
	 *
	 * @param Request $request
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function getWorkersProfileSavedResumes(Request $request)
	{
		$savedVacancies = Auth::user()->savedVacancies()->get();

		return view('profiles.worker_pages.saved_vacancies', compact('savedVacancies'));
	}


	public function getWorkersProfileSettings(Request $request)
	{
		return view('profiles.worker_pages.settings');
	}

    /**
     * Generates pages with list of responses to vacancies from workers
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getEmployersResponsesForVacancies(Request $request)
    {
	    $vacancies = Auth::user()->vacancies->sortByDesc('responses.created_at');
	    foreach($vacancies as $vacancy) {
		    foreach($vacancy->responses->filter(function ($item) {return $item['created_at'] == $item->updated_at;}) as $response) {
			    $response->update(['updated_at' => Carbon::now()]);
		    }
	    }
	    $type = 'responses';

        return view('profiles.employer_pages.responses', compact('vacancies', 'type'));
    }

	/**
	 * Generates pages with list of responses to resumes from employers
	 *
	 * @param Request $request
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function getEmployersResponsesForResumes(Request $request)
	{
		$vacancies = Auth::user()->vacancies->sortByDesc('offers.created_at');
		$type = 'offers';

		return view('profiles.employer_pages.responses', compact('vacancies', 'type'));
	}

	/**
	 * Generates pages with list of responses to trainings from workers
	 *
	 * @param Request $request
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function getEmployersResponsesForTrainings(Request $request)
	{
		$trainings = Auth::user()->trainings()->with(['responses' => function($query){
			$query->orderBy('created_at', 'desc');
		}])->moderated()->get();

		return view('profiles.employer_pages.training_responses', compact('trainings'));
	}

    /**
     * Generates page with form for edit info about company
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getEmployersProfileEdit(Request $request)
    {
        $scopes = Scope::activeOrder()->get();
        $cities = City::activeOrder()->get();
        $profile = Auth::user()->company;
        return view('profiles.employer_pages.edit', compact('scopes', 'cities', 'profile'));
    }

    public function getEmployersProfileEditUpdate(StoreCompanyRequest $request)
    {
	    $entry = Auth::user()->company()->first();
	    $entry->update($request->only(['title', 'scope_id', 'city_id', 'address', 'google_map_code',
	        'about_company', 'fio', 'show_fio', 'phone', 'show_phone', 'site', 'show_site'
        ]));

	    $entry->metas()->delete();
	    $metas = Meta::create(['slug' => 'companies/' . $entry->id, 'metatitle' => $entry->title . ' | Employment.kg', 'metakeyw' => $entry->title . ' | Employment.kg', 'metadesc' => $entry->title . ' | Employment.kg']);
	    $entry->metas()->save($metas);

        return redirect()->back()->with('success', 'Профиль компании обновлен!');
    }

    /**
     * Generates page with list of all saved resumes for currently authenticated employer
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getEmployersProfileSavedResumes(Request $request)
    {
	    $savedResumes = Auth::user()->savedResumes()->get();
        return view('profiles.employer_pages.saved_resumes', compact('savedResumes'));
    }

    public function getEmployersProfileSettings(Request $request)
    {
        return view('profiles.employer_pages.settings');
    }

	public function fillUpEmployerBalance(Request $request)
	{
		$payment = Auth::user()->payments->sortByDesc('created_at')->first();

		return view('profiles.employer_pages.fill_up_balance', compact('payment'));
	}
}
