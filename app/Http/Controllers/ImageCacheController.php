<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Image;

class ImageCacheController extends BaseController
{
	public function fitImage(Request $request)
	{
		list($width, $height, $fullPath) = $this->getSizeAndPath($request);
		$cachedImage = Image::cache(function($image) use($width, $height, $fullPath) {
			$image->make($fullPath)->fit($width, $height);
		}, 43200);

		return response($cachedImage, 200)->header("Content-Type", "image/jpeg");
	}

	public function resizeImage(Request $request)
	{
		list($width, $height, $fullPath) = $this->getSizeAndPath($request);
		$cachedImage = Image::cache(function($image) use($width, $height, $fullPath) {
			$image->make($fullPath)->resize($width, $height, function ($constraint) {
				$constraint->aspectRatio();
			});
		}, 43200);

		return response($cachedImage, 200)->header("Content-Type", "image/jpeg");
	}

	public function noImage(Request $request)
	{
		list($width, $height, $fullPath) = $this->getSizeAndPath($request);
		$overlay = Image::canvas($width, $height, '#C7C8DB');
		$overlay->text(
			$width . ' x '. $height,
			$overlay->getWidth() / 2,
			$overlay->getHeight() / 2,
			function ( $font ) use($width, $height) {
				/** @var \Intervention\Image\Gd\Font $font */
//				$font->file(public_path('fonts/OpenSansBold/OpenSansBold.ttf'));
				$font->file(5);
				$font->size($width < $height ? $width / 6 : $height / 2);
				$font->color('#311c1c');
				$font->valign('center');
				$font->align('center');
			});

		return $overlay->response();
	}

	private function getSizeAndPath($request)
	{
		$keys = array_keys($request->all());
		$size = explode('_', array_get($keys, 0));
		$width = array_get($size, 0);
		$height = array_get($size, 1);
		$path = array_get($keys, 1);
		$fullPath = strpos($path, '/') === false ? config('admin.imagesUploadDirectory') . '/' . str_replace('_', '.', $path) : str_replace('_', '.', $path);

		return [$width, $height, $fullPath];
	}
}