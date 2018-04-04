<?php

namespace App\Models;

use SleepingOwl\Admin\Traits\OrderableModel;

class TrainingCategory extends BaseModel
{
    use OrderableModel;

    public static function getCategoriesInternalArr()
    {
        $arr = [];
        foreach (self::all() as $cat) {
            $arr[$cat->id] = $cat->location ? "За рубежом - " . $cat->title : "В Кыргызстане - " . $cat->title;
        }
        return $arr;
    }

    public static function getCategoriesExternalArr()
    {
        $arr = [];
        foreach (TrainingCategory::all()->where('location', 1) as $cat) {
            $arr[$cat->id] = $cat->title;
        }
        return $arr;
    }

    public function trainings()
    {
        return $this->hasMany(Training::class, 'category_id');
    }

    public function trainingFilter($filteredBy)
    {
        return $this->trainings->filter(function ($item) use ($filteredBy) {
            return $item['location'] == $filteredBy && $item['moderated'] == 1 && $item->location == $item->category->location;
        });
    }
}
