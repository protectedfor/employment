<?php

namespace App\Models\Vacancies;

use App\Models\BaseModel;
use SleepingOwl\Admin\Traits\OrderableModel;

class Scope extends BaseModel
{
    use OrderableModel;

    public function vacancies()
    {
        return $this->hasMany(Vacancy::class);
    }

}
