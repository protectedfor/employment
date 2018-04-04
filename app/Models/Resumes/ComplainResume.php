<?php

namespace App\Models\Resumes;

use App\Models\Vacancies\Complain;
use App\User;
use Illuminate\Database\Eloquent\Model;

class ComplainResume extends Model
{
    protected $with = [
        'resume',
        'complain',
	    'user'
    ];

    protected $fillable = [
        'resume_id',
        'user_id',
        'complain_id',
        'description',
    ];

    public function resume()
    {
        return $this->belongsTo(Resume::class);
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
