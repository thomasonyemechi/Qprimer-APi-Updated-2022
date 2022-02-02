<?php

namespace App\Http\Controllers;

use App\Models\PasswordReset;
use App\Models\User;
use Dflydev\DotAccessData\Data;
use Facade\FlareClient\Http\Response;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class AuthController extends Controller
{
    function index()
    {
    }


    function signUp(Request $request)
    {
        $res = Http::asForm()->post(env('LINK'), [
            'signUpUsers' => 'good',
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => $request->password,
        ]);

        if($res['success'] == true){
            $data = $res['data'];
            $create = $this->createUser($data);
            return response(['message' => 'Signup successfull', 'success' => true]);
        }
        return $res;
    }



    function changePassword(Request $request)
    {
        $user = User::find($request->user_id)->first()->live_id;
        $res = Http::asForm()->post(env('LINK'), [
            'changePassword' => 'good',
            'new_password' => $request->new_password,
            'confirm_new_password' => $request->confirm_new_password,
            'current_password' => $request->current_password,
            'user_id' => $user->id,
        ]);
        if($res['success'] == true){
            $pass = $res['data']['pass'];
            User::where('id', $user->id)->update([
                'password' => $pass
            ]);
            return response(['message' => 'Password updated sucessfully', 'success' => true]);
        }

        return $res;
    }


    function resetPassword(Request $request)
    {
        $verify = $this->verifyOTP($request)->original;
        if($verify['success']){
            $user = User::find($request->user_id);
            $res = Http::asForm()->post(env('LINK'), [
                'resetPassword' => 'good',
                'user_id' => $user->live_id,
                'new_password' => $request->new_password,
            ]);
            $res = json_decode($res, true);
            if($res['success'] == true) {
                $pass = $res['data']['pass'];
                User::where('id', $user->id)->update([
                    'password' => $pass
                ]);
                return response(['message' => 'Password updated sucessfully', 'success' => true]);
            }else {
                return response(['message' => 'Error updating password', 'success' => false]);
            }
        }else{
            return response(['message' => $verify['message'], 'success' => false]);
        }
        return response($verify['success']);
    }



    function verifyOTP(Request $request)
    {
        $otp = $request->otp;
        $user_id = $request->user_id;
        $user = User::find($user_id);
        if ($user) {
            $reset = PasswordReset::where(['email' => $user->email, 'token' => $otp])->first();
            if ($reset) {
                return response(['message' => 'OTP verification sucessfull', 'success' => true], 200);
            } else {
                return response(['message' => 'OTP verification failed, Invalid token', 'success' => false], 401);
            }
        } else {
            return response(['message' => 'OTP verificaton failed, invalid user', 'success' => false], 401);
        }
    }


    function forgotPassword(Request $request)
    {
        $email = $request->email;
        $token = $this->win_hash(4);
        $user = User::where('email', $email)->first();
        if ($user) {
            PasswordReset::create([
                'email' => $email,
                'token' => $token,
            ]);
            //sends email containing otp to $email
            return response(['message' => 'Email verfied , OTP sent!', 'success' => true, 'user_id' => $user->id], 200);
        } else {
            return response(['message' => 'Email does not exist', 'success' => false], 404);
        }
    }



    function login(Request $request)
    {
        $res = Http::asForm()->post(env('LINK'), [
            'LoginUserViaCbtGet' => 'good',
            'email' => $request->email,
            'password' => $request->password ?? '',
        ]);
        $user = [
            'email' => $request->email,
            'password' => $request->password,
        ];
        if ($res['status'] == 1) {
            $data = $res['data'];
            $create = $this->createUser($data);
            $userData = $this->pAuth($user);
            return response($userData);
        }
        return response($res, 422);
    }



    function pAuth($user)
    {
        if (Auth::attempt($user)) {
            $user = User::where('id', auth()->user()->id)->first(['id', 'firstname', 'lastname', 'email', 'phone']);
            return response($user);
        } else {
            return response(['message' => 'error logging in', 'success' => false]);
        }
    }



    function createUser($data)
    {
        $user = User::where('live_id', $data['sn'])->first();
        if ($user == null or $user = '') {
            User::create([
                'lastname' => $data['lastname'],
                'firstname' => $data['firstname'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'live_id' => $data['sn'],
                'password' => $data['pass'],
            ]);
        } else {
            User::where('live_id', $data['sn'])->update([
                'lastname' => $data['lastname'],
                'firstname' => $data['firstname'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'password' => $data['pass'],
            ]);
        }
    }
}
