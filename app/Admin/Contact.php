<?php
\SleepingOwl\Admin\AssetManager\AssetManager::addScript('//maps.google.com/maps/api/js?key=AIzaSyAByXHJTS_tDZvRl9d1fhb1QmlPU8Cf0wQ');
\SleepingOwl\Admin\AssetManager\AssetManager::addScript('/js/gmaps.js');

Admin::model(\App\Models\Contact::class)->title('Контакты')->display(function () {
    $display = AdminDisplay::datatables();
    $display->columns([
        Column::string('address')->label('Адрес'),
        Column::string('phone')->label('Телефон'),
        Column::string('email')->label('E-mail'),
        Column::string('skype')->label('Skype')
    ]);
    return $display;
})->createAndEdit(function () {
    $form = AdminForm::form();
    $form->items([
        FormItem::columns()->columns([
            [
                FormItem::text('address')->label('Адрес'),
                FormItem::text('work_hours')->label('Часы работ'),
                FormItem::text('stringPhone')->label('Телефон'),
                FormItem::text('email')->label('E-mail')->validationRule('email'),
                FormItem::text('url')->label('URL'),
                FormItem::view('admin.contacts.map'),
                FormItem::hidden('google_map_code')->label('Координаты маркеров на карте'),
            ],
            [
                FormItem::text('facebook_url')->label('Facebook')->validationRule('url'),
	            FormItem::text('twitter_url')->label('Twitter')->validationRule('url'),
	            FormItem::text('instagram_url')->label('Instagram')->validationRule('url'),
//	            FormItem::text('skype')->label('Skype'),
//	            FormItem::text('google_plus_url')->label('Google+')->validationRule('url'),
//	            FormItem::text('ok_url')->label('Odnoklassniki')->validationRule('url'),
//	            FormItem::text('vk_url')->label('Vkontakte')->validationRule('url'),
//                FormItem::text('diesel_url')->label('Diesel.elcat.kg')->validationRule('url')
            ]
        ])
    ]);
    return $form;
})->delete(null)->create(null);