<?php

namespace App\Console\Commands;

use App\Models\Resumes\ComplainResume;
use App\Models\Resumes\Resume;
use App\Models\Resumes\ResumeResponse;
use App\Models\Resumes\WorkExperience;
use App\Models\Training;
use App\Models\TrainingResponse;
use App\Models\Vacancies\Complain;
use App\Models\Vacancies\ComplainVacancy;
use App\Models\Vacancies\Vacancy;
use App\Models\Vacancies\VacancyResponse;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Password;

class BrokenItemsRemove extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'brokenItems:remove';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
	    $number = 0;

	    $resume_responses = ResumeResponse::all();
	    foreach($resume_responses as $item) {
		    if(Vacancy::where('id', $item->vacancy_id)->get()->count() <= 0 || Resume::where('id', $item->resume_id)->get()->count() <= 0 ||
			    User::where('id', $item->user_id)->get()->count() <= 0) {
			    $item->delete();
			    $number = $number + 1;
			    $this->info($number . '. Removed resume_response id: ' . $item->id);
		    }
	    }

	    $vacancy_responses = VacancyResponse::all();
	    foreach($vacancy_responses as $item) {
		    if(Vacancy::where('id', $item->vacancy_id)->get()->count() <= 0 || Resume::where('id', $item->resume_id)->get()->count() <= 0 ||
			    User::where('id', $item->user_id)->get()->count() <= 0) {
			    $item->delete();
			    $number = $number + 1;
			    $this->info($number . '. Removed vacancy_response id: ' . $item->id);
		    }
	    }

	    $complain_resumes = ComplainResume::all();
	    foreach($complain_resumes as $item) {
		    if(Complain::where('id', $item->complain_id)->get()->count() <= 0 || Resume::where('id', $item->resume_id)->get()->count() <= 0 ||
			    User::where('id', $item->user_id)->get()->count() <= 0) {
			    $item->delete();
			    $number = $number + 1;
			    $this->info($number . '. Removed complain_resume id: ' . $item->id);
		    }
	    }

	    $complain_vacancies = ComplainVacancy::all();
	    foreach($complain_vacancies as $item) {
		    if(Complain::where('id', $item->complain_id)->get()->count() <= 0 || Vacancy::where('id', $item->vacancy_id)->get()->count() <= 0 ||
			    User::where('id', $item->user_id)->get()->count() <= 0) {
			    $item->delete();
			    $number = $number + 1;
			    $this->info($number . '. Removed complain_vacancy id: ' . $item->id);
		    }
	    }

	    $resume_user = \DB::table('resume_user')->get();
	    foreach($resume_user as $key => $item) {
		    if(Resume::where('id', $item->resume_id)->get()->count() <= 0 || User::where('id', $item->user_id)->get()->count() <= 0) {
			    \DB::table('resume_user')->where('resume_id', $item->resume_id)->where('user_id', $item->user_id)->delete();
			    $number = $number + 1;
			    $this->info($number . '. Removed resume_user resume_id: ' . $item->resume_id);
		    }
	    }

	    $user_vacancy = \DB::table('user_vacancy')->get();
	    foreach($user_vacancy as $item) {
		    if(Vacancy::where('id', $item->vacancy_id)->get()->count() <= 0 || User::where('id', $item->user_id)->get()->count() <= 0) {
			    \DB::table('user_vacancy')->where('vacancy_id', $item->vacancy_id)->where('user_id', $item->user_id)->delete();
			    $number = $number + 1;
			    $this->info($number . '. Removed user_vacancy vacancy_id: ' . $item->vacancy_id);
		    }
	    }

	    $trainings = Training::all();
	    foreach($trainings as $item) {
		    if(User::where('id', $item->user_id)->get()->count() <= 0) {
			    $item->delete();
			    $number = $number + 1;
			    $this->info($number . '. Removed trainings id: ' . $item->id);
		    }
	    }

	    $training_responses = TrainingResponse::all();
	    foreach($training_responses as $item) {
		    if(Training::where('id', $item->training_id)->get()->count() <= 0) {
			    $item->delete();
			    $number = $number + 1;
			    $this->info($number . '. Removed training_responses id: ' . $item->id);
		    }
	    }

	    $work_experiences = WorkExperience::all();
	    foreach($work_experiences as $item) {
		    if(Resume::where('id', $item->resume_id)->get()->count() <= 0) {
			    $item->delete();
			    $number = $number + 1;
			    $this->info($number . '. Removed work_experiences id: ' . $item->id);
		    }
	    }
    }
}
