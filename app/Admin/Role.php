<?php


Admin::model(\App\Role::class)->title('Группы')->display(function ()
{
	$display = AdminDisplay::table();
	$display->columns([
		Column::string('name')->label('Группа'),
		Column::string('display_name')->label('Название группы'),
//		Column::string('description')->label('Описание группы'),
	]);
	return $display;
})->delete(null)->create(null);