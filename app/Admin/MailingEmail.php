<?php

Admin::model(\App\Models\MailingEmail::class)->title('Адреса получателей')->display(function () {

    $display = AdminDisplay::datatablesAsync();
    $display->columns([
        Column::string('email')->label('Email'),
	    Column::custom()->label('Подписан?')->callback(function ($instance) {
		    return $instance->subscribed ? '&check;' : '-';
	    }),
	    Column::string('last_title')->label('Последняя полученная рассылка'),
    ]);
    return $display;
})->createAndEdit(function () {
    $form = AdminForm::form();
    $form->items([
        FormItem::columns()->columns([
            [
	            FormItem::checkbox('subscribed', 'Подписан?')->defaultValue(true),
                FormItem::text('email', 'Email')->validationRule('email')->required(),
            ],
            [
            ],
        ]),
    ]);
    return $form;
});