<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Follow;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    private $postNumbers = 10;

    public function profile(){

        //$user = auth()->user();
        return view('my-profile');//->with('user', $user);
    }

    public function userProfile($user_id){

        if($observer=auth()->user()){

        //check if the user could edit profile or not
        $canUpdateUserProfile = Gate::allows('update-user-profile', $user_id);
        if($canUpdateUserProfile){
            return view('my-profile');//->with('user', $user);
        }
        $observed=User::find($user_id);
        return view('user-profile')->with('observer', $observer)->with('observed', $observed);
        }

        $observed=User::find($user_id);
        return view('user-profile')->with('observed', $observed);
    }


    public function follow($id){
        $follower_id = auth()->user()->id;
        $checkFollowers = $this->checkFollowers($follower_id, $id);

        if(! $checkFollowers){
            $record = new Follow(['user_id_1'=>$follower_id, 'user_id_2'=>$id, 'score'=>0]);
            $record->save();
            return $record->id;
        }

        //Already followed
        return '';


    }

    public function checkFollowers($follower_id, $following_id){
        Follow::where([['user_id_1', $follower_id], ['user_id_2', $following_id]])->firstOr(function(){
            return false;
        });

    }

    public function unfollow($id){
        $follower_id = auth()->user()->id;
        //$checkFollowers = checkFollowers($follower_id, $id);

        
            $record = Follow::where([['user_id_1', $follower_id], ['user_id_2', $id]]);
            $deleted = $record->delete();

            if($deleted){
                return 'deleted';
            }
        

        // Deleting error
        return '';


    }


    public function posts(Request $request){

        $n = $this->postNumbers;
        $posts = $this->gatherPosts($n, $request);

        if($request['page']){
            return response()->json(['posts' => $posts]);
        }

         return view('home', ['base_stat' => 'myposts','posts' => $posts->toJson()]);
        
    }


    public function gatherPosts($n, Request $request){

        $page = 1;

            if($request['page']){
                $page = $request['page'];
            }

            $id = auth()->user()->id;

        $posts = DB::table('posts')->where('user_id', $id)->get();

        if($posts->count() > $n){
                $posts = $posts->slice(($page-1)*$n, $n);
            }

        return($posts); 
    }


    public function saveImage(Request $request){

         $file = $request->file('file');

        // $mime = $file->getClientMimeType(); 
         $mime = $file->getClientMimeType();
         $extension = explode("/", $mime)[1];
         $path1 = $file->storeAs('public/userImages', "user-" . auth()->user()->id . "." . $extension);

          // when we want to store (above line) a file we use the store function. It saves the 
          // file in the storage/app/public/userImages (the path starts by storage/app). 
          // since we used link, we can get it publicly from the public folder.
          // the argument to the url() helper should be the path to the file inside
          // the public folder.
          $path = url('storage/'. substr($path1, 7));

          $user = auth()->user();

          $user->img_path = $path;

          $res = $user->save();

          //Now we should remove the previous img_path from data base and the file from server. 

          if($res) {return $user->img_path;}

    }


    public function removeImage(){

          $user = auth()->user();
          $filename = explode("/", $user->img_path)[5];
          //return 'public/userImages/' . $filename;

          //public/userImages/filename
          Storage::delete('public/userImages/' . $filename);

          $user->img_path = null;
          $res = $user->save();


          if($res) {return "removed";} 
    }

    public function saveUsername(Request $request){

        // This input does not need validation since it has 
        // been validated in the front-end.

        $id = $request['user_id'];
        // Authorizing the action
        if (! Gate::allows('update-user-profile', $id)) {
            abort(403);
        }
        $user = auth()->user();
        $user->name = $request['user_name'];
        $user->save(); 
        return $user->name;
         
    }

    public function saveUserEmail(Request $request){

        // This input does not need validation since it has 
        // been validated in the front-end.
        //$validated = $request->validate([
       //     'user_email' => 'nullable|unique:users,email|email:rfc,dns',
      //  ]);
        $validator = Validator::make($request->all(), [
            'user_email' => 'nullable|unique:users,email|email:rfc,dns',
        ]);

        if($validator->fails()){
            return $validator->errors()->toJson();

        }

        $validated = $validator->validated();

        $id = $request['user_id'];
        // Authorizing the action
        if (! Gate::allows('update-user-profile', $id)) {
            abort(403);
        }
        $user = auth()->user();
        $user->email = $validated['user_email'];
        $user->save(); 
        return $user->email;
         
    }

    public function saveUserMellicode(Request $request){

        // This input does not need validation since it has 
        // been validated in the front-end.
        //$validated = $request->validate([
       //     'user_email' => 'nullable|unique:users,email|email:rfc,dns',
      //  ]);
        $validator = Validator::make($request->all(), [
            'mellicode' => 'nullable|unique:users,mellicode|digits:10',
        ]);

        if($validator->fails()){
            return $validator->errors()->toJson();

        }

        $validated = $validator->validated();

        $id = $request['user_id'];
        // Authorizing the action
        if (! Gate::allows('update-user-profile', $id)) {
            abort(403);
        }
        $user = auth()->user();
        $user->mellicode = $validated['mellicode'];
        $user->save(); 
        return $user->mellicode;
         
    }


    public function saveUserPhone(Request $request){

        // This input does not need validation since it has 
        // been validated in the front-end.
        //$validated = $request->validate([
       //     'user_email' => 'nullable|unique:users,email|email:rfc,dns',
      //  ]);
        $validator = Validator::make($request->all(), [
            'phone' => 'nullable|unique:users,phone|digits:11', 
        ]);

        if($validator->fails()){
            return $validator->errors()->toJson();

        }

        $validated = $validator->validated();

        $id = $request['user_id'];
        // Authorizing the action
        if (! Gate::allows('update-user-profile', $id)) {
            abort(403);
        }
        $user = auth()->user();
        $user->phone = $validated['phone'];
        $user->save(); 
        return $user->phone;
         
    }

    public function saveUserPassword(Request $request){

        // This input does not need validation since it has 
        // been validated in the front-end.
        //$validated = $request->validate([
       //     'user_email' => 'nullable|unique:users,email|email:rfc,dns',
      //  ]);
        $validator = Validator::make($request->all(), [
            'current_password' => 'current_password',
            'new_password' => 'required|confirmed', 
        ]);

        if($validator->fails()){
            return $validator->errors()->toJson();

        }

        $validated = $validator->validated();

        $id = $request['user_id'];
        // Authorizing the action
        if (! Gate::allows('update-user-profile', $id)) {
            abort(403);
        }
        $user = auth()->user();
        $user->password = Hash::make($validated['new_password']);
        $user->save(); 
        if($user->wasChanged('password')){
            return "password-changed";
        }    
    } 
    
}
