<?php

Admin::model(\App\Models\Vacancies\Complain::class)->title('Причины жалоб')->display(function () {
    $display = AdminDisplay::datatablesAsync();
    $display->apply(function ($query) {
        $query->orderBy('order');
    });
    $display->with();
    $display->filters([

    ]);
    $display->columns([
        Column::string('title')->label('Название'),
	    Column::custom()->label('Описание')->callback(function ($e) {
		    return strip_tags(str_limit($e->description, 450));
	    }),
        Column::order()
    ]);
    return $display;
})->createAndEdit(function ($id) {
    $form = AdminForm::form();
    $form->items([
        FormItem::columns()->columns([
            [
                FormItem::text('title', 'Название')->required(),
                FormItem::text('description', 'Описание'),
            ], [

            ],
        ]),
    ]);
    return $form;
});