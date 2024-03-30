<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;

class EarnController extends Controller
{
    /**
     * Show the profile for a given user.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function aprChart($id)
    {
        return view('user.profile', [
            'user' => User::findOrFail($id)
        ]);
    }
}