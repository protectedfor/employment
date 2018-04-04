<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class UsersController extends Controller
{
	public function update(Request $request, $id)
	{
		$user= User::findOrFail($id);

//			$this->validate($request, [
//				'newPassword' => 'min:8',
//			]);

			if ($request->newPassword != $request->repeatedPassword) {
				return back()->with(Session::flash('error', 'Повторный набор пароля был осуществлён неверно'));}

			if (!Hash::check($request->oldPassword, $user->password))
				return back()->with(Session::flash('error', 'Старый пароль был введён неверно'));
			else
				$data['password'] = Hash::make($request->newPassword);

		$user->update($data);

		return back()->with(Session::flash('success', 'Ваш пароль изменён'));
	}
}
