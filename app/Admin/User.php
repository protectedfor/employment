<?php

/*
 * This is a simple example of the main features.
 * For full list see documentation.
 */

Admin::model('App\User')->title('Пользователи')->display(function () {
    $display = AdminDisplay::datatablesAsync();
    $display->apply(function ($query) {
        $query->whereHas('roles', function ($q) {
            $q->where('id', Request::get('role_id'));
        })->orderBy('created_at', 'desc');
    });
    $display->columns([
        Column::checkbox(),
        Column::string('id')->label('#'),
        Column::string('name')->label('Имя'),
        Column::string('email')->label('Email'),
	    Request::get('role_id') == 2 ?
            Column::string('company.phone')->label('Телефон') :
            Column::string('profile.phone')->label('Телефон'),
        Column::lists('roles.display_name')->label('Группа'),
        Column::string('personal_bill')->label('Лицевой счет'),
        Column::string('balance')->label('Баланс'),
	    Column::custom()->label('Активирован')->callback(function ($e) {
		    return $e->activated ? '&check;' : '-';
	    }),
    ]);
	$display->actions([
		Column::action('delete')->value('Удалить')->icon('fa-minus')->target('_blank')->callback(function ($collection)
		{
			if($collection->count() > 0) {
				foreach($collection as $col) {
					$col->delete();
				}
				dd('Следующие элементы списка были удалены:', $collection->lists('name', 'id')->all());
			} else
				dd('Не были выбраны элементы списка для удаления! ');
		}),
	]);
    return $display;
})->createAndEdit(function () {
    $form = AdminForm::form();
    $form->items([
        FormItem::text('name', 'Имя')->required(),
        FormItem::text('email', 'Email')->required()->unique(),
        FormItem::text('personal_bill', 'Лицевой счет'),
        FormItem::text('balance', 'Баланс'),
        FormItem::text('phone', 'Телефон'),
//        FormItem::multiselect('roles', 'Роль')->model(\App\Role::class)->display('name')
    ]);
    return $form;
});