<?php

namespace App\Models\Resumes;

use App\Models\Vacancies\Education;
use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{

    protected $with = ['education'];

    protected $fillable = [
        'resume_id',
        'education_id',
        'institution',
        'department',
        'specialty',
        'year_of_ending'
    ];

	public function education()
	{
		return $this->belongsTo(Education::class);
	}
}
