<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    private $postNumbers = 10;
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
   /* public function __invoke(Request $request)
    {
        //$results = $this.searchByTerm($request['term']);

        $posts = DB::table('posts')->where('title', 'like','%'. urldecode($request['term']) . '%')->get();
        
        return view('home')
        //->with('posts', count($posts) ? $posts : null)
        ->with('posts', $posts)
        ;
    }*/

    public function searchPosts(Request $request)
    {
        
        $n = $this->postNumbers;

        //$posts = DB::table('posts')->where('title', 'like','%'. urldecode($request['term']) . '%')->get();
        $posts = $this->gatherPosts($n, $request);

        if($request['page']){
            //return "The ajax   " . $request['page'];
            //$view = view('components.posts-component', ['posts' => $posts])->render();
            //return response()->json(['html'=>$view]);
            //return response()->json(['posts' => json_encode($posts)]);
            return response()->json(['posts' => $posts]);
        }

         return view('home', ['base_stat' => 'search','posts' => $posts->toJson()]);
        
        //return view('home')
        //->with('posts', count($posts) ? $posts : null)
        //->with('posts', $posts);
    }



    public function gatherPosts($n, Request $request){

        $page = 1;

            if($request['page']){
                $page = $request['page'];
            }

        $posts = DB::table('posts')->where('title', 'like','%'. urldecode($request['term']) . '%')->get();

        if($posts->count() > $n){
                $posts = $posts->slice(($page-1)*$n, $n);
            }

        return($posts); 
    }



    /*public function searchByTerm(String $data){

        return DB::table('teachers')->where('name', 'like','%'. urldecode($data) . '%')->get();
    }*/

} 