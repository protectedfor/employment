<?php

namespace App\Custom;

use App\Models\Club;
use App\Models\Vars;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;

class CustomCollection extends Collection
{
	public function filterArrFix($arr_or_filteredBy, $sign = '==', $optionalArr = null)
	{
		$filteredResult = new CustomCollection();
		$namedArr = is_array($arr_or_filteredBy);
		if(is_array($arr_or_filteredBy))
			$arr = $arr_or_filteredBy;
		else
			$arr = !is_array($optionalArr) ? [$optionalArr] : $optionalArr;

		foreach($arr as $k => $arg) {
			$filteredBy =  $namedArr ? $k : $arr_or_filteredBy;
			!count($filteredResult) ? $object = $this : $object = $filteredResult;

			if($sign == '!=')
				$filteredResult = $object->filterFix($filteredBy, $sign, $arg);
			elseif($namedArr && $sign != 'not_strict')
				$filteredResult = $object->filterFix($filteredBy, '==', $arg);
			else
				$filteredResult = $filteredResult->merge($this->filterFix($filteredBy, $sign, $arg))->unique();
		}

		return $filteredResult;
	}

	public function filterFix($filteredBy, $sign, $arg, $givenObj = null) {
		$givenObj ? $obj = $givenObj : $obj = $this;
		return $obj->filter(function ($item) use ($filteredBy, $sign, $arg) {
			if(count(explode('.', $filteredBy)) > 1)
				return $this->filterHasMany($item, $filteredBy, $sign, $arg);
			else {
				if($sign == '!=' || $sign == '!==')
					return $item[$filteredBy] != $arg;
				elseif($sign == '<')
					return $item[$filteredBy] < $arg;
				elseif($sign == '<=' || $sign == '<==')
					return $item[$filteredBy] <= $arg;
				elseif($sign == '>')
					return $item[$filteredBy] > $arg;
				elseif($sign == '>=' || $sign == '>==')
					return $item[$filteredBy] >= $arg;
				elseif($sign == 'like')
					return stristr($item[$filteredBy], $arg);
				elseif($sign == 'not like')
					return !stristr($item[$filteredBy], $arg);
				else
					return $item[$filteredBy] == $arg;
			}
		});
	}

	public function filterHasMany($item, $filteredBy, $sign, $arg) {
		$filteredByArr = explode('.', $filteredBy);
		$relationItems = $item->{array_get($filteredByArr, 0)};
		$relationItems = is_object($relationItems) && !($relationItems instanceof Collection) ? (new CustomCollection())->add($relationItems) : $relationItems;

		return count($relationItems) ? $this->filterFix(array_get($filteredByArr, 1), $sign, $arg, $relationItems)->count() : false;
	}

	public function toHierarchy()
	{
		$dict = $this->getDictionary();

		// Enforce sorting by $orderColumn setting in Baum\Node instance
		uasort($dict, function ($a, $b) {
			return ($a->getOrder() >= $b->getOrder()) ? 1 : -1;
		});

		return new CustomCollection($this->hierarchical($dict));
	}

	protected function hierarchical($result)
	{
		foreach ($result as $key => $node)
			$node->setRelation('children', new CustomCollection);

		$nestedKeys = array();

		foreach ($result as $key => $node) {
			$parentKey = $node->getParentId();

			if (!is_null($parentKey) && array_key_exists($parentKey, $result)) {
				$result[$parentKey]->children[] = $node;

				$nestedKeys[] = $node->getKey();
			}
		}

		foreach ($nestedKeys as $key)
			unset($result[$key]);

		return $result;
	}

	public function customPaginator($perPage) {
		$allItems = $this;
		$input = \Input::all();
		if (isset($input['page']) && !empty($input['page'])) { $currentPage = $input['page']; } else { $currentPage = 1; }
		$items = $allItems->forPage($currentPage, $perPage); //Filter the page var
		$pagedItems = new Paginator($items, count($allItems), $perPage, $currentPage);

		return $pagedItems->setPath(request()->getPathInfo());
	}

//	>>--------------------------------------------------------------------------------------------------------



	/**
	 * Find a model in the collection by key.
	 *
	 * @param  mixed  $key
	 * @param  mixed  $default
	 * @return \Illuminate\Database\Eloquent\Model
	 */
	public function find($key, $default = null)
	{
		if ($key instanceof Model) {
			$key = $key->getKey();
		}

		return Arr::first($this->items, function ($itemKey, $model) use ($key) {
			return $model->getKey() == $key;
		}, $default);
	}

