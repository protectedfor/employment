<?php

Admin::model(\App\Models\Vacancies\Currency::class)->title('Валюты')->display(function () {
    $display = AdminDisplay::datatablesAsync();
    $display->apply(function ($query) {
        $query->orderBy('order');
    });
    $display->with();
    $display->filters([

    ]);
    $display->columns([
        Column::string('title')->label('Название'),
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
            ], [

            ],
        ]),
    ]);
    return $form;
})->delete(null);