<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use Illuminate\Http\Response;

class AuthController extends Controller
{

    public function showRegistrationForm(Request $request)
    {
        $referralToken = $request->query('ref');

        if ($request->has('ref')) {
            session(['referrer' => $request->query('ref')]);
        }
        return view('auth.register');
    }
    public function register(Request $request)
    {
        $referrer = User::whereEmail(session()->pull('referrer'))->first();

        try {
            $data = $request->validate([
                'name' => 'required|string',
                'email' => 'required|email|unique:users',
                'password' => 'required|confirmed',
                'role' => 'sometimes',
                'referrer_id' => 'sometimes'
            ]);
            
            $data['role'] = 'user';
            $data['referrer_id'] = $referrer ? $referrer->id : null;

            // dd($request->all());
            $user = User::create($data);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000) {
                return redirect()->back()->withInput()->withErrors(['error' => 'A duplicate entry error occurred. Please try again.']);
            }
        
        }

        // event(new Registered($user));

    
        // auth()->logout();
        return $request->wantsJson()
            ? Response::api(['data' => $user])
            : to_route('login');
    }

    public function login(CreateUserRequest $request)
    {
        if (!Auth::attempt($request->only(['email', 'password']))) {
            return $request->wantsJson()
                ? Response::api('Invalid Credentials', Response::HTTP_BAD_REQUEST)
                : back()->with('invalidCredential', 'Invalid Credentials');
        }

        return  to_route('home');
    }

    public function logout(Request $request)
    {

        if ($request->wantsJson()) {

            return Response::api('logged out successfully');
        }

        Auth::logout();

        return to_route('login');
    }
}
