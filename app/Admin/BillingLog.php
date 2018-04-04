<?php
Admin::model(\App\Models\BillingLog::class)->title('Платные функции на проверке')->display(function () {
	$display = AdminDisplay::tabbed();
	$display->tabs(function () {

		$columns_array = [
			Column::string('id')->label('#'),
			Column::string('user.name')->label('Автор'),
			Column::custom()->label('Объект')->callback(function ($e) {
				switch (true) {
					case ($e->billable_type == 'App\Models\Vacancies\Vacancy'):
						return 'Вакансия';
					case ($e->billable_type == 'App\Models\Company'):
						return 'Компания';
					case ($e->billable_type == 'App\Models\Training'):
						return 'Тренинг';
					default:
						return $e->billable_type;
				}
			}),
			Column::string('billable_id')->label('Номер'),
			Column::custom()->label('Действие')->callback(function ($e) {
				switch (true) {
					case ($e->description == 'makeInPriority'):
						return 'Поднять в списке';
					case ($e->description == 'makeFixed'):
						return 'Прикрепить в списке';
					case ($e->description == 'makeHot'):
						return 'В "Горячие"';
					case ($e->description == 'makeLeading'):
						return 'В "Ведущие работодатели"';
					case ($e->description == 'getContacts'):
						return 'Получить контакты';
					case ($e->description == 'post'):
						return 'Опубликовать';
					default:
						return $e->description;
				}
			}),
			Column::string('user.balance')->label('Достуно на счёте'),
			Column::custom()->label('Активировано?')->callback(function ($e) {
				return $e->active ? '&check;' : '-';
			}),
			Column::custom()->label('В Архиве')->callback(function ($e) {
				return $e->expired ? '&check;' : '-';
			}),
		];

		$tabs = [];
		$all = AdminDisplay::datatables();
		$all->order([[0, 'desc']])->attributes(['stateSave' => false,]);
		$all->apply(function ($query) {
			$query->where('expired', false);
		});
		$all->columns($columns_array);
		$tabs[] = AdminDisplay::tab($all)->label('Все активные(' . \App\Models\BillingLog::where('expired', false)->count() .')')->active(true);

		$all_fixed = AdminDisplay::datatables();
		$all_fixed->order([[0, 'desc']])->attributes(['stateSave' => false,]);
		$all_fixed->apply(function ($query) {
			$query->where('expired', false)->where('description', 'makeFixed');
		});
		$all_fixed->columns($columns_array);
		$tabs[] = AdminDisplay::tab($all_fixed)->label('Прикреплённые в списке(' . \App\Models\BillingLog::where('expired', false)->where('description', 'makeFixed')->count() .')');

		$all_hot = AdminDisplay::datatables();
		$all_hot->order([[0, 'desc']])->attributes(['stateSave' => false,]);
		$all_hot->apply(function ($query) {
			$query->where('expired', false)->where('description', 'makeHot');
		});
		$all_hot->columns($columns_array);
		$tabs[] = AdminDisplay::tab($all_hot)->label('В "Горячие"(' . \App\Models\BillingLog::where('expired', false)->where('description', 'makeHot')->count() .')');

		$all_leading = AdminDisplay::datatables();
		$all_leading->order([[0, 'desc']])->attributes(['stateSave' => false,]);
		$all_leading->apply(function ($query) {
			$query->where('expired', false)->where('description', 'makeLeading');
		});
		$all_leading->columns($columns_array);
		$tabs[] = AdminDisplay::tab($all_leading)->label('В "Ведущие работодатели"(' . \App\Models\BillingLog::where('expired', false)->where('description', 'makeLeading')->count() .')');

		$all_post = AdminDisplay::datatables();
		$all_post->order([[0, 'desc']])->attributes(['stateSave' => false,]);
		$all_post->apply(function ($query) {
			$query->where('expired', false)->where('description', 'post');
		});
		$all_post->columns($columns_array);
		$tabs[] = AdminDisplay::tab($all_post)->label('Опубликовать тренинг(' . \App\Models\BillingLog::where('expired', false)->where('description', 'post')->count() .')');

		$all_expired = AdminDisplay::datatables();
		$all_expired->order([[0, 'desc']])->attributes(['stateSave' => false,]);
		$all_expired->apply(function ($query) {
			$query->where('expired', true);
		});
		$all_expired->columns($columns_array);
		$tabs[] = AdminDisplay::tab($all_expired)->label('В Архиве(' . \App\Models\BillingLog::where('expired', true)->count() .')');
		return $tabs;
	});
	return $display;

})->createAndEdit(function ($id) {
    $form = AdminForm::form();
    $form->items([
        FormItem::columns()->columns([
            [
                FormItem::checkbox('active', 'Активировать?'),
            ], [

            ],
        ]),
    ]);
    return $form;
})->delete(null)->create(null);