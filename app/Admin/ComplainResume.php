<?php

Admin::model(\App\Models\Resumes\ComplainResume::class)->title('Жалобы на резюме')->display(function () {
    $display = AdminDisplay::datatablesAsync();
    $display->apply(function ($query) {

    });
    $display->with();
    $display->filters([

    ]);
    $display->columns([
	    Column::string('id')->label('#'),
        Column::string('resume.career_objective')->label('Резюме'),
        Column::string('user.name')->label('Автор'),
        Column::string('complain.title')->label('Причина'),
	    Column::custom()->label('Описание')->callback(function ($e) {
		    return strip_tags(str_limit($e->description, 450));
	    }),
	    Column::custom()->label('Закрыта?')->callback(function ($e) {
		    return $e->closed ? '&check;' : '-';
	    }),
    ]);
    return $display;
})->createAndEdit(function ($id) {
    $form = AdminForm::form();
    $form->items([
        FormItem::columns()->columns([
            [
                FormItem::checkbox('closed', 'Закрыта ли жалоба?'),
                FormItem::textarea('description', 'Описание'),
            ], [

            ],
        ]),
    ]);
    return $form;
});