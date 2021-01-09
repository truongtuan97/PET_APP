<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class PassportAuthController extends Controller
{
    public function register(Request $request) {
        $this->validate($request, [
            'name' => 'required|min:4',
            'email' => 'required|email',
            'address' => 'required',
            'password' => 'required|min:8',
            'phone' => 'required'
        ]);

        $user = User::create([
            'name'=> $request->name,
            'email' => $request->email,
            'password' => \bcrypt($request->password),
            'phone' => $request->phone,
            'address' => $request->address
        ]);
        
        $token = $user->createToken('PET_APP')->accessToken;

        return response()->json(['token' => $token], 200);
    }

    public function login(Request $request) {
        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (auth()->attempt($data)) {
            $token = auth()->user()->createToken('PET_APP')->accessToken;
            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }
}
