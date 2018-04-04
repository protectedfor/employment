<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Banner;
use App\Models\Meta;
use App\Models\Vars;
use App\User;
use Illuminate\Http\Request;
use Image;
use ReflectionClass;
use Request as StaticRequest;
use Response;

class BaseController extends Controller
{
	public $b_counter;
	public $billingVars;
	public $banners;

    public function __construct(Request $request)
    {
        $page = Meta::where('slug', $request->path())->first();
	    $billingVars = Vars::getBillingVars();
	    $banners = Banner::orderByRaw("-LOG(RAND()) / frequency")->activeOnly()->with('position')->whereHas('position', function ($query) {
//		    $query->whereIn('slug', ['main_top', 'main_center']);
	    })->get();

	    $this->b_counter = 0;
	    $this->billingVars = $billingVars;
	    $this->banners = $banners;

        view()->share([
	        'b_counter' => 0,
	        'billingVars' => $billingVars,
	        'banners' => $banners,
	        'metatitle' => $page ? $page->metatitle : null,
	        'metadesc' => $page ? $page->metadesc : null,
	        'metakeyw' => $page ? $page->metakeyw : null,
        ]);
    }

	public function makeModel($model, $path = 'App\Models\\') {
		return \App::make($path . str_replace(' ', '', ucwords(str_replace('_', ' ', $model))));
	}

	public function getAjaxResponseView($data)
	{
		$status = array_get($data, 'status');
		$arr = [
			'status'  => $this->ajaxResponse(array_get($status, 0), array_get($status, 1), array_get($status, 2)),
			'views' => array_get($data, 'views'),
			'params' => array_get($data, 'params'),
		];
		if(count(array_get($data, 'vars')))
			foreach(array_get($data, 'vars') as $key => $var)
				$arr = array_add($arr, $key, $var);

		return view('partials.mini._getAjaxResponseBlock', $arr);
	}

	public function ajaxResponse($status, $msg, $params = [])
	{
		$arr = ['success' => $status, 'text' => $msg];
		if(count($params))
			foreach($params as $k => $v)
				$arr = array_add($arr, $k, $v);

		return $arr;
	}

	public function accessProtected($obj, $prop) {
		$reflection = new ReflectionClass($obj);
		$property = $reflection->getProperty($prop);
		$property->setAccessible(true);
		return $property->getValue($obj);
	}

	public function generateCode($length = 6)
	{
		$number = '';

		do {
			for ($i = $length; $i--; $i > 0) {
				$number = random_int(100000, 999999);
			}
		} while (!empty(User::where('personal_bill', $number)->first(['id'])));

		return $number;
	}
}
