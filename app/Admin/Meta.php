<?php

Admin::model(\App\Models\Meta::class)->title('Meta data')->display(function () {
    $display = AdminDisplay::datatablesAsync();
	$display->apply(function ($query) {
		$query->orderBy('created_at', 'desc');
	});
    $display->with();
    $display->filters([

    ]);
    $display->columns([
	    Column::string('id')->label('#'),
        Column::string('slug')->label('ЧПУ'),
        Column::string('metatitle')->label('Metatitle'),
        Column::string('metakeyw')->label('Metakeyw'),
        Column::string('metadesc')->label('Metadesc'),
    ]);
    return $display;
})->createAndEdit(function () {
    $form = AdminForm::form();
    $form->items([
        FormItem::columns()->columns([
            [
                FormItem::text('slug', 'ЧПУ')->required()->unique(),
                FormItem::text('metatitle', 'Metatitle'),
                FormItem::text('metakeyw', 'Metakeyw'),
                FormItem::textarea('metadesc', 'Metadesc'),
            ], [

            ],
        ]),
    ]);
    return $form;
});