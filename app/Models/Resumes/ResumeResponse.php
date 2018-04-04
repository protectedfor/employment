<?php

namespace App\Models\Resumes;

use App\Models\BaseModel;
use App\Models\Vacancies\Vacancy;
use Illuminate\Database\Eloquent\Model;

class ResumeResponse extends BaseModel
{
    protected $with = [
//        'vacancy',
//        'resume'
    ];

    protected $fillable = [
	    'resume_id', 'user_id', 'vacancy_id', 'updated_at'
    ];

    public function vacancy()
    {
        return $this->belongsTo(Vacancy::class);
    }

    public function resume()
    {
        return $this->belongsTo(Resume::class);
    }
}
