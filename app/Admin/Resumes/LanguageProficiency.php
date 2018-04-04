<?php

Admin::model(\App\Models\Resumes\LanguageProficiency::class)->title('Владения языками')->display(function () {
    $display = AdminDisplay::datatablesAsync();
    $display->apply(function ($query) {
        $query->orderBy('order');
    });
    $display->with();
    $display->filters([

    ]);
    $display->columns([
        Column::string('title')->label('Название'),
	    Column::string('english_slug')->label('Название на английском'),
        Column::custom()->label('Активен?')->callback(function ($e) {
            return $e->active ? '&check;' : '-';
        }),
        Column::order()
    ]);
    return $display;
})->createAndEdit(function ($id) {
    $form = AdminForm::form();
    $form->items([
        FormItem::columns()->columns([
            [
                FormItem::checkbox('active', 'Активен?')->defaultValue(true),
	            FormItem::text('title', 'Название')->required(),
	            FormItem::text('english_slug', 'Название на английском')->required(),
            ], [

            ],
        ]),
    ]);
    return $form;
})->delete(null);