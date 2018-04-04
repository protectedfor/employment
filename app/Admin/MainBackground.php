<?php

Admin::model(\App\Models\MainBackground::class)->title('Обои на главной странице')->display(function () {
	$display = AdminDisplay::datatablesAsync()->order([[4, 'asc']])->attributes(['stateSave' => false,]);
    $display->columns([
	    Column::string('id')->label('#'),
        Column::image('path')->label('Изображение'),
	    Column::custom()->label('Описание')->callback(function ($e) {
		    return strip_tags(str_limit($e->description, 450));
	    }),
	    Column::string('url')->label('Ссылка'),
        Column::order(),
        Column::string('order')->label('Порядок'),
		Column::custom()->label('Активный?')->callback(function ($e) {
			return $e->active ? '&check;' : '-';
		}),
    ]);
    return $display;
})->createAndEdit(function ($id) {
    $form = AdminForm::form();
    $form->items([
        FormItem::columns()->columns([
            [
	            FormItem::checkbox('active', 'Активный?')->defaultValue(true),
                FormItem::image('path', 'Изображение')->required(),
                FormItem::text('description', 'Описание'),
	            FormItem::text('url')->label('Ссылка'),
            ], [

            ],
        ]),
    ]);
    return $form;
});