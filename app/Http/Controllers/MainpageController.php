<?php

namespace App\Http\Controllers;
//namespace App\Models\Post;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Follow;
use App\Models\Post;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class MainpageController extends Controller
{
    // 
    private $postNumbers = 10;
    public function setPostNumbers($n){
        $this->postNumbers = $n;
    }

    public function getPostNumbers(){
        return $this->postNumbers;
    }

    public function index(Request $request){
             
        $n1 = floor($this->postNumbers * 2/3);
        $n2 = $this->postNumbers - $n1;

        $posts = $this->gatherPosts($n1, $n2, $request);//->toJson();

        //$posts = $this->checkForSimilarPosts($posts);

        if($request['page']){
            //return "The ajax   " . $request['page'];
            //$view = view('components.posts-component', ['posts' => $posts])->render();
            //return response()->json(['html'=>$view]);
            //return response()->json(['posts' => json_encode($posts)]);
            return response()->json(['posts' => $posts]);
        }
        
        //if($posts->count() > 0){
        //if(count($posts) > 0){
            //return $posts->count();
        
            return view('home', ['base_stat' => 'mainpage','posts' => $posts->toJson()]);

        /*}else{
            //return "posts are empty";
            return view('home');

        }*/

        
    }

    public function gatherPosts($n1, $n2, Request $request){

        //if(Auth::user()){     //It is another alternative
        if($user = $request->user()){
            //find all users which are followed or following by this user

            //followings this user -- high score shows more importance
            $followings = Follow::select('user_id_2')->where('user_id_1', $user->id)->orderBy('score')->get();

            //followers of this user
            $followers = Follow::select('user_id_1')->where('user_id_2', $user->id)->orderBy('score')->get();
             
            //$relatedUsers = $followers->merge($followings);

            $page = 1;

            if($request['page']){
                $page = $request['page'];
            }
             
            //find all posts that are liked by user and search for similar ones
            // posts of persons you follow.
            $posts1 = DB::table('posts')->where('acceptable', 1)
            ->whereIn('user_id', $followings)
            ->orderBy('id')->get();

            if($posts1->count()>$n1){
                $posts1 = $posts1->slice(($page-1)*$n1, $n1);
            }

            //posts of your followers
            $posts2 = DB::table('posts')->where('acceptable', 1)
            ->whereIn('user_id', $followers)
            ->orderBy('id')->get();

            if($posts2->count()>$n2){
                $posts2 = $posts2->slice(($page-1)*$n2, $n2);
            }

            $posts = $posts1->merge($posts2);

            if($residual = ($n1+$n2)-($posts1->count()+$posts2->count())>0){
                //select others
                $all_posts = DB::table('posts')->where('acceptable', 1)->where('updated_at', '>', Carbon::now()->subDay(30))
                ->get();
                $res_posts = $all_posts->slice(($page-1)*$residual, $residual);
                $posts = $posts->merge($res_posts);
            }

            return($posts); 
        }else{

            $page = 1;

            if($request['page']){
                $page = $request['page'];
            }

            //if the user is not authenticated, for each page the posts will be selected randomly or by slice
            // In the randomly method, the page is not take into account.
             
            $posts = DB::table('posts')->where('acceptable', 1)->where('updated_at', '>', Carbon::now()->subDay(30))
            ->get();

            if($posts->count()>($n1+$n2)){
                //$posts = $posts->random($n1+$n2);
                $posts = $posts->slice(($page-1)*($n1+$n2), ($n1+$n2));

            }

            //->where('id', '>', ($page-1)*3)->take(3)->get();

            //$total = $posts->count();
            //$selected_posts = 

            return $posts;


        }

        //return DB::table('posts')->where('removed', 0)->where('acceptable', 1)->orderBy('id')->CursorPaginate(7);
 
        //return DB::table('posts')->where('removed', 0)->where('acceptable', 1)->CursorPaginate(7);
    }

    
    // For exercising download
    public function download(){

         return Storage::download('public/user_image/fati1.jpg');
    }
}
