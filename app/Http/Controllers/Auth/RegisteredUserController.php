<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;


class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storeByPhone(Request $request)
    {
       // echo 'ffffff';
        $validated = $request->validate([
            //'name' => ['required', 'string', 'max:255'],
            //'email' => ['required_without:phone', 'nullable', 'string', 'email', 'max:255', 'unique:users'],
            //'phone' => ['required_without:email', 'nullable', 'string', 'max:11', 'unique:users'],
            'phone' => ['required', 'string', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        

        $user = User::create([
            'name' => $request->phone,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'confirmed' => 0,
        ]);

        event(new Registered($user));



        Auth::attempt($request->only('phone', 'password'), 0);

        //Auth::login($user);

        //$this->confirmPhone();
        if(!$this->otpThrottle()){
            $res = auth()->user()->sendPhoneVerificationNotification();
        }
        
        //So we should create a new get rout and ...
        return redirect()->route('confirmation.page');
        


         //this login should be come after confirmation the account
        //Auth::login($user);

        //return redirect(RouteServiceProvider::HOME);
    }

    public function phoneConfirm(){

        if(!$this->otpThrottle()){
            $res = auth()->user()->sendPhoneVerificationNotification();
        }
        
        //So we should create a new get rout and ...
        return redirect()->route('confirmation.page');

    }


    public function otpThrottle($user = null){

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


    }

    /*public function confirmPhone(){

        $res = auth()->user()->sendPhoneVerificationNotification();
        //echo $res . " and I ...";
        //we can not return a view from post route. like this:
        //return view('auth.confirm-account-by-phone');

        //So we should create a new get rout and ...
        return redirect()->route('confirmation-page');
        
        
        

    }*/

    public function confirmPage(){

        return view('auth.confirm-account-by-phone');

    }

    public function sendOTP(){

        if(!$this->otpThrottle()){
            auth()->user()->sendPhoneVerificationNotification();
            return 'otp-sent';
        } else {
            return 'otp-throttled';
        }      

    }

    /*public function getCurrentOTP(){

       $phone = auth()->user()->phone;

       $res = DB::table('phone_confirm')->select('token')->where(['phone'=> $phone])->where('created_at', '>', \Carbon\Carbon::now()->subMinutes(10))->get();
       
       return $res;      

    }*/





    public function confirmOTP(Request $request){

        $otp = $request['token'];

        $user = auth()->user();
        $phone = $user->phone;

        $saved_otp = DB::table('phone_confirm')->select('token')->where(['phone'=> $phone])->where('created_at', '>', \Carbon\Carbon::now()->subMinutes(10))->get();
        //return $saved_otp[0]->token . "  yyy ";

        foreach($saved_otp as $s_otp){
            if($otp == $s_otp->token){
            $user->phone_verified_at = \Carbon\Carbon::now();
            $user->save();

            return 'confirmed';

        } 
            
        }

        return 'not-confirmed';
    }



    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storeByEmail(Request $request)
    {
       // echo 'ffffff';
        $request->validate([
            //'name' => ['required', 'string', 'max:255'],
            //'email' => ['required_without:phone', 'nullable', 'string', 'email', 'max:255', 'unique:users'],
            //'phone' => ['required_without:email', 'nullable', 'string', 'max:11', 'unique:users'],
            'email' => ['required', 'string', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        

        $user = User::create([
            'name' => $request->email,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }


    public function userAvailable(Request $request){
     
     $user = null;
     $name = $request['name'];
     if($name == 'phone'){
        $phone = $request['phone'];
        $user = User::where('phone', $phone)->first();
     } elseif ($name == 'email') {
        $email = $request['email'];
        $user = User::where('email', $email)->first();

     }

     if(!is_null($user)){
        return $user->id;
     }
        
        return('');
    }

    //This function is for un authenticated users
    public function sendPhoneOtpForPassChange(Request $request){

        if($user = User::where('phone', $request['phone'])->first()){

            if(!$this->otpThrottle($user)){

               $randOtp = rand(100011, 999989);
               $message = config('app.name') . " " . __('form.pass-change-code: ') . $randOtp;

            //save the otp in the database 
            DB::table('phone_confirm')->insert(['phone'=> $request['phone'], 'token' => $randOtp, 
                 "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
                 "updated_at" => \Carbon\Carbon::now()]);
        
               $user->sendSMS($message, $user->phone);
            }

        return redirect()->route('input-pass-change-token-phone', ['phone' => $request['phone']]);

        } else {

            return back()->withErrors([__("form.user-not-found")]);//'user-not-found';
        }
    }


    public function passChangeByPhoneAlert($phone){
        return view('auth.input-pass-change-token-phone', ['phone' => $phone]);//->with('phone', $phone);
    }

    public function confirmPassChange(Request $request){

         $otp = $request['token'];
       $phone = $request['phone'];

       $saved_otp = DB::table('phone_confirm')->select('token')->where('phone', $phone)->where('created_at', '>', \Carbon\Carbon::now()->subMinutes(10))->get();

       foreach($saved_otp as $s_otp){
            if($otp == $s_otp->token){
                return "otp-confirmed";

            //return redirect()->route("pass-change-form", ['phone' => $phone]);


        } 
            
        }

        return 'otp-not-confirmed';
    }

    public function createPassChangeForm($phone, $token){

        return view('auth.pass-change-form')->with('phone', $phone)->with('token', $token);
    }

    public function changePass(Request $request){

        $validated = $request->validate([
            //'phone' => ['required', 'string', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);


        $otp = $request['token'];
       $phone = $request['phone'];

       $saved_otp = DB::table('phone_confirm')->select('token')->where('phone', $phone)->where('created_at', '>', \Carbon\Carbon::now()->subMinutes(10))->get();

       foreach($saved_otp as $s_otp){
            if($otp == $s_otp->token){
                $user = User::where('phone', $request['phone'])->first();
                if($user){
                    $user->password = Hash::make($request->password);
                    $user->save();
                    //erase the phone confirmations such that they could not be applied for other 
                    //change pass try. 
                    $this->erasePhoneConfirmations($phone);
                    return 'pass-changed';

                }else{
                    return 'pass-change-error';
                }
                

            //return redirect()->route("pass-change-form", ['phone' => $phone]);


        } 
            
        }

        return 'pass-change-error';

        
        
    }

    public function erasePhoneConfirmations($phone){
        //erase all records of phone_confirm table with given phone. 
    }

    
}
