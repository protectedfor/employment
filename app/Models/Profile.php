<?php

namespace App\Models;

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
	protected $dates = ['date_of_birth'];

	protected $fillable = [
		'user_id', 'name', 'sname', 'mname', 'date_of_birth', 'phone', 'show_phone', 'citizenship_id', 'about_me'
	];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

	public function setDateOfBirthAttribute($entry)
	{
		if($entry == '')
			$this->attributes['date_of_birth'] = null;
		else
			$this->attributes['date_of_birth'] = Carbon::parse($entry);
	}

	public function getDateOfBirthAttribute($entry)
	{
		if($entry == '0000-00-00')
			return $this->attributes['date_of_birth'] = null;
		else
			return $this->attributes['date_of_birth'] = Carbon::parse($entry);
	}

	public function getFullNameAttribute()
	{
		return $this->sname . ' ' . $this->name . ' ' . $this->mname;
	}

	public function getLogoAttribute()
	{
		if(isset($this->attributes['logo']) && $this->attributes['logo'])
			return config('admin.imagesUploadDirectory') . '/' . $this->attributes['logo'];
	}

	public function setLogoAttribute($value)
	{
		$this->attributes['logo'] = str_replace(config('admin.imagesUploadDirectory') . '/', '', $value);
	}
}
