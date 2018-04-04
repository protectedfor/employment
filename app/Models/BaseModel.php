<?php

namespace App\Models;

use App\Custom\CustomCollection;
use Approached\LaravelImageOptimizer\ImageOptimizer;
use Illuminate\Database\Eloquent\Model;
use Image;

class BaseModel extends Model
{
	public static function makeModel($model, $path = 'App\Models\\', $withRelations = false) {
		if(!is_string($path)) {
			$withRelations = $path;
			$path = 'App\Models\\';
		}
		if($cutModel = stristr($model, 's.', true))
			$model = $cutModel;
		$resultedModel = \App::make($path . str_replace(' ', '', ucwords(str_replace('_', ' ', $model))));
		if(!$withRelations)
			$resultedModel = $resultedModel->setEagerLoads([]);

		return $resultedModel;
	}

	/**
	 * Create a new Eloquent Collection instance.
	 *
	 * @param  array  $models
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function newCollection(array $models = [])
	{
		return new CustomCollection($models);
	}

	/**
	 * @param $query
	 */
	public function scopeForAuthUser($query)
	{
		$query->where('user_id', \Auth::id());
	}

	public function scopeNotActive($query)
	{
		$query->where('active', false)->orderBy('created_at', 'desc');
	}
	public function scopeActive($query)
	{
		return $query->where('active', true)->orderBy('created_at', 'desc');
	}
	public function scopeActiveOnly($query)
	{
		return $query->where('active', true);
	}
	public function scopeOrder($query)
	{
		return $query->orderBy('order');
	}
	public function scopeActiveOrder($query)
	{
		return $query->activeOnly()->orderBy('order');
	}
	public function scopeActiveOrderDesc($query)
	{
		return $query->activeOnly()->orderBy('order', 'desc');
	}
	public function scopeModerated($query)
	{
		$query->where('moderated', true);
	}
	public function scopeActiveModerated($query)
	{
		$query->active()->where('moderated', true);
	}
	public function scopeActiveOnlyModerated($query)
	{
		$query->activeOnly()->where('moderated', true);
	}

	public function setAdminImageAttribute($value)
	{
		return $this->attributes['image'] = str_replace(config('admin.imagesUploadDirectory') . '/', '', $value);
	}

	public function getAdminImageAttribute()
	{
		if ($this->exists && $this->attributes['image'])
			return config('admin.imagesUploadDirectory') . '/' . $this->attributes['image'];
		return null;
	}

	public function setAdminFileAttribute($value)
	{
		return $this->attributes['file'] = str_replace(config('admin.imagesUploadDirectory') . '/', '', $value);
	}

	public function getAdminFileAttribute()
	{
		if ($this->exists && $this->attributes['file'])
			return config('admin.imagesUploadDirectory') . '/' . $this->attributes['file'];
		return null;
	}

	public function setAdminImagesAttribute($images)
	{
		self::saved(function($node) use($images) {
			$node->photos()->delete();
			$imgs = [];
			foreach ($images as $img) {
				$img = str_replace(config('admin.imagesUploadDirectory') . '/', '', $img);
				$imgs[] = Photo::create(['imageable_id' => $node->id, 'path' => $img]);
			}
			$node->photos()->saveMany($imgs);
		});
	}

	public function getAdminImagesAttribute()
	{
		$imgs = [];
		if($this->photos->count() > 0)
			foreach ($this->photos as $img) {
				$imgs[] = config('admin.imagesUploadDirectory') . '/' . $img->path;
			}
		return $imgs;
	}

	public function setImage($size, $filename, $type = 'fit')
	{
		$path = strpos($filename, '.') ? $filename : $this->{$filename};
		if($path == '')
			$path = '/img/jpg/nophoto.jpg';
		elseif(strpos($path, 'https://') !== false)
			return $path;
		$fullPath = strpos($path, '/') === false ? config('admin.imagesUploadDirectory') . '/' . $path : public_path($path);
		$file_exists = file_exists($fullPath);
		$sizes = explode('_', $size);
		$w_size = array_get($sizes, 0) !== 'null' ? array_get($sizes, 0) : 0;
		$h_size = array_get($sizes, 1) !== 'null' ? array_get($sizes, 1) : 0;

		if ($file_exists && $path) {
			if (strpos($path, '.gif') || strpos($path, '.svg'))
//				return url($fullPath);
				return route("ic.{$type}Image", [$size, $path]);
			if(strpos($path, '/') === 0)    //DeActivate cache
				$path = substr($path, strrpos($path, '/') + 1);
//			if(strpos($path, '/') === 0)    //Activate cache
//				$path = substr($path, 1);
//			return route("ic.{$type}Image", [$size, $path]);
			$dirPath = config('admin.imagesUploadDirectory') . '/' . $size;
			$pathWithSize = $dirPath . '/'. $path;
			if (!file_exists($dirPath))
				mkdir($dirPath, 0777, true);
			if (!file_exists($pathWithSize)) {
				if($w_size && $h_size)
					$img = \Image::make($fullPath)->fit($w_size, $h_size);
				else
					$img = \Image::make($fullPath)->resize($w_size ? $w_size : 'null', $h_size ? $h_size : 'null', function ($constraint) {
						$constraint->aspectRatio();
					});
				$img->save($pathWithSize);
			}
			return url($pathWithSize);
		} else
			return "http://placehold.it/{$w_size}x{$h_size}";
	}
}
