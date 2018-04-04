<?php

namespace App\Models;

class Vars extends BaseModel
{
	public static function getFilteredVars($initialVars, $allowed) {
		$filteredArr = array_filter(
			$initialVars,
			function ($key) use ($allowed) {
				if(strpos($key, $allowed) !== false) return $key;
			},
			ARRAY_FILTER_USE_KEY
		);
		array_pull($filteredArr, 'b_counter'); array_pull($filteredArr, 'b_item');

		return $filteredArr;
	}

	public static function getBillingVars() {
		$col = \App::make(self::class);

		$col->makeLeading = [
			'company' => [
				30 => 3000,
			],
		];

		$col->getContacts = [
			'company' => [
				7 => 2500,
				15 => 3500,
				30 => 5000,
			],
		];

		$col->makeInPriority = [
			'vacancy' => [
				0 => 50,
			],
		];

		$col->makeFixed = [
			'vacancy' => [
				7 => 300,
			],
		];

		$col->makeHot = [
			'vacancy' => [
				7 => 400,
			],
		];

		$col->publish = [
			'training' => [
				30 => 500,
			],
		];

		return $col;
	}

	public static function getBillingColumnNameVars() {
		$col = \App::make(self::class);

		$col->makeInPriority = 'in_priority';
		$col->makeFixed = 'is_fixed';
		$col->makeHot = 'is_hot';
		$col->makeLeading = 'is_leading';
		$col->getContacts = 'get_contacts';

		return $col;
	}

	public static function getAdminVars() {
		$allVars = Widget::all()->keyBy('key');
		$col = \App::make(self::class);

		foreach ($allVars as $key => $var)
			$col->{camel_case($key)} = $var->value;

		return $col;
	}
}
