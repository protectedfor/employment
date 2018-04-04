<?php

namespace App\Models\Vacancies;

use App\Models\BillingLog;
use App\Models\Meta;
use App\Models\Resumes\City;
use App\Models\Resumes\Language;
use App\Models\Resumes\ResumeResponse;
use App\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Vacancy extends Model
{
    protected $with = [
        'vacancyField',
        'user',
        'busyness',
	    'billingLog',
	    'city',
//	    'scope',
//	    'education',
//	    'currency',
//	    'responses',
//	    'offers'
    ];

	protected $dates = ['expires_at'];

    protected $fillable = [
        'user_id', 'position', 'scope_id', 'place_of_work', 'city_id', 'education_id',
	    'busyness_id', 'work_graphite', 'experience', 'wages_from', 'wages_to', 'expires_at',
        'currency_id', 'overview', 'qualification_requirements', 'duties', 'conditions',
        'response_email_notifications', 'only_in_english', 'request_type', 'form_from_file',
	    'link_online_form', 'language_id', 'moderated', 'draft', 'is_fixed', 'is_hot', 'in_priority'
    ];

    public function scopeForAuthUser($query)
    {
        $query->where('user_id', Auth::id());
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
        $query->where('moderated', true)->orderBy('is_fixed', 'desc')->orderBy('in_priority', 'desc');
    }

    public function scopeModeratedHot($query)
    {
        $query->where('moderated', true)->where('is_hot', true)->orderBy('in_priority', 'desc');
//        $query->where('moderated', true)->whereHas('billingLog', function($q)
//        {
//	        dd($q);
//	        $q->where('active', true)->whereDescription('hot')->where('updated_at', '>=', 1);
//
//        })->orderBy('updated_at', 'desc')->first();
    }

    public function scopeSearched($query, Request $request)
    {
        if ($word = $request->get('query'))
            $query->where('position', 'like', "%$word%")->orWhereHas('user.company', function ($q) use($word) {
		    $q->where('title', 'like', "%$word%");})->where('moderated', true)->orderBy('created_at', 'desc');
        if ($place = $request->get('city_id'))
            $query->where('city_id', $place);
        if ($field = $request->get('scope_id'))
            $query->where('scope_id', $field);
        if ($busyness = $request->get('busyness_id'))
            $query->where('busyness_id', $busyness);
	    if ($user_id = $request->get('user_id'))
		    $query->where('user_id', $user_id);
    }

    public function vacancyField()
    {
        return $this->belongsTo(Scope::class, 'scope_id');
    }

    public function busyness()
    {
        return $this->belongsTo(Busyness::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function salaryCurrency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function responses()
    {
        return $this->hasMany(VacancyResponse::class);
    }

	public function offers()
	{
		return $this->hasMany(ResumeResponse::class);
	}

	public function city()
	{
		return $this->belongsTo(City::class);
	}

	public function scope()
	{
		return $this->belongsTo(Scope::class);
	}

	public function education()
	{
		return $this->belongsTo(Education::class);
	}

	public function currency()
	{
		return $this->belongsTo(Currency::class);
	}

	public function language()
	{
		return $this->belongsTo(Language::class);
	}

	public function complain()
	{
		return $this->belongsToMany(Complain::class);
	}

	public function billingLog()
	{
		return $this->morphMany(BillingLog::class, 'billable');
	}

	public function metas()
	{
		return $this->morphMany(Meta::class, 'metable');
	}

	public function setModeratedAttribute($value)
	{
		if(!$this->attributes['moderated'] && !$this->in_priority)
			$this->attributes['in_priority'] = Carbon::now();
		$this->attributes['moderated'] = $value;
	}

	public function setformFromFileAttribute($file)
	{
		$this->attributes['form_from_file'] = str_replace(config('admin.imagesUploadDirectory') . '/', '', $file);
	}

	public function setExpiresAtAttribute($entry)
	{
		if($entry == '')
			$this->attributes['expires_at'] = null;
		else
			$this->attributes['expires_at'] = Carbon::parse($entry);
	}

	public function billingFilter($filteredBy)
	{
		return $this->billingLog->filter(function ($item) use($filteredBy) {
			return $item['description'] == $filteredBy && $item['expired'] == false;
		});
	}
}
