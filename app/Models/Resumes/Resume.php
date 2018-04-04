<?php

namespace App\Models\Resumes;

use App\Models\BillingLog;
use App\Models\Meta;
use App\Models\Vacancies\Busyness;
use App\Models\Vacancies\Currency;
use App\Models\Vacancies\Scope;
use App\Models\Vacancies\VacancyResponse;
use App\User;
use Auth;
use Request;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
//use Illuminate\Http\Request;

class Resume extends Model
{

    protected $with = [
	    'resumeField',
        'user',
        'busyness',
        'city',
        'resumeLanguages',
        'currency',
        'workExperiences',
	    'institutions',
//	    'scope',
//	    'responses',
//	    'offers'
    ];

    protected $dates = ['date_of_birth'];

    protected $fillable = [
        'user_id', 'photo', 'name', 'sname', 'mname', 'date_of_birth', 'phone',
	    'citizenship_id', 'native_language_id', 'career_objective', 'city_id', 'salary',
	    'currency_id', 'scope_id', 'busyness_id', 'key_skills', 'about_me',
	    'filename1', 'file1', 'filename2', 'file2', 'filename3', 'file3',
        'language_id', 'moderated', 'draft', 'is_hidden', 'is_fixed', 'is_hot', 'in_priority'
    ];

    public function scopeForAuthUser($query)
    {
        $query->where('user_id', Auth::id());
    }

	public function scopeNotDraft($query)
	{
		$query->where('draft', false)->orderBy('created_at', 'desc');
	}

	public function scopeModerated($query)
	{
		$query->where('moderated', true)->where('draft', false)->orderBy('created_at', 'desc');
	}

	public function scopeChecking($query)
	{
		$query->where('moderated', false)->where('draft', false)->orderBy('created_at', 'desc');
	}

	public function scopeDraft($query)
	{
		$query->where('draft', true)->orderBy('created_at', 'desc');
	}

    public function scopeModeratedFixed($query)
    {
        $query->where('moderated', true)->where('is_hidden', false)->orderBy('is_fixed', 'desc')->orderBy('in_priority', 'desc')->orderBy('created_at', 'desc');
    }

	public function scopeSearched($query, \Illuminate\Http\Request $request)
	{
		if ($word = $request->get('query'))
			$query->where('career_objective', 'like', "%$word%");
		if ($place = $request->get('city_id'))
			$query->where('city_id',$place);
		if ($field = $request->get('scope_id'))
			$query->where('scope_id', $field);
		if ($fromDate = $request->get('year_from'))
			$query->where('date_of_birth', '<=', Carbon::now()->subYears($fromDate)->toDateString());
		if ($toDate = $request->get('year_to'))
			$query->where('date_of_birth', '>=', Carbon::now()->subYears($toDate)->toDateString());
	}

    public function getFullTitleAttribute()
    {
        return $this->name . ', ' . $this->date_of_birth->age . ' ' . trans_choice('год|года|лет', $this->date_of_birth->age);
    }

	public function getFullNameAttribute()
	{
		return $this->sname . ' ' . $this->name . ' ' . $this->mname;
	}

	public function getPhotoAttribute()
	{
		if(isset($this->attributes['photo']) && $this->attributes['photo'])
			return config('admin.imagesUploadDirectory') . '/' . $this->attributes['photo'];
	}

	public function setPhotoAttribute($value)
	{
		$this->attributes['photo'] = str_replace(config('admin.imagesUploadDirectory') . '/', '', $value);
	}

	public function setFileAttribute($file)
	{
		$this->attributes['file'] = str_replace(config('admin.imagesUploadDirectory') . '/', '', $file);
	}

	public function setAdminFile1Attribute($value)
	{
		return $this->attributes['file1'] = str_replace(config('admin.imagesUploadDirectory') . '/', '', $value);
	}

	public function getAdminFile1Attribute()
	{
		if ($this->exists && $this->attributes['file1'])
			return config('admin.imagesUploadDirectory') . '/' . $this->attributes['file1'];
		return null;
	}

	public function setAdminFile2Attribute($value)
	{
		return $this->attributes['file2'] = str_replace(config('admin.imagesUploadDirectory') . '/', '', $value);
	}

	public function getAdminFile2Attribute()
	{
		if ($this->exists && $this->attributes['file2'])
			return config('admin.imagesUploadDirectory') . '/' . $this->attributes['file2'];
		return null;
	}

	public function setAdminFile3Attribute($value)
	{
		return $this->attributes['file3'] = str_replace(config('admin.imagesUploadDirectory') . '/', '', $value);
	}

	public function getAdminFile3Attribute()
	{
		if ($this->exists && $this->attributes['file3'])
			return config('admin.imagesUploadDirectory') . '/' . $this->attributes['file3'];
		return null;
	}

	public function setDateOfBirthAttribute($entry)
	{
		if($entry == '')
			$this->attributes['date_of_birth'] = null;
		else
			$this->attributes['date_of_birth'] = Carbon::parse($entry);
	}

	public function totalWorkExperienceInRussian($lang = null)
	{
		$period = 0;
		foreach($this->workExperiences as $workExperience) {
			$exp_start_work = strtotime($workExperience->exp_start_work);
			$workExperience->exp_end_work ? $exp_end_work = strtotime($workExperience->exp_end_work) : $exp_end_work = strtotime(Carbon::now()->toDateTimeString());
			$period = $period + ($exp_end_work - $exp_start_work);
		}
		$fullPeriod = Carbon::now()->addSeconds($period)->diffForHumans();
	    $periodArray = explode(' ', $fullPeriod);
	    $words = [
		    'day' => 'день',
		    'days' => trans_choice("день|дня|дней", $periodArray[0]),
		    'week' => 'неделя',
		    'weeks' => trans_choice("неделя|недели|недель", $periodArray[0]),
		    'month' => 'месяц',
		    'months' => trans_choice("месяц|месяца|месяцев", $periodArray[0]),
		    'year' => 'год',
		    'years' => trans_choice("год|года|лет", $periodArray[0]),
	    ];
	    $word = array_get($words, $periodArray[1]);

		if($this->language_id == 2 && isset($lang))
			return $totalWorkExperience = $periodArray[0] . ' ' . $periodArray[1];
		else
	        return $totalWorkExperience = $periodArray[0] . ' ' . $word;
	}

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function resumeField()
    {
        return $this->belongsTo(Scope::class, 'scope_id');
    }

	public function currency()
	{
		return $this->belongsTo(Currency::class);
	}

    public function institutions()
    {
        return $this->hasMany(Institution::class);
    }

	public function extraInstitutions()
	{
		return $this->hasMany(ExtraInstitutions::class);
	}

	public function nativeLanguage()
	{
		return $this->belongsTo(Language::class, 'native_language_id');
	}

    public function resumeLanguages()
    {
        return $this->hasMany(ResumeLanguage::class);
    }

    public function workExperiences()
    {
        return $this->hasMany(WorkExperience::class);
    }

    public function busyness()
    {
        return $this->belongsTo(Busyness::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

	public function offers()
	{
		return $this->hasMany(VacancyResponse::class);
	}

	public function responses()
	{
		return $this->hasMany(ResumeResponse::class);
	}

	public function scope()
	{
		return $this->belongsTo(Scope::class);
	}

	public function billingLog()
	{
		return $this->morphMany(BillingLog::class, 'billable');
	}

	public function metas()
	{
		return $this->morphMany(Meta::class, 'metable');
	}
}
