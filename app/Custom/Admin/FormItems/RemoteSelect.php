<?php

namespace App\Custom\Admin\FormItems;


use Illuminate\Support\Collection;
use Input;
use Response;
use Route;
use SleepingOwl\Admin\AssetManager\AssetManager;
use SleepingOwl\Admin\FormItems\NamedFormItem;
use SleepingOwl\Admin\Interfaces\WithRoutesInterface;
use SleepingOwl\Admin\Repository\BaseRepository;
use Validator;

class RemoteSelect extends NamedFormItem
{
	protected $model;
	protected $display = 'title';
	protected $options = [];
	protected $nullable = false;
	protected $filter = [];
	protected $addDisplay = false;


	public function initialize()
	{
		parent::initialize();

		AssetManager::addStyle('/formItems/remoteSelect/select2.min.css');
		AssetManager::addScript('/formItems/remoteSelect/select2.full.min.js');
	}

	public function render()
	{
		$params = $this->getParams();
		// $params will contain 'name', 'label', 'value' and 'instance'
		return view('admin.formItems.remoteSelect', $params);
	}

	public function getParams()
	{
		return parent::getParams() + [
			'options' => $this->options(),
			'nullable' => $this->isNullable(),
			'model' => $this->model(),
			'display' => $this->display(),
			'addDisplay' => $this->addDisplay(),
			'filter' => $this->filter(),
			'multiSelect' => false
		];
	}

	public function filter($filter = null)
	{
		if (is_null($filter)) {
			return $this->filter;
		}
		array_push($this->filter, $filter);
		return $this;
	}

	public function model($model = null)
	{
		if (is_null($model)) {
			return $this->model;
		}
		$this->model = $model;
		return $this;
	}

	public function options($options = null)
	{
		if (is_null($options)) {
			if (!is_null($this->model()) && !is_null($this->display())) {
				$this->loadOptions();
			}
			$options = $this->options;
			asort($options);
			return $options;
		}
		$this->options = $options;
		return $this;
	}

	public function display($display = null)
	{
		if (is_null($display)) {
			return $this->display;
		}
		$this->display = $display;
		return $this;
	}

	public function addDisplay($addDisplay = null)
	{
		if (is_null($addDisplay)) {
			return $this->addDisplay;
		}
		$this->addDisplay = $addDisplay;
		return $this;
	}

	protected function loadOptions()
	{
		if ($this->value()) {
			$repository = new BaseRepository($this->model());
			$key = $repository->model()->getKeyName();
			$cols = $repository->query()->where($key, $this->value())->get();
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

	public function isNullable()
	{
		return $this->nullable;
	}
}