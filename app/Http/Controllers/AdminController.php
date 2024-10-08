<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminController extends Controller
{
    function register(Request $request){
        $cek = User::where('email', $request->input('email'))->first();
        $token = Str::random(50);

        if($cek){
            return response([
                'message' => 'Email sudah terdaftar. Silakan login.'
            ], 200);
        } else {
            $user = User::create([
                "name" => $request->input("name"),
                "email" => $request->input("email"),
                "password" => bcrypt($request->input('password')),
                "token" => $token,
                "account_status" => "2",
                "created_at" => now(),
            ]);
        }
    }
    
    public function login(Request $request) {
        $user = User::where('email', $request->email)->first();

        if($user){
            if(Hash::check($request->password, $user->password)){
                Auth::loginUsingId($user->id);
                $user->last_login = now();
                $user->save();
                return response([
                    'message' => 'Berhasil login.',
                    'token' => $user->token,
                    'data' => $user
                ], 200);
            } else {
                return response([
                    'message' => 'Password salah. Silakan ulangi lagi.'
                ], 401);
            }
        } else {
            return response([
                'message' => 'Email Anda tidak terdaftar pada sistem SIPOLMA. Harap hubungi Bagian Kemahasiswaan.'
            ], 401);
        }
    }

    function change_password(Request $request){
        $user = User::where('token', $request->token)->first();

        if($user){
            if(Hash::check($request->password_lama, $user->password) && $request->password_baru==$request->verify_password){
                $user->password = bcrypt($request->password_baru);
                $user->save();

                return response()->json([
                    'status' => 200,
                    'message' => 'Berhasil mengubah password.',
                ], 200);
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Silakan ulangi lagi.'
                ], 401);
            }
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'User tidak ditemukan.'
            ], 404);
        }
    }

    function logout(){
        Auth::logout();

        return [
            'message' => 'Logged out.'
        ];
    }
}
