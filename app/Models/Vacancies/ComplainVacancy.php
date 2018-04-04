<?php

namespace App\Models\Vacancies;

use App\User;
use Illuminate\Database\Eloquent\Model;

class ComplainVacancy extends Model
{
    protected $with = [
        'vacancy',
        'complain',
	    'user'
    ];

    protected $fillable = [
        'vacancy_id',
        'user_id',
        'complain_id',
        'description',
    ];

    public function vacancy()
    {
        return $this->belongsTo(Vacancy::class);
    }

	public function user()
	{
		return $this->belongsTo(User::class);
	}

    public function complain()
    {
        return $this->belongsTo(Complain::class);
    }
}
