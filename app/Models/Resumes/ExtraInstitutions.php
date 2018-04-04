<?php

namespace App\Models\Resumes;

use App\Models\Vacancies\Education;
use Illuminate\Database\Eloquent\Model;

class ExtraInstitutions extends Model
{
    protected $fillable = [
        'resume_id',
        'extra_inst_title',
        'extra_inst_organizer',
        'extra_inst_date',
        'extra_inst_location',
    ];
}
