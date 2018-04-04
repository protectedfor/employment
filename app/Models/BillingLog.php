<?php

namespace App\Models;

use App\Models\Vacancies\Vacancy;
use App\User;
use Illuminate\Database\Eloquent\Model;

class BillingLog extends Model
{

	protected $with = [
		'user'
	];

    protected $fillable = [
        'user_id', 'billable_id','billable_type', 'description', 'duration',
	    'change', 'balance', 'active', 'expired', 'started_at'
    ];

    public function billable()
    {
        return $this->morphTo();
    }

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function setActiveAttribute($entity)
	{
		if ($this->active == $entity)
			return;

		if($entity && ($this->description == 'makeInPriority' || $this->description == 'makeFixed') ||
			$this->description == 'publish' || $this->description == 'getContacts')
			$this->attributes['active'] = $entity;
		elseif ($entity) {
			$type = $this->getTypeFromModel($this->billable_type);
			$columnName = Vars::getBillingColumnNameVars()->{$this->description};
			$duration = $this->duration;
			$change = $this->getRate($type, $duration);
			$balance = $this->user->balance - $change;
			$object = $this->billable;

			if ($balance >= 0 && $object->count() > 0) {
				$object->update([$columnName => $entity, 'change' => $change]);
				$success = true;
			} elseif ($balance < 0 || $object->count() == 0 || $object->{$columnName}) {
				$success = false;
				?><a href="/admin/billing_logs"><===BACK</a><?php
				if ($balance < 0)
					dd('Недостаточно средств на счёте пользователя!');
				elseif ($object->count() == 0)
					dd('Активируемый объект был удалён и больше не существует!');
				elseif ($object->first()->{$columnName})
					dd('Данный объект уже активирован! Пожалуйста, подождите пока истечёт срок активации и повторите снова');
			}

			if ($success && $entity) {
				$this->user->update(['balance' => $balance]);
				$this->attributes['change'] = $change;
				$this->attributes['balance'] = $balance;
				$this->attributes['active'] = $entity;
			}

		} elseif (!$entity) {
			return;
		}
	}

	private function getRate($type, $duration)
	{
		return array_get(array_get(Vars::getBillingVars()->{$this->description}, $type), $duration);
	}

	private function getTypeFromModel($model)
	{
		return strtolower(last(explode('\\', $model)));
	}
}
