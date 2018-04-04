<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meta extends Model
{

    protected $fillable = [
        'slug',
        'metatitle',
        'metakeyw',
        'metadesc'
    ];

	public function metable()
	{
		return $this->morphTo();
	}

	public static function updateMeta($obj, $metaSlug, $metaTitle, $metaKeyWord = null, $metaDesc = null)
	{
		$obj->metas()->delete();
		self::whereSlug($metaSlug)->first() ? self::whereSlug($metaSlug)->first()->delete() : null;
		$metas = self::create(['slug' =>  $metaSlug, 'metatitle' => $metaTitle, 'metakeyw' => $metaKeyWord ? $metaKeyWord : $metaTitle, 'metadesc' => $metaDesc ? $metaDesc : $metaTitle]);
		$obj->metas()->save($metas);
	}
}
