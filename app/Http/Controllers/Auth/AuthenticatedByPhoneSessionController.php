<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller; 
use App\Http\Requests\Auth\LoginByPhoneRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Auth\LoginRequest; 

class AuthenticatedByPhoneSessionController extends AuthenticatedSessionController
{
    

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function passPage(LoginByPhoneRequest $phone_request)
    {
        $phone = $phone_request->input('phone');
        $back = $phone_request->input('back');
        $id = $phone_request->input('id');
        $res = User::where('phone', '=', $phone)->firstOr(function(){ 
        return false;   
        });

        if($res){
            return view('auth.login-phone-enter-staticpass', ['phone' => $phone, 'back' => $back, 'id' => $id]);

        }else{
          //redirect to login page with error message. 
          // return redirect()->route('login', ['errors' => serialize(["__('form.user-not-found')"])]);  

            return redirect(url()->previous())
                    //->withInput(Arr::except($request->input(), $this->dontFlash))
                    //->withErrors($exception->errors(), $request->input('_error_bag', $exception->errorBag));
                      ->withInput($phone_request->input())
                      ->withErrors([__('form.user-not-found')]);
        }

        
         
    } 
    

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeStatic(LoginByPhoneRequest $request)
    { 

        $res = $request->authenticateStatic();

        if($res == "wrong pass"){
            return view('auth.login-phone-enter-staticpass', ['phone' => $request->phone, 'back' => $request['back'], 'id' => $request['id']])->withErrors([__('auth.wrong-password')]);
        }



        $request->session()->regenerate();

        $back_route = $request['back'];
        $back_id = $request['id'];

        if($back_route){
            return redirect()->intended(route($back_route, ['id' => $back_id]));
        }
        return redirect()->intended(RouteServiceProvider::HOME);
        
    }

     public function storeDynamic(LoginByPhoneRequest $request){ 

        $res = $request->authenticateDynamic();

        if($res !== "true-pass"){
            //echo $res;
            return view('auth.login-phone-enter-dynamicpass', ['phone' => $request->phone, 'back' => $request['back'], 'id' => $request['id']])->withErrors([__('auth.wrong-password')]);
        }



        $request->session()->regenerate();

        $back_route = $request['back'];
        $back_id = $request['id'];

        if($back_route){
            return redirect()->intended(route($back_route, ['id' => $back_id]));
        }
        return redirect()->intended(RouteServiceProvider::HOME);
        
    }


    /*public function storeStaticByEmail(LoginRequest $request)
    {

        //print_r($request->only('phone', 'password'));


        $request->authenticateEmailStatic();

        //$request->session()->regenerate();

        //return redirect()->intended(RouteServiceProvider::HOME);
    }*/

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {

        $request->authenticateWithPhone();

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function dynamicPassPage(LoginByPhoneRequest $phone_request){
        $phone = $phone_request->input('phone');
        $back = $phone_request->input('back');
        $id = $phone_request->input('id');
        $res = User::where('phone', '=', $phone)->firstOr(function(){ 
        return false;   
        });

        if($res){

            //create a dynamic pass and send to user by sms
            if(!app('App\Http\Controllers\Auth\RegisteredUserController')->otpThrottle($res)){

               $randOtp = rand(100011, 999989);
               $message = config('لاراول') . " " . __('form.pass-change-code: ') . $randOtp;

            //save the otp in the database 
            DB::table('phone_confirm')->insert(['phone'=> $phone_request['phone'], 'token' => $randOtp, 
                 "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
                 "updated_at" => \Carbon\Carbon::now()]);
        
               $res->sendSMS($message, $res->phone);
            }


            return view('auth.login-phone-enter-dynamicpass', ['phone' => $phone, 'back' => $back, 'id' => $id]);

        }else{
          //redirect to login page with error message. 
          // return redirect()->route('login', ['errors' => serialize(["__('form.user-not-found')"])]);  

            return redirect(url()->previous())
                    //->withInput(Arr::except($request->input(), $this->dontFlash))
                    //->withErrors($exception->errors(), $request->input('_error_bag', $exception->errorBag));
                      ->withInput($phone_request->input())
                      ->withErrors([__('form.user-not-found')]);
        }
    }

    /*public function otpThrottle($user = null){

        if($user){
            $phone = $user->phone;
        } else {
            $phone = auth()->user()->phone;

        }
 
        $saved_otp = DB::table('phone_confirm')->select('token')->where(['phone'=> $phone])->where('created_at', '>', \Carbon\Carbon::now()->subMinutes(10))->get();
        if($saved_otp->count() > 0){
            return true;
        } else {
            return false;
        }


    }*/
}
