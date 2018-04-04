<?php

Admin::model(\App\Models\Page::class)->title('Страницы')->display(function () {
	$display = AdminDisplay::datatablesAsync()->order([[7, 'asc']])->attributes(['stateSave' => false,]);
    $display->columns([
	    Column::string('id')->label('#'),
        Column::string('name')->label('Название'),
        Column::string('title')->label('Заголовок'),
	    Column::custom()->label('Описание')->callback(function ($e) {
		    return strip_tags(str_limit($e->description, 450));
	    }),
	    Column::string('url')->label('Ссылка'),
	    Column::custom()->label('Позиция')->callback(function ($e) {
		    if ($e->position == 1)
		        return 'слева';
		    else
			    return 'справа';
	    }),
	    Column::order(),
	    Column::string('order')->label('Порядок'),
	    Column::custom()->label('Активный?')->callback(function ($e) {
		    return $e->active ? '&check;' : '-';
	    }),
    ]);
    return $display;
})->createAndEdit(function () {
    $form = AdminForm::form();
    $form->items([
        FormItem::columns()->columns([
            [
	            FormItem::checkbox('active', 'Активный?')->defaultValue(true),
	            FormItem::text('name')->label('Название')->required(),
                FormItem::text('slug')->label('ЧПУ(заполняется автоматически)'),
                FormItem::text('title')->label('Заголовок'),
                FormItem::text('url')->label('Ссылка'),
	            FormItem::select('position', 'Позиция')->options([1 => 'слева', 2 => 'справа'])->required(),
            ],
            [
//	            FormItem::text('order')->label('Порядок'),
	            FormItem::ckeditor('description')->label('Описание'),
            ]
        ])
    ]);
    return $form;
});