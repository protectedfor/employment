<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class TrainingResponse extends Model
{
    protected $with = [
        'training',
    ];

    protected $fillable = [
        'training_id',
        'user_id',
    ];

    public function training()
    {
        return $this->belongsTo(Training::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
