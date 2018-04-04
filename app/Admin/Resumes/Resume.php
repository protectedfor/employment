<?php

Admin::model(\App\Models\Resumes\Resume::class)->title('Резюме')->display(function () {
    $display = AdminDisplay::datatablesAsync();
    $display->apply(function ($query) {
	    if(Request::get('moderated'))
            $query->where('draft', '0')->where('moderated', true)->orderBy('created_at', 'desc');
	    else
		    $query->where('draft', '0')->where('moderated', false)->orderBy('created_at', 'desc');
    });
    $display->with();
    $display->filters([

    ]);
	$display->columns([
		Column::checkbox(),
        Column::string('id')->label('#'),
        Column::string('user.name')->label('Пользователь'),
        Column::string('name')->label('Имя'),
        Column::string('user.email')->label('Email'),
        Column::string('career_objective')->label('Желаемая должность'),
        Column::custom()->label('Проверено?')->callback(function ($e) {
            return $e->moderated ? '&check;' : '-';
        }),
        Column::custom()->label('Прикреплена в списке?')->callback(function ($e) {
            return $e->is_fixed ? '&check;' : '-';
        }),
        Column::datetime('created_at')->label('Дата')
    ]);
	$display->actions([
		Column::action('delete')->value('Удалить')->icon('fa-minus')->target('_blank')->callback(function ($collection)
		{
			if($collection->count() > 0) {
				foreach($collection as $col) {
					$col->delete();
				}
				dd('Следующие элементы списка были удалены:', $collection->lists('career_objective', 'id')->all());
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
//	            FormItem::checkbox('draft', 'Черновик?'),
//	            FormItem::checkbox('is_hot', 'В горячих?'),
//	            FormItem::checkbox('is_fixed', 'Прикреплено в списке?'),
//	            FormItem::time('in_priority', 'Добавлена в приоритет'),
		        FormItem::image('photo', 'Изображения'),
		        FormItem::text('name', 'Имя')->required(),
                FormItem::text('sname', 'Фамилия')->required(),
                FormItem::text('mname', 'Отчество'),
                FormItem::date('date_of_birth', 'Дата рождения')->required(),
	            FormItem::text('phone', 'Телефон'),

//		        FormItem::select('user_id', 'Автор')->model(\App\User::class)->display('name')->required(),
		        FormItem::select('citizenship_id', 'Гражданство')->model(\App\Models\Resumes\Citizenship::class)->display('title'),
//		        FormItem::select('native_language_id', 'Родной язык')->model(\App\Models\Resumes\Language::class)->display('title')->required(),
		        FormItem::text('career_objective', 'Желаемая должность')->required(),
		        FormItem::select('city_id', 'Город')->model(\App\Models\Resumes\City::class)->display('title')->required(),
		        FormItem::text('salary', 'Желаемая зарплата')->required(),
		        FormItem::select('currency_id', 'Валюта')->model(\App\Models\Vacancies\Currency::class)->display('title')->required(),
		        FormItem::select('scope_id', 'Сфера деятельности')->model(\App\Models\Vacancies\Scope::class)->display('title')->required(),
		        FormItem::select('busyness_id', 'Занятость')->model(\App\Models\Vacancies\Busyness::class)->display('title')->required(),
		        FormItem::textarea('about_me', 'Обо мне'),
//		        FormItem::select('language_id', 'Язык резюме')->model(\App\Models\Resumes\Language::class)->display('title')->required(),
		        FormItem::select('language_id', 'Язык резюме')->options([1 => 'Русский', 2 => 'Английский'])->required(),
		        FormItem::text('filename1', 'Название файла1'),
		        FormItem::file('AdminFile1', 'Файл1'),
		        FormItem::text('filename2', 'Название файла2'),
		        FormItem::file('AdminFile2', 'Файл2'),
		        FormItem::text('filename3', 'Название файла3'),
		        FormItem::file('AdminFile3', 'Файл3'),
	        ], [
		        FormItem::view('admin.resumes.dynamic_fields'),
            ],
        ]),
    ]);
    return $form;
})->create(null);