<?php

Route::group(['middleware' => 'requirements'], function () {
	Route::get('/', 'PagesController@getHome')->name('home');
	Route::get('subscribe', 'PagesController@addToMailings')->name('subscribe');
	Route::get('pages/{slug}', 'PagesController@getPage')->name('page');

	Route::get('vacancies', 'VacanciesController@index')->name('vacancies.index');
	Route::get('vacancies/{id}', 'VacanciesController@show')->name('vacancies.show')->where('id', '[0-9]+');
	Route::get('vacancies/{id}/getPdf', 'VacanciesController@getPdf')->name('vacancies.getPdf')->where('id', '[0-9]+');

	Route::get('resumes', 'ResumesController@index')->name('resumes.index');
	Route::group(['middleware' => 'resumes'], function () {
		Route::get('resumes/{id}', 'ResumesController@show')->name('resumes.show')->where('id', '[0-9]+');
		Route::get('resumes/{id}/getPdf', 'ResumesController@getPdf')->name('resumes.getPdf')->where('id', '[0-9]+');
	});

	Route::get('companies', 'CompaniesController@index')->name('companies.index');
	Route::get('companies/{id}', 'CompaniesController@show')->name('companies.show')->where('id', '[0-9]+');

	Route::get('articles', 'PagesController@getArticles')->name('articles.index');
	Route::get('articles/{id}', 'PagesController@getArticle')->name('articles.show')->where('id', '[0-9]+');

	Route::get('trainings', 'TrainingsController@index')->name('trainings.index');
	Route::get('trainings/{id}', 'TrainingsController@show')->name('trainings.show')->where('id', '[0-9]+');
	Route::post('trainings/{id}/response/send', 'TrainingsController@sendResponse')->name('training.response.send')->where('id', '[0-9]+');

	Route::group(['middleware' => 'auth'], function () {

		Route::group(['middleware' => 'role:employers'], function () {

			Route::group(['middleware' => 'owner'], function () {
				Route::get('vacancies/{id}/edit', 'VacanciesController@edit')->name('vacancies.edit')->where('id', '[0-9]+');
				Route::post('vacancies/{id}/update', 'VacanciesController@update')->name('vacancies.update')->where('id', '[0-9]+');
				Route::post('vacancies/{id}/delete', 'VacanciesController@destroy')->name('vacancies.delete')->where('id', '[0-9]+');
				Route::post('vacancies/{id}/makeInPriority', 'BillingLogsController@makeInPriority')->name('vacancies.makeInPriority')->where('id', '[0-9]+');
				Route::post('vacancies/{id}/makeFixed', 'BillingLogsController@makeFixed')->name('vacancies.makeFixed')->where('id', '[0-9]+');
				Route::post('vacancies/{id}/makeHot', 'BillingLogsController@makeHot')->name('vacancies.makeHot')->where('id', '[0-9]+');

				Route::post('companies/{id}/makeLeading', 'BillingLogsController@makeLeading')->name('companies.makeLeading')->where('id', '[0-9]+');
				Route::post('companies/{id}/getContacts', 'BillingLogsController@getContacts')->name('companies.getContacts')->where('id', '[0-9]+');

				Route::get('trainings/{id}/edit', 'TrainingsController@edit')->name('trainings.edit')->where('id', '[0-9]+');
				Route::post('trainings/{id}/update', 'TrainingsController@update')->name('trainings.update')->where('id', '[0-9]+');
				Route::post('trainings/{id}/delete', 'TrainingsController@destroy')->name('trainings.delete')->where('id', '[0-9]+');
				Route::post('trainings/{id}/makePost', 'BillingLogsController@publish')->name('trainings.publish')->where('id', '[0-9]+');
			});

			Route::post('terminals/payments/store', 'PaymentController@store')->name('payments.store');
			Route::get('trainings/create', 'TrainingsController@create')->name('trainings.create');
			Route::post('trainings/store', 'TrainingsController@store')->name('trainings.store');
			Route::get('profile/trainings', 'ProfilesController@getTrainingsProfile')->name('trainings.profile');
			Route::get('profile/trainings/checking', 'ProfilesController@getTrainingsProfile')->name('trainings.profile.checking');

			Route::get('vacancies/create', 'VacanciesController@create')->name('vacancies.create');
			Route::post('vacancies/store', 'VacanciesController@store')->name('vacancies.store');
			Route::get('vacancies/{id}/publishDraft', 'VacanciesController@publishDraft')->name('vacancies.publishDraft')->where('id', '[0-9]+');
			Route::post('vacancies/response/destroy', 'VacanciesController@destroyResponse')->name('vacancies.response.destroy');
			Route::get('profile/employer', 'ProfilesController@getEmployersProfile')->name('employers.profile');
			Route::get('profile/employer/edit', 'ProfilesController@getEmployersProfileEdit')->name('employers.profile.edit');
			Route::post('profile/employer/edit', 'ProfilesController@getEmployersProfileEditUpdate')->name('employers.profile.edit.update');
			Route::get('profile/employer/checking', 'ProfilesController@getEmployersProfile')->name('employers.profile.checking');
			Route::get('profile/employer/drafts', 'ProfilesController@getEmployersProfile')->name('employers.profile.drafts');
			Route::get('profile/employer/vacancy_responses', 'ProfilesController@getEmployersResponsesForVacancies')->name('employers.profile.vacancy_responses');
			Route::get('profile/employer/fill_up_balance', 'ProfilesController@fillUpEmployerBalance')->name('employers.profile.fill_up_balance');
			Route::get('profile/employer/resume_responses', 'ProfilesController@getEmployersResponsesForResumes')->name('employers.profile.resume_responses');
//        Route::get('profile/employer/training_responses', 'ProfilesController@getEmployersResponsesForTrainings')->name('employers.profile.training_responses');
			Route::get('profile/employer/saved_resumes', 'ProfilesController@getEmployersProfileSavedResumes')->name('employers.profile.saved_resumes');
			Route::get('profile/employer/settings', 'ProfilesController@getEmployersProfileSettings')->name('employers.profile.settings');
			Route::post('resumes/response/send', 'ResumesController@sendResponse')->name('resume.response.send');
			Route::get('resumes/{id}/toFavourites', 'ResumesController@toFavourites')->name('resumes.toFavourites')->where('id', '[0-9]+');
			Route::post('resumes/complain', 'ResumesController@complain')->name('resumes.complain');
		});

		Route::group(['middleware' => 'role:workers'], function () {

			Route::group(['middleware' => 'owner'], function () {
				Route::get('resumes/{id}/edit', 'ResumesController@edit')->name('resumes.edit')->where('id', '[0-9]+');
				Route::post('resumes/{id}/update', 'ResumesController@update')->name('resumes.update')->where('id', '[0-9]+');
				Route::post('resumes/{id}/delete', 'ResumesController@destroy')->name('resumes.delete')->where('id', '[0-9]+');
			});
			Route::get('ajax/resumes/makeHidden', 'ResumesController@makeHidden')->name('resumes.makeHidden')->where('id', '[0-9]+');

			Route::get('resumes/create', 'ResumesController@create')->name('resumes.create');
			Route::post('resumes/store', 'ResumesController@store')->name('resumes.store');
			Route::get('resumes/{id}/publishDraft', 'ResumesController@publishDraft')->name('resumes.publishDraft')->where('id', '[0-9]+');
			Route::post('resumes/response/destroy', 'ResumesController@destroyResponse')->name('resumes.response.destroy');
			Route::get('profile/candidate', 'ProfilesController@getWorkerProfile')->name('workers.profile');
			Route::get('profile/candidate/edit', 'ProfilesController@getWorkersProfileEdit')->name('workers.profile.edit');
			Route::post('profile/candidate/edit', 'ProfilesController@getWorkersProfileEditUpdate')->name('workers.profile.edit.update');
			Route::get('profile/candidate/checking', 'ProfilesController@getWorkerProfile')->name('workers.profile.checking');
			Route::get('profile/candidate/drafts', 'ProfilesController@getWorkerProfile')->name('workers.profile.drafts');
			Route::get('profile/candidate/vacancy_responses', 'ProfilesController@getWorkersResponsesForVacancies')->name('workers.profile.vacancy_responses');
			Route::get('profile/candidate/resume_responses', 'ProfilesController@getWorkersResponsesForResumes')->name('workers.profile.resume_responses');
//	    Route::get('profile/candidate/training_responses', 'ProfilesController@getWorkersResponsesForTrainings')->name('workers.profile.training_responses');
			Route::get('profile/candidate/saved_vacancies', 'ProfilesController@getWorkersProfileSavedResumes')->name('workers.profile.saved_vacancies');
			Route::get('profile/candidate/settings', 'ProfilesController@getWorkersProfileSettings')->name('workers.profile.settings');
			Route::post('vacancies/response/send', 'VacanciesController@sendResponse')->name('vacancy.response.send');

			Route::get('vacancies/{id}/toFavourites', 'VacanciesController@toFavourites')->name('vacancies.toFavourites')->where('id', '[0-9]+');
			Route::post('vacancies/complain', 'VacanciesController@complain')->name('vacancies.complain');
		});

		Route::post('users/{id}/update', ['as' => 'users.update', 'uses' => 'UsersController@update'])->where('id', '[0-9]+');

		Route::post('ajax/uploadImage', 'AjaxController@uploadImage')->name('ajax.uploadImage');
		Route::post('ajax/uploadFile', 'AjaxController@uploadFile')->name('ajax.uploadFile');
	});

//ImageCache
	Route::get('fitImage', 'ImageCacheController@fitImage')->name('ic.fitImage');
	Route::get('resizeImage', 'ImageCacheController@resizeImage')->name('ic.resizeImage');
	Route::get('noImage', 'ImageCacheController@noImage')->name('ic.noImage');

//Route for accepting requests from mobilnik servers
	Route::post('terminals/mobilnik', 'PaymentController@index');

	Route::get('ajax/vacancies', 'AjaxController@getVacancies')->name('ajax.vacancies');
	Route::get('ajax/resumes', 'AjaxController@getResumes')->name('ajax.resumes');
	Route::get('ajax/companies', 'AjaxController@getCompanies')->name('ajax.companies');
	Route::get('ajax/training_categories', 'AjaxController@getTrainingCategories')->name('ajax.training_categories');
	Route::post('ajax/modal/getModalContent', 'AjaxController@getModalContent')->name('ajax.getModalContent');
	Route::get('ajax/bannerClick', 'AjaxController@bannerClick')->name('ajax.bannerClick');

// Authentication routes...
	Route::get('social/auth', 'Auth\AuthController@getSocialAuth');
	Route::get('auth/login', 'Auth\AuthController@getLogin')->name('auth.login');;
	Route::post('auth/login', 'Auth\AuthController@postLogin');
	Route::get('auth/logout', 'Auth\AuthController@getLogout');
	Route::get('auth/activate', ['as' => 'auth.activate', 'uses' => 'Auth\AuthController@activate']);
	Route::get('auth/send_activation/{user_id}', ['as' => 'auth.send_activation', 'uses' => 'Auth\AuthController@sendActivation'])->where('user_id', '[0-9]+');

// Registration routes...
	Route::get('auth/register', 'Auth\AuthController@getRegister')->name('auth.register');;
	Route::post('auth/register', 'Auth\AuthController@postRegister')->name('auth.postRegister');;

// Password reset link request routes...
	Route::get('password/email', 'Auth\PasswordController@getEmail')->name('password.email');;
	Route::post('password/email', 'Auth\PasswordController@postEmail');

// Password reset routes...
	Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
	Route::post('password/reset', 'Auth\PasswordController@postReset')->name('password.reset');

});