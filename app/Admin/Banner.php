<?php

Admin::model(\App\Models\Banner::class)->title('Баннеры')->display(function () {
	$display = AdminDisplay::datatablesAsync()->order([[0, 'desc']])->attributes(['stateSave' => false,]);
	$display->with(['position']);
	$display->apply(function($query) {
		if(Request::get('position_id'))
			$query->where('position_id', Request::get('position_id'));
	});
    $display->columns([
	    Column::string('id')->label('#'),
        Column::image('AdminImage')->label('Изображение'),
        Column::string('title')->label('Заголовок'),
	    Column::custom()->label('Описание')->callback(function ($e) {
		    return strip_tags(str_limit($e->description, 450));
	    })->orderable(false),
	    Column::string('url')->label('Ссылка'),
	    Column::custom()->label('Активный?')->callback(function ($e) {
		    return $e->active ? '&check;' : '-';
	    })->orderable(false),
	    Column::string('position.title')->label('Расположение')->orderable(false),
    ]);
    return $display;
})->createAndEdit(function () {
    $form = AdminForm::form();
    $form->items([
        FormItem::columns()->columns([
            [
	            FormItem::checkbox('active', 'Активный?')->defaultValue(true),
	            FormItem::text('title')->label('Заголовок')->required(),
                FormItem::text('url')->label('Ссылка')->required(),
	            FormItem::select('position_id', 'Расположение')->model(\App\Models\BannerPosition::class)->display('title')->required(),
	            FormItem::hidden('frequency')->label('Вероятность появления')->defaultValue(1)->required()->validationRule('integer')->validationRule('min:1'),
	            FormItem::columns()->columns([
		            [
			            FormItem::date('started_at', 'Дата начала')->defaultValue(\Carbon\Carbon::now()),
		            ],
		            [
			            FormItem::date('expired_at', 'Дата деактивации')->defaultValue(\Carbon\Carbon::now()->addMonth()->addDay()),
		            ]
	            ]),
	            FormItem::columns()->columns([
		            [
			            FormItem::text('views')->label('Количество просмотров'),
		            ],
		            [
			            FormItem::text('views_limit')->label('Лимит просмотров'),
		            ]
	            ]),
	            FormItem::columns()->columns([
		            [
			            FormItem::hidden('clicks')->label('Количество кликов'),
		            ],
		            [
			            FormItem::hidden('clicks_limit')->label('Лимит кликов'),
		            ]
	            ]),
            ],
            [
	            FormItem::hidden('iframe')->label('Iframe баннер'),
		        FormItem::image('AdminImage', 'Изображение')->validationRule('required_without:iframe'),
	            FormItem::ckeditor('description')->label('Описание'),
            ]
        ])
    ]);
    return $form;
});