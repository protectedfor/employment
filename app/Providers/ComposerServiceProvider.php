<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Company;
use App\Models\Contact;
use App\Models\Page;
use App\Models\Resumes\Resume;
use App\Models\Training;
use App\Models\TrainingCategory;
use App\Models\Vacancies\Scope;
use App\Models\Vacancies\Vacancy;
use App\Models\Widget;
use Illuminate\Support\ServiceProvider;
use View;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer([
            'profiles.employers',
            'profiles.employer_pages.responses',
            'profiles.employer_pages.edit',
            'profiles.employer_pages.saved_resumes',
            'profiles.employer_pages.settings',
            'profiles.employer_pages.on_checking',
            'profiles.employer_pages.trainings',
	        'profiles.employer_pages.training_responses',
	        'profiles.employer_pages.fill_up_balance',
        ], function ($view) {
            $my_vacancies = Vacancy::moderated()->forAuthUser()->get();
            $checking_vacancies = Vacancy::checking()->forAuthUser()->get();
            $drafts = Vacancy::draft()->forAuthUser()->get();

	        $my_trainings = Training::forAuthUser()->get();
//	        $checking_trainings = Training::checking()->forAuthUser()->get();

            $view->with(compact('my_vacancies', 'checking_vacancies', 'drafts', 'my_trainings', 'checking_trainings'));
        });
        View::composer([
            'profiles.workers',
            'profiles.worker_pages.edit',
            'profiles.worker_pages.responses',
            'profiles.worker_pages.saved_vacancies',
	        'profiles.worker_pages.settings',
	        'profiles.worker_pages.training_responses',
        ], function ($view) {
            $my_resumes = Resume::moderated()->forAuthUser()->get();
	        $checking_resumes = Resume::checking()->forAuthUser()->get();
	        $drafts = Resume::draft()->forAuthUser()->get();

            $view->with(compact('my_resumes', 'checking_resumes', 'drafts'));
        });
        View::composer([
            'partials._vacancies_by_scope',
        ], function ($view) {
            $fields = Scope::activeOrder()->with('vacancies')->has('vacancies', '>', 0)->get();

            $view->with(compact('fields'));
        });
	    View::composer([
		    'partials._vacancies_kg_by_companies',
	    ], function ($view) {
		    $companiesKg = Company::leading()->with('user.vacancies')->has('user.vacancies', '>', 0)->get();

		    $view->with(compact('companiesKg'));
	    });
	    View::composer([
		    'partials._educationBlock',
	    ], function ($view) {
		    $fields = TrainingCategory::activeOrder()->with('trainings')->has('trainings', '>', 0)->get();
		    $view->with(compact('fields'));
	    });
        View::composer([
            'partials._footer',
            'profiles.partials._employer_tabs',
        ], function ($view) {
            $customPages = Page::all();
            $contact = Contact::all()->first();
            $widgets = Widget::all()->keyBy('key');
            $view->with(compact('contact', 'customPages', 'widgets'));
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
