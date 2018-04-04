<?php

namespace App;

use App\Models\Company;
use App\Models\Payment;
use App\Models\Profile;
use App\Models\Resumes\Resume;
use App\Models\Training;
use App\Models\Vacancies\Vacancy;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Model implements AuthenticatableContract,
    CanResetPasswordContract
{
    use Authenticatable, CanResetPassword, EntrustUserTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    protected $with = ['company', 'roles', 'profile'];

    protected $dates = [
        'activation_request_date'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'remember_token', 'photo', 'uid', 'network',
        'identity', 'activated', 'activation_token', 'personal_bill', 'balance',
	    'informed', 'informed_at'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];


    public function setRolesAttribute($perms)
    {
        $this->roles()->detach();
        if (!$perms) return;
        if (!$this->exists) $this->save();

        $this->roles()->attach($perms);
    }

	public function hasRoleFix($role){
		return $this->roles->filter(function($item) use($role){
			return $item['name'] == $role;
		})->count();
	}

	public function getPhoneAttribute()
	{
		if($this->roles->first()->name == 'employers')
			return $this->company->phone;
		else
			return $this->profile->phone;
	}

	public function setPhoneAttribute($value)
	{
		if($this->roles->first()->name == 'employers')
			$this->company->update(['phone' => $value]);
		else
			$this->profile->update(['phone' => $value]);
	}

    public function vacancies()
    {
        return $this->hasMany(Vacancy::class);
    }

    public function resumes()
    {
        return $this->hasMany(Resume::class);
    }

	public function trainings()
	{
		return $this->hasMany(Training::class);
	}

    public function company()
    {
        return $this->hasOne(Company::class);
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

	public function savedResumes()
	{
		return $this->belongsToMany(Resume::class);
	}

	public function savedVacancies()
	{
		return $this->belongsToMany(Vacancy::class);
	}

	public function payments()
	{
		return $this->hasMany(Payment::class);
	}

	public function allResponses($type){
		$allResponses = 0;
		foreach($this->{$type} as $item) {
			$allResponses = $allResponses + $item->responses->filter(function ($item) {return $item['created_at'] == $item->updated_at;})->count();
		}
		return $allResponses;
	}
}
