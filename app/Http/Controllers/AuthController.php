<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function Login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email|string',
            'password' => [
                'required',
            ],
            'remember' => 'boolean'
        ]);

        $remember = $credentials['remember'] ?? false;
        unset($credentials['remember']);

        if (!Auth::attempt($credentials, $remember)) {
            return response([
                'message' => 'Incorrect email or password.'
            ], 422);
        }
        $user = Auth::user();
        $token = $user->createToken('main')->plainTextToken;
        $company = DB::table('users as a')
                    ->join('companyTbl as b', 'a.company', '=', 'b.id')
                    ->select('b.shortName')
                    ->where('email', $request->email)
                    ->get();
        return response([
            'user' => $user,
            'token' => $token,
            'company' => $company
        ]);
    }
    public function Register(Request $request)
    {
        // $data = $request->validate([
        //     'name' => 'required|string',
        //     'email' => 'required|email|string|unique:users,email',
        //     'password' => [
        //         'required',
        //         'confirmed',
        //         Password::min(8)->mixedCase()->numbers()->symbols()
        //     ]
        // ]);

        /** @var \App\Models\User $user */

        if($request->position == "Agent"){
            $status = "Active";
        }else{
            $status = "Inactive";
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'company' => $request->company,
            'position' => $request->position,
            'status' => $status
        ]);
        // $token = $user->createToken('main')->plainTextToken;

        return response()->json(['message' => 'Success Registered']);
    }
    public function Logout()
    {
        /** @var User $user */
        $user = Auth::user();
        // Revoke the token that was used to authenticate the current request...
        $user->currentAccessToken()->delete();
        // $user = Auth::user()->token();
        // $user->revoke();

        return response([
            'success' => true
        ]);
    }
}
?>