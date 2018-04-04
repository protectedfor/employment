<?php

Admin::model(\App\Models\BannerPosition::class)->title('Расположение баннеров')->display(function () {
	$display = AdminDisplay::datatablesAsync()->order([[0, 'desc']])->attributes(['stateSave' => false,]);
	$display->with('banners');
    $display->columns([
	    Column::string('id')->label('#'),
        Column::string('title')->label('Заголовок'),
	    Column::custom()->label('Описание')->callback(function ($e) {
		    return strip_tags(str_limit($e->description, 450));
	    })->orderable(false),
	    Column::custom()->label('Активный?')->callback(function ($e) {
		    return $e->active ? '&check;' : '-';
	    })->orderable(false),
	    Column::count('banners')->label('Баннеры')->append(
		    Column::filter('position_id')->model(\App\Models\Banner::class)
	    )->orderable(false),
    ]);
    return $display;
})->createAndEdit(function () {
    $form = AdminForm::form();
    $form->items([
        FormItem::columns()->columns([
            [
	            FormItem::checkbox('active', 'Активный?')->defaultValue(true),
	            FormItem::text('title')->label('Заголовок')->required(),
	            FormItem::text('slug')->label('ЧПУ(заполняется автоматически)'),
            ],
            [
	            FormItem::ckeditor('description')->label('Описание'),
            ]
        ])
    ]);
    return $form;
});