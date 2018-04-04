<?php

Admin::model(\App\Models\Article::class)->title('Статьи (полезное)')->display(function () {
    $display = AdminDisplay::datatablesAsync();
    $display->apply(function ($query) {
        $query->orderBy('created_at', 'desc');
    });
//    $display->with();
    $display->filters([

    ]);
	$display->columns([
		Column::checkbox(),
        Column::string('id')->label('#'),
        Column::image('image')->label('Изображение'),
        Column::string('title')->label('Заголовок'),
        Column::custom()->label('Активен?')->callback(function ($e) {
            return $e->active ? '&check;' : '-';
        }),
        Column::datetime('created_at')->label('Дата добавления')
//        Column::order()
    ]);
	$display->actions([
		Column::action('delete')->value('Удалить')->icon('fa-minus')->target('_blank')->callback(function ($collection)
		{
			if($collection->count() > 0) {
				foreach($collection as $col) {
					$col->delete();
				}
				dd('Следующие элементы списка были удалены:', $collection->lists('title', 'id')->all());
			} else
				dd('Не были выбраны элементы списка для удаления! ');
		}),
	]);
    return $display;
})->createAndEdit(function ($id) {
    $form = AdminForm::form();
    $form->items([
        FormItem::columns()->columns([
            [
                FormItem::checkbox('active', 'Активен?')->defaultValue(true),
                FormItem::text('title', 'Заголовок')->required(),
                FormItem::image('image', 'Изображение')->required(),
                FormItem::text('source', 'Источник')->required(),
//                FormItem::text('name', 'Tекст/название'),
                FormItem::text('url', 'Ссылка'),
            ], [
		        FormItem::ckeditor('body', 'Содержимое')->required()
            ],
        ]),
    ]);
    return $form;
});