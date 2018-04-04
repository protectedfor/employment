<?php

\SleepingOwl\Admin\AssetManager\AssetManager::addScript('//maps.google.com/maps/api/js?key=AIzaSyAByXHJTS_tDZvRl9d1fhb1QmlPU8Cf0wQ');
\SleepingOwl\Admin\AssetManager\AssetManager::addScript('/js/gmaps.js');

Admin::model(\App\Models\Company::class)->title('Компании работодателей')->display(function () {
    $display = AdminDisplay::datatablesAsync();
    $display->apply(function ($query) {
        $query->orderBy('created_at', 'desc');
    });
    $display->with('user');
    $display->filters([

    ]);
    $display->columns([
        Column::string('id')->label('#'),
	    Column::image('logo')->label('Аватар'),
        Column::string('title')->label('Название компании'),
        Column::string('user.name')->label('Работодатель'),
	    Column::custom()->label('О компании')->callback(function ($e) {
		    return str_limit(strip_tags($e->about_company), 280);
	    }),
        Column::custom()->label('Ведущий работодатель')->callback(function ($e) {
            return $e->is_leading ? '&check;' : '-';
        }),
    ]);
    return $display;
})->createAndEdit(function ($id) {
    $form = AdminForm::form();
    $form->items([
        FormItem::columns()->columns([
            [
//                FormItem::checkbox('is_leading', 'Ведущий работодатель')->defaultValue(false),
                FormItem::text('title', 'Название компании')->required(),
	            FormItem::ckeditor('about_company', 'О компании'),
            ], [
		        FormItem::image('logo', 'Изображения'),
		        FormItem::view('admin.contacts.map'),
		        FormItem::hidden('google_map_code')->label('Координаты маркеров на карте'),
            ],
        ]),
    ]);
    return $form;
})->delete(null)->create(null);