<?php

namespace App\Custom\Admin\FormItems;

use Illuminate\Support\Collection;
use SleepingOwl\Admin\Repository\BaseRepository;

class RemoteMultiSelect extends RemoteSelect
{
	public function getParams()
	{
		return [
			'options' => $this->options(),
			'nullable' => $this->isNullable(),
			'model' => $this->model(),
			'display' => $this->display(),
			'addDisplay' => $this->addDisplay(),
			'multiSelect' => true,
		] + parent::getParams();
	}

	public function value()
	{
		$value = parent::value();
		if ($value instanceof Collection && $value->count() > 0) {
			$value = $value->lists($value->first()->getKeyName());
		}
		if ($value instanceof Collection) {
			$value = $value->toArray();
		}
		return $value;
	}

	protected function loadOptions()
	{
		if ($this->value()) {
			$repository = new BaseRepository($this->model());
			$key = $repository->model()->getKeyName();
			$cols = $repository->query()->whereIn($key, $this->value())->get();
			$options = [];
			$addDisplay = $this->addDisplay();
			$delimiter = $addDisplay && is_array($addDisplay) && count($addDisplay) > 1 ? array_get($addDisplay, 1) : ' ';
			if($addDisplay && is_array($addDisplay))
				$addDisplay = array_get($addDisplay, 0);
			if ($cols instanceof Collection) {
				foreach ($cols as $col) {
					$options = array_add($options, $col->{$key}, $col->{$this->display()} . ($addDisplay && $col->{$addDisplay} ? $delimiter . $col->{$addDisplay} : ''));
				}
			}
			$this->options($options);
		}
	}
}