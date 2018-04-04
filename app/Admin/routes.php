<?php

Route::get('', [
	'as' => 'admin.home',
	function ()
	{
		$content = 'Добро пожаловать в административную панель сайта ' . config('app.domain_name');
		return Admin::view($content, config('app.site_name'));
	}
]);

Route::get('mailing',[ 'as' => 'mailing' , 'uses' => '\App\Http\Controllers\PagesController@mailing']);
