<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\BillingLog;
use App\Models\Vars;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Events\SendNotificationEvent;
use Event;
use Auth;
use Session;
use Request as UrlRequest;

class BillingLogsController extends BaseController
{
	public function makeLeading(Request $request, $id)
	{
		$this->billItem($request, $id, 'makeLeading');

		return redirect()->back()->with('success', 'Компания будет добавлена в "Ведущие работодатели" сразу после активации администратором!');
	}

	public function getContacts(Request $request, $id)
	{
		$this->billItem($request, $id, 'getContacts');

		return redirect()->back()->with('success', 'Контакты соискателей доступны для Вас');
	}

	public function makeInPriority(Request $request, $id)
	{
		$this->billItem($request, $id, 'makeInPriority');

		return redirect()->back()->with('success', 'Вакансия поднята в списке!');
	}

	public function makeFixed(Request $request, $id)
	{
		$this->billItem($request, $id, 'makeFixed');

		return redirect()->back()->with('success', 'Вакансия прикреплена в списке!');
	}

	public function makeHot(Request $request, $id)
	{
		$this->billItem($request, $id, 'makeHot');

		return redirect()->back()->with('success', 'Вакансия будет добавлена в горячие сразу после активации администратором!');
	}


	public function publish(Request $request, $id)
	{
		$this->billItem($request, $id, 'publish');

		return redirect()->route('trainings.profile')->with('success', 'Ваш тренинг размещен в разделе «Обучение». Успехов с организацией курса/тренинга!');
	}

	private function billItem($request, $id, $description)
	{
		$type = $request->get('type');
		$namespace = $type == 'vacancy' ? 'App\Models\Vacancies\\' : ($type == 'resume' ? 'App\Models\Resumes\\' : 'App\Models\\');
		$billedItem = $this->makeModel($type, $namespace)->findOrFail($id);
		$duration = $request->get('duration');
		$sum = array_get(array_get(Vars::getBillingVars()->{$description}, $type), $duration);
		$columnName = Vars::getBillingColumnNameVars()->{$description};
		$user = Auth::user();

		$withoutActivation = true;
		$updateItem = [$columnName => true];
		if($description == 'makeLeading')
			$withoutActivation = false;
		elseif($description == 'makeInPriority')
			$updateItem = [$columnName => Carbon::now()];
		elseif($description == 'makeHot')
			$withoutActivation = false;
		elseif($description == 'publish')
			$updateItem = ['created_at' => Carbon::now(), 'moderated' => true];

		if($user->balance < $sum)
			return redirect()->back()->with('error', 'У вас недостаточно средств на счёте!');

		$balance = $withoutActivation ? $user->balance - $sum : $user->balance;
		$billingLog = BillingLog::create(['user_id' => $user->id, 'description' => $description, 'duration' => $duration,
			'change' => $withoutActivation ? $sum : 0, 'balance' => $balance, 'active' => $withoutActivation ? true : false]);
		$billedItem->billingLog()->save($billingLog);
		if($withoutActivation) {
			$user->update(['balance' => $balance]);
			if($updateItem)
				$billedItem->update($updateItem);
		}

		return true;
	}
}
