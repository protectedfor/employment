<?php


Admin::model(\App\Permission::class)->title('Права доступа')->display(function ()
{
	$display = AdminDisplay::table();
	$display->columns([
		Column::string('name')->label('Право доступа'),
		Column::string('display_name')->label('Название права'),
		Column::string('description')->label('Описание права'),
	]);
	return $display;
})->createAndEdit(function ()
{
	$form = AdminForm::form();
	$form->items([
		FormItem::text('name', 'Право доступа')->required()->unique(),
		FormItem::text('display_name', 'Название права')->required(),
		FormItem::text('description', 'Описание права'),
	]);
	return $form;
});