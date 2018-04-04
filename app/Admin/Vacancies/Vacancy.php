<?php

Admin::model(\App\Models\Vacancies\Vacancy::class)->title('Вакансии')->display(function () {
    $display = AdminDisplay::datatablesAsync();

	$display->apply(function ($query) {
		if(Request::get('moderated'))
            $query->where('draft', '0')->where('moderated', true)/*->orderBy('moderated')->orderBy('is_hot')->orderBy('is_fixed')*/->orderBy('created_at', 'desc');
		else
			$query->where('draft', '0')->where('moderated', false)/*->orderBy('moderated')->orderBy('is_hot')->orderBy('is_fixed')*/->orderBy('created_at', 'desc');
    });
    $display->with();
    $display->filters([

    ]);
	$display->columns([
		Column::checkbox(),
        Column::string('id')->label('#'),
        Column::string('position')->label('Позиция'),
        Column::string('user.company.title')->label('Компания'),
        Column::string('user.name')->label('Автор'),
        Column::custom()->label('Проверено?')->callback(function ($e) {
            return $e->moderated ? '&check;' : '-';
        }),
        Column::custom()->label('Горячая?')->callback(function ($e) {
            return $e->is_hot ? '&check;' : '-';
        }),
        Column::custom()->label('Прикреплена в списке?')->callback(function ($e) {
            return $e->is_fixed ? '&check;' : '-';
        }),
    ]);
	$display->actions([
		Column::action('delete')->value('Удалить')->icon('fa-minus')->target('_blank')->callback(function ($collection)
		{
			if($collection->count() > 0) {
				foreach($collection as $col) {
					$col->delete();
				}
				dd('Следующие элементы списка были удалены:', $collection->lists('position', 'id')->all());
			} else
				dd('Не были выбраны элементы списка для удаления! ');
		}),
	]);
    return $display;
})->createAndEdit(function ($id) {
    $form = AdminForm::form();
    $form->items([
        FormItem::columns()->columns([
            [
                FormItem::checkbox('moderated', 'Проверено?')->defaultValue(true),
//                FormItem::checkbox('is_hot', 'Горячая?'),
//                FormItem::checkbox('is_fixed', 'Прикреплена в списке?'),
                FormItem::text('position', 'Позиция')->required(),
//	            FormItem::select('user_id', 'Автор')->model(\App\User::class)->display('name')->required(),
                FormItem::select('scope_id', 'Сфера деятельности')->model(\App\Models\Vacancies\Scope::class)->display('title')->required(),
	            FormItem::select('city_id', 'Город')->model(\App\Models\Resumes\City::class)->display('title')->required(),
//	            FormItem::text('place_of_work', 'Место работы')->required(),
	            FormItem::select('education_id', 'Образование')->model(\App\Models\Vacancies\Education::class)->display('title')->required(),
	            FormItem::select('busyness_id', 'Занятость')->model(\App\Models\Vacancies\Busyness::class)->display('title')->required(),
	            FormItem::text('work_graphite', 'График работы'),
	            FormItem::text('experience', 'Опыт работы'),
	            FormItem::text('wages_from', 'Заработная плата от'),
	            FormItem::text('wages_to', 'До'),
	            FormItem::date('expires_at', 'Крайний срок:')->validationRule('after:' . \Carbon\Carbon::now()->subDay()),
	            FormItem::select('currency_id', 'Валюта')->model(\App\Models\Vacancies\Currency::class)->display('title'),
	            FormItem::checkbox('response_email_notifications', 'Уведомления'),
	            FormItem::checkbox('only_in_english', 'Требования к языку'),
		        FormItem::select('language_id', 'Язык резюме')->model(\App\Models\Resumes\Language::class)->display('title')->required(),
		        FormItem::select('request_type', 'Тип подачи резюме')->options(['resume' => 'резюме', 'form_from_file' => 'Заполенная форма', 'online_form' => 'Онлайн-форма'])->required(),
		        FormItem::file('form_from_file', 'Форма на заполнение'),
		        FormItem::text('link_online_form', 'Ссылка на онлайн форму'),
	        ], [
		        FormItem::ckeditor('overview', 'Общие сведения'),
		        FormItem::ckeditor('qualification_requirements', 'Требования к квалификации')->required(),
		        FormItem::ckeditor('duties', 'Обязанности')->required(),
		        FormItem::ckeditor('conditions', 'Условия'),
            ],
        ]),
    ]);
    return $form;
})->create(null);