<?php

namespace App\Models\Vacancies;

use App\Models\BaseModel;
use App\Models\Resumes\Resume;
use Illuminate\Database\Eloquent\Model;

class VacancyResponse extends BaseModel
{
    protected $with = [
//        'vacancy',
//        'resume'
    ];

    protected $fillable = [
        'vacancy_id', 'user_id', 'resume_id', 'filename1', 'file1',
	    'filename2', 'file2', 'filename3', 'file3', 'form_from_file', 'updated_at'
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
