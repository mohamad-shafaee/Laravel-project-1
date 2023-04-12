<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create($back = null, $id = null)
    {
        return view('auth.login')->with('back', $back)->with('id', $id);
    }


     /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    /*public function enterPass(LoginRequest $request)
    {

        //detect that phone or email has been entered by user. 
        $user_cred = $this->phoneOrEmail($request);

        switch($request->input('action')){
        
        case 'static-p':
        //echo "Static";
        //check the Static pass and login
        //Go to a page to enter Static pass.
        if($user_cred == 'phone'){
            return view('auth.login-phone-enter-staticpass', ['phone'=>$request->input('phone-or-email')]);
        }else{
            return view('auth.login-email-enter-staticpass', ['email'=>$request->input('phone-or-email')]);
 
        }
        


        break;

        case 'dynamic-p':
        echo "Dynamic";

        break;


        }


    }*/



    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
   /* public function enterPass(LoginRequest $request, LoginByPhoneRequest $phone_request, LoginByEmailRequest $email_request)
    {

        //detect that phone or email has been entered by user. 
        $user_cred = $this->phoneOrEmail($request);

        if($user_cred == 'phone'){

            $this->goToEnterByPhone($phone_request);
            //return view('auth.login-phone-enter-staticpass', ['phone'=>$request->input('phone-or-email')]);
        }else{
            $this->goToEnterByEmail($email_request);
            //return view('auth.login-email-enter-staticpass', ['email'=>$request->input('phone-or-email')]);
        }


    }*/

   /* public function goToEnterByEmail(LoginByEmailRequest $request){

                    return view('auth.login-email-enter-staticpass', ['email'=>$request->input('phone-or-email')]);



    }*/

   /* public function goToEnterByPhone(LoginByPhoneRequest $request){



        switch($request->input('action')){
        
        case 'static-p':

        return view('auth.login-phone-enter-staticpass', ['phone'=>$request->input('phone-or-email')]);

        break;

        case 'dynamic-p':
        echo "Dynamic";

        break;


        }

        
    }*/


   /* public function phoneOrEmail(LoginRequest $request){

        if(is_numeric($request->input('phone-or-email'))){
            return 'phone';
        }else{
            return 'email';
        }
    }*/

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
   /* public function storeStaticByPhone(LoginRequest $request)
    {

        //print_r($request->only('phone', 'password'));


        $request->authenticatePhoneStatic();

        //$request->session()->regenerate();

        //return redirect()->intended(RouteServiceProvider::HOME);
    }


    public function storeStaticByEmail(LoginRequest $request)
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
