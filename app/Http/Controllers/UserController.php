<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller {

    public function login()
    {
        if (request()->isMethod('get')) {
            if (Auth::check()) {
                return redirect('/');
            }
            return view('user.login');
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
        return redirect('/');
    }

    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        return redirect('login');
    }

    public function register()
    {
        if (request()->isMethod('get')) {
            return view('user.register');
        }
        $this->validate(request(), [
            'email' => 'required|unique:users,email',
            'name' => 'required',
            'password' => 'required',
        ]);
        $user = new User;
        $user->email    = request('email');
        $user->name     = request('name');
        $user->password = password_hash(request('password'), PASSWORD_DEFAULT);
        $user->save();
        Auth::loginUsingId($user->id);
        return redirect('/');
    }

}