<?php

namespace App\Http\Controllers\User;

use App\Models\User;

class UserRepository
{
	public function listAllUsers($exceptCurrentUser = false)
	{
		if ($exceptCurrentUser) {
			return User::where('id', '!=' ,auth()->user()->id)->get();
		}
		return User::get();
	}

	public function findUserById($id)
	{
		return User::find($id);
	}
}
