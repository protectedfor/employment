<?php

Admin::model(\App\Models\TrainingCategory::class)->title('Категории тренингов')->display(function () {
    $display = AdminDisplay::datatablesAsync();
    $display->apply(function ($query) {
        $query->orderBy('order');
    });
    $display->with();
    $display->filters([

    ]);
    $display->columns([
        Column::string('title')->label('Название'),
	    Column::custom()->label('Тренинг будет проводиться')->callback(function ($e) {
		    return !$e->location ? 'В Кыргызстане' : 'За рубежом';
	    }),
        Column::custom()->label('Активен?')->callback(function ($e) {
            return $e->active ? '&check;' : '-';
        }),
        Column::order()
    ]);
    return $display;
})->createAndEdit(function ($id) {
    $form = AdminForm::form();
    $form->items([
        FormItem::columns()->columns([
            [
                FormItem::checkbox('active', 'Активен?')->defaultValue(true),
	            FormItem::text('title', 'Название')->required(),
	            FormItem::select('location', 'Тренинг будет проводиться')->options([ 0 =>'В Кыргызстане', 1 => 'За рубежом'])->required(),
            ], [

            ],
        ]),
    ]);
    return $form;
})->delete(null);