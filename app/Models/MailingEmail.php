<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class MailingEmail extends Model
{
	protected $fillable = [
		'user_id', 'email', 'subscribed', 'last_title'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}