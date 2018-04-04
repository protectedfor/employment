<?php

Admin::model(\App\Models\Mailing::class)->title('Рассылка новостей')->display(function () {

	$mailingEmails = \App\Models\MailingEmail::where('subscribed', true)->get();

    $display = AdminDisplay::datatablesAsync();
    $display->columns([
        Column::string('title')->label('Заголовок'),
	    Column::custom()->label('Описание')->callback(function ($e) {
		    return str_limit(strip_tags($e->description, 450));
	    }),
        Column::custom()->label('Рассылка новостей')->callback(function ($instance) {
	        if($instance->active && $instance->sending_date->addMinutes(10) < \Carbon\Carbon::now())
                return "<a href='" . route('mailing', ['mailing_id' => $instance->id]) . "' class='btn btn-success'>Сделать рассылку</a>";
	        else
		        return "<a href='" . route('mailing', ['mailing_id' => $instance->id]) . "' class='btn btn-danger' disabled>Сделать рассылку</a>";
        }),
        Column::custom()->label('Отправлено')->callback(function ($instance) {
            return $instance->sending_date > \Carbon\Carbon::minValue() ? $instance->sending_date : '-';
        }),
	    Column::custom()->label('Ещё не отправлено (кол-во пользователей)')->callback(function ($instance) use ($mailingEmails) {
		    return $mailingEmails->filter(function ($item) use ($instance) {return $item['last_title'] != $instance->id;})->count();
	    }),
	    Column::custom()->label('Активная?')->callback(function ($instance) {
		    return $instance->active ? '&check;' : '-';
	    }),
    ]);
    return $display;
})->createAndEdit(function () {
    $form = AdminForm::form();
    $form->items([
        FormItem::columns()->columns([
            [
	            FormItem::checkbox('active', 'Активен?')->defaultValue(true),
                FormItem::text('title', 'Заголовок')->required(),
                FormItem::ckeditor('description', 'Описание')->required(),
            ],
            [
            ],
        ]),
    ]);
    return $form;
});