<?php

namespace App\Models\Resumes;

use Illuminate\Database\Eloquent\Model;

class ResumeLanguage extends Model
{
    protected $fillable = [
        'resume_id',
        'language_id',
        'language_proficiency_id'
    ];

	public function language()
	{
		return $this->belongsTo(Language::class, 'language_id');
	}

	public function languageProficiency()
	{
		return $this->belongsTo(LanguageProficiency::class, 'language_proficiency_id');
	}
}