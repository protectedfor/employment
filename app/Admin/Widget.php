<?php
Admin::model(\App\Models\Widget::class)->title('Widgets')->alias('widget')->display(function () {
    $display = AdminDisplay::datatables();
    $display->columns([
        Column::string('id')->label('id'),
        Column::string('name')->label('Title'),
        Column::string('key')->label('Key'),
        Column::string('value')->label('Value')
    ]);
    return $display;
})->createAndEdit(function () {
    $form = AdminForm::form();
    $form->items([
        FormItem::columns()->columns([
            [
                FormItem::text('name', 'Title')->required(),
                FormItem::text('key', 'Key')->unique()->required(),
            ], []
        ]),
        FormItem::textarea('value', 'Value')->required()
    ]);
    return $form;
})->delete(function ($id) {
    if ($id == 1 || $id == 2)
        return null;
    return true;
});