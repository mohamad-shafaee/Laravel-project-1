<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\LoginByEmailRequest; 
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class AuthenticatedByEmailSessionController extends AuthenticatedSessionController
{ 

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function passPage(LoginByEmailRequest $email_request)
    {

        $email = $email_request->input('email');
        $back = $email_request->input('back');
        $id = $email_request->input('id');

        $res = User::where('email', '=', $email)->firstOr(function(){ 
        return false;   
        });

        if($res){
            return view('auth.login-email-enter-staticpass', ['email' => $email, 'back' => $back, 'id' => $id]);

        }else{
          //redirect to login page with error message.   

            return redirect(url()->previous())
                      ->withInput($email_request->input())
                      ->withErrors([__('form.user-not-found')]);
        }
    } 



    public function storeStatic(LoginByEmailRequest $request)
    {

        $res = $request->authenticateStatic();

        if($res == "wrong pass"){
            return view('auth.login-email-enter-staticpass', ['email' => $request->email, 'back' => $request['back'], 'id' => $request['id']])->withErrors([__('auth.wrong-password')]);
        }

        $request->session()->regenerate();

        $back_route = $request['back'];
        $back_id = $request['id'];

        if($back_route){
            return redirect()->intended(route($back_route, ['id' => $back_id]));
        }
        return redirect()->intended(RouteServiceProvider::HOME);

        
    }


    

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    /*public function storeStaticByPhone(LoginRequest $request)
    {

        //print_r($request->only('phone', 'password'));


        $request->authenticatePhoneStatic();

        //$request->session()->regenerate();

        //return redirect()->intended(RouteServiceProvider::HOME);
    }*/


    
    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
   /* public function store(LoginRequest $request)
    {

        $request->authenticateWithPhone();

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }*/

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
}
