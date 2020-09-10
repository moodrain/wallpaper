<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function login()
    {
        if (request()->isMethod('get')) {
            return $this->view('user.login');
        }
        $this->validate(request(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $user = User::query()->where('email', request('email'))->first();
        if (empty($user) || ! password_verify(request('password'), $user->password)) {
            return $this->backErr('auth.failed');
        }
        Auth::loginUsingId($user->id);
        return redirect('/admin');
    }

    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        return redirect('/admin/login');
    }

}