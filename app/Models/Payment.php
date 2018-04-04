<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
	protected $dates = [];

	protected $fillable = [
		'user_id', 'name', 'post', 'INN', 'email', 'sum', 'type'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
