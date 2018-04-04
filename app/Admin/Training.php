<?php

\SleepingOwl\Admin\AssetManager\AssetManager::addScript('//maps.google.com/maps/api/js?key=AIzaSyAByXHJTS_tDZvRl9d1fhb1QmlPU8Cf0wQ');
\SleepingOwl\Admin\AssetManager\AssetManager::addScript('/js/gmaps.js');



Admin::model(\App\Models\Training::class)->title('Тренинги')->display(function () {
    $display = AdminDisplay::datatablesAsync();
    $display->apply(function ($query) {
        $query->orderBy('moderated')->orderBy('created_at', 'desc');
    });
    $display->with();
    $display->filters([

    ]);
	$display->columns([
		Column::checkbox(),
        Column::string('id')->label('#'),
        Column::string('title')->label('Название'),
        Column::string('coordinator')->label('Организатор'),
        Column::string('user.name')->label('Пользователь'),
        Column::custom()->label('Опубликовано?')->callback(function ($e) {
            return $e->moderated ? '&check;' : '-';
        }),
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
//                FormItem::checkbox('moderated', 'Проверено?')->defaultValue(true),
//                FormItem::checkbox('location', 'За пределами Кыргызстана?'),
                FormItem::radio('location', 'Курсы будут проводиться:')->options([0 => 'В Кыргызстане', 1 => 'За рубежом']),
//	            FormItem::select('user_id', 'Автор')->model(\App\User::class)->display('name')->required(),
	            FormItem::image('photo', 'Изображения'),
	            FormItem::text('title', 'Название')->required(),
	            FormItem::text('coordinator', 'Организатор')->required(),

//	            isset($id) && \App\Models\Training::findOrfail($id)->location ?
//	            FormItem::select('category_id', 'Категория тренинга')->options(\App\Models\TrainingCategory::getCategoriesExternalArr())->required() :
//	            FormItem::select('category_id', 'Категория тренинга')->options(\App\Models\TrainingCategory::getCategoriesInternalArr())->required(),

                FormItem::select('category_id', 'Категория тренинга')->options(\App\Models\TrainingCategory::getCategoriesInternalArr())->required(),
	            FormItem::text('price', 'Цена')->required(),
	            FormItem::text('start_date', 'Дата начала тренинга')->required(),
	            FormItem::text('duration', 'Продолжительность'),
	            FormItem::text('schedule', 'Время проведения занятий'),
	            FormItem::text('place', 'Место проведения'),
	            FormItem::text('contacter', 'Контактное лицо'),
	            FormItem::text('coach', 'Тренер'),
            ], [
		        FormItem::text('email', 'Email')->validationRule('email'),
		        FormItem::text('site', 'Сайт'),
		        FormItem::text('address', 'Адрес'),
		        FormItem::view('admin.contacts.map'),
		        FormItem::hidden('google_map_code')->label('Координаты маркеров на карте'),
		        FormItem::ckeditor('description', 'Описание'),
		        FormItem::date('expires_at', 'Крайний срок:')->validationRule('after:' . \Carbon\Carbon::now()),
            ],
        ]),
    ]);
    return $form;
})->create(null);