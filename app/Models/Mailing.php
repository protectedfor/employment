<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mailing extends Model
{
	protected $dates = ['sending_date'];

	protected $fillable = [
		'sending_date'
	];
}