	/**
	 * Load a set of relationships onto the collection.
	 *
	 * @param  mixed  $relations
	 * @return $this
	 */
	public function load($relations)
	{
		if (count($this->items) > 0) {
			if (is_string($relations)) {
				$relations = func_get_args();
			}

			$query = $this->first()->newQuery()->with($relations);

			$this->items = $query->eagerLoadRelations($this->items);
		}

		return $this;
	}

	/**
	 * Add an item to the collection.
	 *
	 * @param  mixed  $item
	 * @return $this
	 */
	public function add($item)
	{
		$this->items[] = $item;

		return $this;
	}

	/**
	 * Determine if a key exists in the collection.
	 *
	 * @param  mixed  $key
	 * @param  mixed  $value
	 * @return bool
	 */
	public function contains($key, $value = null)
	{
		if (func_num_args() == 2) {
			return parent::contains($key, $value);
		}

		if ($this->useAsCallable($key)) {
			return parent::contains($key);
		}

		$key = $key instanceof Model ? $key->getKey() : $key;

		return parent::contains(function ($k, $m) use ($key) {
			return $m->getKey() == $key;
		});
	}

	/**
	 * Fetch a nested element of the collection.
	 *
	 * @param  string  $key
	 * @return static
	 *
	 * @deprecated since version 5.1. Use pluck instead.
	 */
	public function fetch($key)
	{
		return new static(Arr::fetch($this->toArray(), $key));
	}

	/**
	 * Get the array of primary keys.
	 *
	 * @return array
	 */
	public function modelKeys()
	{
		return array_map(function ($m) {
			return $m->getKey();
		}, $this->items);
	}

	/**
	 * Merge the collection with the given items.
	 *
	 * @param  \ArrayAccess|array  $items
	 * @return static
	 */
	public function merge($items)
	{
		$dictionary = $this->getDictionary();

		foreach ($items as $item) {
			$dictionary[$item->getKey()] = $item;
		}

		return new static(array_values($dictionary));
	}

	/**
	 * Diff the collection with the given items.
	 *
	 * @param  \ArrayAccess|array  $items
	 * @return static
	 */
	public function diff($items)
	{
		$diff = new static;

		$dictionary = $this->getDictionary($items);

		foreach ($this->items as $item) {
			if (! isset($dictionary[$item->getKey()])) {
				$diff->add($item);
			}
		}

		return $diff;
	}

	/**
	 * Intersect the collection with the given items.
	 *
	 * @param  \ArrayAccess|array  $items
	 * @return static
	 */
	public function intersect($items)
	{
		$intersect = new static;

		$dictionary = $this->getDictionary($items);

		foreach ($this->items as $item) {
			if (isset($dictionary[$item->getKey()])) {
				$intersect->add($item);
			}
		}

		return $intersect;
	}

	/**
	 * Return only unique items from the collection.
	 *
	 * @param  string|callable|null  $key
	 * @return static
	 */
	public function unique($key = null)
	{
		if (! is_null($key)) {
			return parent::unique($key);
		}

		return new static(array_values($this->getDictionary()));
	}

	/**
	 * Returns only the models from the collection with the specified keys.
	 *
	 * @param  mixed  $keys
	 * @return static
	 */
	public function only($keys)
	{
		$dictionary = Arr::only($this->getDictionary(), $keys);

		return new static(array_values($dictionary));
	}

	/**
	 * Returns all models in the collection except the models with specified keys.
	 *
	 * @param  mixed  $keys
	 * @return static
	 */
	public function except($keys)
	{
		$dictionary = Arr::except($this->getDictionary(), $keys);

		return new static(array_values($dictionary));
	}

	/**
	 * Make the given, typically hidden, attributes visible across the entire collection.
	 *
	 * @param  array|string  $attributes
	 * @return $this
	 */
	public function withHidden($attributes)
	{
		$this->each(function ($model) use ($attributes) {
			$model->withHidden($attributes);
		});

		return $this;
	}

	/**
	 * Get a dictionary keyed by primary keys.
	 *
	 * @param  \ArrayAccess|array  $items
	 * @return array
	 */
	public function getDictionary($items = null)
	{
		$items = is_null($items) ? $this->items : $items;

		$dictionary = [];

		foreach ($items as $value) {
			$dictionary[$value->getKey()] = $value;
		}

		return $dictionary;
	}

	/**
	 * Get a base Support collection instance from this collection.
	 *
	 * @return \Illuminate\Support\Collection
	 */
//	public function toBase()
//	{
//		return new BaseCollection($this->items);
//	}
}
