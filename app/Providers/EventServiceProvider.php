<?php

namespace App\Providers;

use App\Models\Article;
use App\Models\Company;
use App\Models\MailingEmail;
use App\Models\Meta;
use App\Models\Page;
use App\Models\Resumes\Resume;
use App\Models\Training;
use App\Models\Vacancies\Vacancy;
use App\User;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\UserRegisteredEvent' => [
            'App\Listeners\UserRegisteredListener',
        ],
	    'App\Events\SendNotificationEvent' => [
		    'App\Listeners\SendNotificationListener',
	    ]
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

	    Vacancy::saved(function ($entry) {
		    Meta::updateMeta($entry, 'vacancies/' . $entry->id, $entry->position . ' | Employment.kg');
	    });
	    Vacancy::deleting(function ($entry) {
		    $entry->metas()->delete();
	    });

	    Resume::saved(function ($entry) {
		    Meta::updateMeta($entry, 'resumes/' . $entry->id, $entry->career_objective . ' | Employment.kg');
	    });
	    Resume::deleting(function ($entry) {
		    $entry->metas()->delete();
	    });

	    Training::saved(function ($entry) {
		    Meta::updateMeta($entry, 'trainings/' . $entry->id, $entry->title . ' | Employment.kg');
	    });
	    Training::deleting(function ($entry) {
		    $entry->metas()->delete();
	    });

	    Article::saved(function ($entry) {
		    Meta::updateMeta($entry, 'articles/' . $entry->id, $entry->title . ' | Employment.kg');
	    });
	    Article::deleting(function ($entry) {
		    $entry->metas()->delete();
	    });

	    Company::deleting(function ($entry) {
		    $entry->metas()->delete();
	    });

	    Page::deleting(function ($entry) {
		    $entry->metas()->delete();
	    });

	    User::deleting(function ($entry) {
		    $mailingEmail = MailingEmail::where('user_id', $entry->id)->first();
		    if($mailingEmail)
			    $mailingEmail->delete();
	    });
    }
}
