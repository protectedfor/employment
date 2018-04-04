<?php

Admin::menu()->url('/')->label('Главная')->icon('fa-dashboard');

Admin::menu()->label('Вакансии')->icon('fa-bullseye')->items(function () {
	Admin::menu(\App\Models\Vacancies\Vacancy::class)->url('vacancies?moderated=0')->label('Ожидаюшие проверки')->icon('fa-users');
	Admin::menu(\App\Models\Vacancies\Vacancy::class)->url('vacancies?moderated=1')->label('Проверенные')->icon('fa-users');
});

Admin::menu()->label('Резюме')->icon('fa-bullseye')->items(function () {
	Admin::menu(\App\Models\Resumes\Resume::class)->url('resumes?moderated=0')->label('Ожидаюшие проверки')->icon('fa-users');
	Admin::menu(\App\Models\Resumes\Resume::class)->url('resumes?moderated=1')->label('Проверенные')->icon('fa-users');
});

Admin::menu(\App\Models\Training::class)->label('Тренинги')->icon('fa-users');
Admin::menu(\App\Models\Article::class)->label('Статьи (полезное)')->icon('fa-users');
Admin::menu(\App\Models\Company::class)->label('Компании')->icon('fa-users');
Admin::menu(\App\Models\Profile::class)->label('Профили')->icon('fa-users');

Admin::menu()->label('Данные для вакансий')->icon('fa-bullseye')->items(function () {
    Admin::menu(\App\Models\Vacancies\Scope::class)->label('Сферы деятельности')->icon('fa-users');
    Admin::menu(\App\Models\Vacancies\Education::class)->label('Образование')->icon('fa-users');
    Admin::menu(\App\Models\Vacancies\Busyness::class)->label('Занятость')->icon('fa-users');
});

Admin::menu()->label('Данные для резюме')->icon('fa-bullseye')->items(function () {
    Admin::menu(\App\Models\Resumes\Citizenship::class)->label('Гражданства')->icon('fa-users');
    Admin::menu(\App\Models\Resumes\City::class)->label('Города (места работы)')->icon('fa-users');
    Admin::menu(\App\Models\Resumes\Language::class)->label('Языки')->icon('fa-users');
    Admin::menu(\App\Models\Resumes\LanguageProficiency::class)->label('Владения языками')->icon('fa-users');
});

Admin::menu()->label('Данные для тренингов')->icon('fa-bullseye')->items(function () {
	Admin::menu(\App\Models\TrainingCategory::class)->label('Категории тренингов')->icon('fa-users');
});

Admin::menu()->label('Жалобы')->icon('fa-bullseye')->items(function () {
	Admin::menu(\App\Models\Vacancies\ComplainVacancy::class)->label('Жалобы на вакансии')->icon('fa-users');
	Admin::menu(\App\Models\Resumes\ComplainResume::class)->label('Жалобы на резюме')->icon('fa-users');
	Admin::menu(\App\Models\Vacancies\Complain::class)->label('Причины жалоб')->icon('fa-users');
});

Admin::menu(\App\Models\Vacancies\Currency::class)->label('Валюты')->icon('fa-users');
Admin::menu(\App\Models\BillingLog::class)->label('Платные функции на проверке')->icon('fa-users');

Admin::menu()->label('Мерчант')->icon('fa-bullseye')->items(function () {
    Admin::menu(\App\Models\Merchant\MobilnikPayment::class)->label('Mobilnik.kg')->icon('fa-money');
});

Admin::menu()->label('Управление баннерами')->icon('fa-bullseye')->items(function () {
	Admin::menu(\App\Models\BannerPosition::class)->label('Расположение баннеров')->icon('fa-bullseye');
	Admin::menu(\App\Models\Banner::class)->label('Баннеры')->icon('fa-money');
});

Admin::menu(\App\Models\Page::class)->label('Страницы')->icon('fa-file-o');
Admin::menu(\App\Models\MainBackground::class)->label('Обои на главной странице')->icon('fa-file-o');

Admin::menu()->label('Рассылка новостей')->icon('fa-bullseye')->items(function () {
	Admin::menu(\App\Models\Mailing::class)->label('Содержание рассылки')->icon('fa-file-text');
	Admin::menu(\App\Models\MailingEmail::class)->label('Адреса получателей')->icon('fa-users');
});

Admin::menu()->label('Управление пользователями')->icon('fa-cogs')->items(function () {
    foreach (\App\Role::all() as $role):
        Admin::menu()->url('users?role_id=' . $role->id)->label($role->display_name)->icon('fa-users');
    endforeach;
//    Admin::menu(\App\Models\Company::class)->label('Компании')->icon('fa-users');
    Admin::menu(\App\Role::class)->label('Группы')->icon('fa-users');
//    Admin::menu(\App\Permission::class)->label('Права доступа')->icon('fa-users');
});

Admin::menu()->label('Настройки')->icon('fa-cogs')->items(function () {
    Admin::menu()->url('contacts/' . ((\App\Models\Contact::all()->first()) ? \App\Models\Contact::all()->first()->getKey() : '1') . '/edit')->label('Контакты')->icon('fa-phone');
    Admin::menu(\App\Models\Widget::class)->label('Виджеты')->icon('fa-sliders');
	Admin::menu(\App\Models\Meta::class)->label('Мета данные')->icon('fa-file-o');
});