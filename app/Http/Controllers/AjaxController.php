<?php

namespace App\Http\Controllers;

use App\Models\BaseModel;
use App\Models\Company;
use App\Models\Profile;
use App\Models\Resumes\City;
use App\Models\Resumes\Resume;
use App\Models\TrainingCategory;
use App\Models\Vacancies\Busyness;
use App\Models\Vacancies\Scope;
use App\Models\Vacancies\Vacancy;
use App\Models\Vars;
use Illuminate\Http\Request;
use Input;
use Response;
use Validator;

class AjaxController extends BaseController
{
    public function uploadFile(Request $request)
    {
        $validator = Validator::make(Input::all(), static::uploadFileValidationRules($request));
        if ($validator->fails()) {
            return Response::make($validator->errors()->get('file'), 400);
        }
        $file = Input::file('file');
        $filename = md5(time() . $file->getClientOriginalName()) . '.' . mb_strtolower($file->getClientOriginalExtension());

        if ($request->get('section') == 'form_from_file' || $request->get('section') == 'response_attached_file')
            $path = config('admin.imagesUploadDirectory') . '/attached';
        else
            $path = config('admin.imagesUploadDirectory');

        $fullpath = public_path($path);
        $file->move($fullpath, $filename);
        $value = $filename;
        return [
            'url' => asset(($path . '/' . $value)),
            'value' => $value,
        ];

    }

    protected static function uploadFileValidationRules($request)
    {
        if ($request->get('section') == 'resume_file' || $request->get('section') == 'form_from_file' || $request->get('section') == 'response_attached_file') {
            return [
                'file' => 'required|mimes:doc,docx,xls,xlsx,pdf,jpg,jpeg,png|max:3000',
            ];
        } else {
            return [
                'file' => 'required|mimes:doc,docx,xls,xlsx,pdf,jpg,jpeg,png|max:3000',
            ];
        }
    }

    public function uploadImage(Request $request)
    {
        $validator = Validator::make(Input::all(), static::uploadValidationRules());
        if ($validator->fails()) {
            return Response::make($validator->errors()->get('file'), 400);
        }
        $file = Input::file('file');
        $filename = md5(time() . $file->getClientOriginalName()) . '.' . mb_strtolower($file->getClientOriginalExtension());
        $path = config('admin.imagesUploadDirectory');
        $fullpath = public_path($path);
        $file->move($fullpath, $filename);
        $value = $filename;
        if ($request->get('section') == 'company_logo') {
            $company = Company::findOrFail($request->get('company_id'));
            if ($company->user->hasRoleFix('employers')) {
                $company->logo = $value;
                $company->save();
            }
        }
        if ($request->get('section') == 'worker_profile_block') {
            $profile = Profile::findOrFail($request->get('profile_id'));
            if ($profile->user->hasRoleFix('workers')) {
                $profile->logo = $value;
                $profile->save();
            }
        }
        return [
            'url' => asset($path . '/' . $filename),
            'value' => $value,
        ];
    }


    protected static function uploadValidationRules()
    {
        return [
            'file' => 'required|mimes:jpg,jpeg,png,gif|max:3000',
        ];
    }

    public function getVacancies(Request $request)
    {
        $scopes = Scope::activeOrder()->get();
        $cities = City::activeOrder()->get();
        $busynesses = Busyness::activeOrder()->get();
        $vacancies = Vacancy::moderatedFixed()->searched($request)->paginate(env('CONFIG_PAGINATE', 1));
        return view('partials._filtered_entries', compact('scopes', 'cities', 'busynesses', 'vacancies'));
    }

	public function getResumes(Request $request)
	{
		$scopes = Scope::activeOrder()->get();
		$cities = City::activeOrder()->get();
		$resumes = Resume::moderatedFixed()->searched($request)->paginate(env('CONFIG_PAGINATE', 1));
		return view('partials._filtered_entries', compact('scopes', 'cities', 'resumes'));
	}

	public function getCompanies(Request $request)
	{
		$scopes = Scope::activeOrder()->get();
		$cities = City::activeOrder()->get();
		$companies = Company::with('user', 'user.vacancies')->activated()->searched($request)->paginate(env('CONFIG_PAGINATE', 1));
		return view('partials._filtered_entries', compact('scopes', 'cities', 'companies'));
	}

	public function getTrainingCategories(Request $request)
	{
		if($request->get('loc') == 1)
			$categories = TrainingCategory::whereLocation(1)->get();
		else
			$categories = TrainingCategory::whereLocation(0)->get();

		return view('partials._training_categories', compact('categories'));
	}

	public function getModalContent(Request $request)
	{
		$billingVars = Vars::getBillingVars();
		$action = $request->get('action');
		$type = $request->get('type');
		$item_id = $request->get('item_id');
		$forType = $request->get('forItem');
		$forItem_id = $request->get('forItem_id');
		$parameters = $request->get('parameters');
		$itemPath = $type == 'resume' ? 'App\Models\Resumes\\' : ($type == 'vacancy' ? 'App\Models\Vacancies\\' : 'App\Models\\');
		$forItemPath = $forType == 'resume' ? 'App\Models\Resumes\\' : ($forType == 'vacancy' ? 'App\Models\Vacancies\\' : 'App\Models\\');

		if($item_id)
			$item = \App::make($itemPath . ucwords($type))->findOrFail($item_id);
		if($forItem_id)
			$forItem = \App::make($forItemPath . ucwords($forType))->findOrFail($forItem_id);

		return view('partials.mini._modalContentBlock', compact('action', 'type', 'forType', 'item', 'forItem', 'parameters', 'billingVars'));
	}

	public function bannerClick(Request $request)
	{
		$banner = BaseModel::makeModel('banner')->findOrFail($request->get('id'));
		$banner->increment('clicks');

		return $this->getAjaxResponseView([
			'status'  => [1, ''],
		]);
	}
}
