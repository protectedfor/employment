<?php

Admin::model(\App\Models\Profile::class)->title('Профили соискателей')->display(function () {
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
        Column::string('FullName')->label('ФИО'),
        Column::string('user.name')->label('Пользователь'),
    ]);
    return $display;
})->createAndEdit(function ($id) {
    $form = AdminForm::form();
    $form->items([
        FormItem::columns()->columns([
            [
                FormItem::text('name', 'Имя')->required(),
                FormItem::text('sname', 'Фамилия')->required(),
                FormItem::text('mname', 'Отчество')->required(),
            ], [
		        FormItem::image('logo', 'Изображения'),
            ],
        ]),
    ]);
    return $form;
})->delete(null)->create(null);