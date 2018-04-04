<?php

namespace App\Custom\Admin\FormItems;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use SleepingOwl\Admin\Repository\BaseRepository;

class FormItemController extends Controller
{
    public function remoteSelect(Request $request, $model, $field)
    {
		$model = str_replace('_', '\\', $model);
		$repository = new BaseRepository($model);
	    $items = $repository->model();

	    if($request->get('filters')) {
		    for ($i = 1; $i <= $request->get('filters'); $i++) {
			    $columnArr = explode('.', $request->get('column' . $i));
			    $sign = $request->get('sign' . $i) ? $request->get('sign' . $i) : '=';
			    $searched = $request->get('searched' . $i);

			    if(count($columnArr) == 1)
				    $items = $items->where($columnArr[0], $sign, $searched);
			    else
				    $items = $items->whereHas($columnArr[0], function ($query) use($columnArr, $sign, $searched, $i) {
					    $query->where($columnArr[1], $sign, $searched);
				    });
		    }
	    }
	    $items = $items->where($field, 'like', '%' . $request->get('q') . '%')->get();

	    return ['items' => $items];
    }
}
