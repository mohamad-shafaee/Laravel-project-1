<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post; 
use App\Models\RecycledPost;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\PostImage;
use App\Models\RecycledPostImage;
use Illuminate\Support\Facades\Gate;
Use Carbon\Carbon;
use App\Models\Follow;
use App\Models\User;


class PostController extends Controller
{

    private $postNumbers = 10;
    public function setPostNumbers($n){
        $this->postNumbers = $n;
    }

    public function getPostNumbers(){
        return $this->postNumbers;
    }


    public function create(){

        $user = Auth::user();

        // check that if there is an uncompleted (initial) post by this user. 
        $prev_post = $this->checkPost($user->id); 

        if($prev_post){ 
            $rel_post = $prev_post;  
            return view('create-post')->with('post', $rel_post);  
        }else{

        $rel_post = new Post(['title' => ' ', 'description' => ' '
            //, 'position' => DB::raw("GeomFromText('Point(57 80)')")
     ]);
        $user->posts()->save($rel_post); 
        return view('create-post')->with('post', $rel_post);
        } 
    } 

    public function showPostDetails($id){
        $post = Post::find($id);
        return view('post-details')->with('post', $post);

    }
 
    //save a post.
    public function save(Request $request){
        $user = Auth::user();

        //taking data from request
        $validated = $request->validate([
            'type' => 'nullable|String|max:20',
            'category' => 'nullable|String|max:20', 
            'province' => 'nullable|String|max:20',
            'city' => 'nullable|String|max:20',
            'address' => 'nullable|String|max:500',
            'title' => 'required|max:400',
            'description' => 'required',
            'price' => 'nullable|numeric'
        ]);

        $post_id = $request['post-id']; 
        $post = Post::find($post_id); 
        $post->title = $validated['title'];
        $post->description = $validated['description'];
        $post->type = $validated['type'] ?: "";
        $post->category = $validated['category'] ?: "";
        $post->province = $validated['province'] ?: ""; 
        $post->city = $validated['city'] ?: "";
        $post->address = $validated['address'] ?: "";
        $post->price = (int) $validated['price'] ?: 0;

        $acceptable = $this->isAcceptablePost($validated['title'], $validated['description']);

        if($acceptable){
            //The post can be acceptable to be save in (publishable) posts. 
            $post->acceptable = 1;
            $post_stat = "acceptable";
 
           }else{ 
            $post_stat = "unacceptable";
           }
           $res = $user->posts()->save($post); 
           return $res->id . "-" . $post_stat;
    } 

    public function isAcceptablePost($title, $description){
        if($title !== ' ' && $description !== ' '){
            return true;
        }
        return false;
    }


    public function saveFiles(Request $request){ 
        // we could save files just for posts not initial posts or recycled posts 

          $file = $request->file('file');
          $mime = $file->getClientMimeType();
          $extension = explode("/", $mime)[1];
          //$path = $file->store('public/postImages');
          $path = $file->storeAs('public/postImages', "post-". time() ."-". rand(100,200) . "." . $extension);

          $post_id = $request['post_id'];

          $postImage = new PostImage;
          $postImage->img_path = $path;   
          $post = Post::find($post_id);
          $post->postImages()->save($postImage); 
          return $postImage->id; 
        
    } 

    //To check if the user has uaacceptable post.
    public function checkPost($id)
    { 
        $res = Post::where([['user_id', '=', $id], ['acceptable', 0]])->firstOr(function(){
            return false;
        });

        return $res;
    }

    public function delete($id){

        $post = Post::find($id);
      
              if (! Gate::allows('update-post', $post)) {
            abort(403);
        }

        //move the post to recycled posts
        $rec_post = Post::moveToRecycledPosts($id); 
        return redirect()->route('home');

    }

    public function editPage($id){

        // Update the post...
        $post = Post::find($id);

        if (! Gate::allows('update-post', $post)) {
            abort(403);
        }

        return view('edit-post')-> with('post', $post); 

    }
     
     //Delete an array of images 
    //The deprecated method. This method works with a route request from form 
    // and causes deit page to be refreshed. 
   /* public function deleteImages(Request $request, $postId, $array){
        //extract the image ids to be deleted.
        $ids = explode(",", $array);

        $post = Post::find($postId);
      
        if (! Gate::allows('update-post', $post)) {
            abort(403);
                    }
        //delete images
        PostImage::destroy($ids);

        //return back();
        return redirect()->route('post-edit-page', $postId); 
    }*/


     //Delete an array of images 
    public function deleteImages(Request $request){

        $arr = $request['array'];
        $postId = $request['post_id'];

        $ids = json_decode($arr);

        

        $post = Post::find($postId);
      
        if (! Gate::allows('update-post', $post)) {
            abort(403);
                    }
        //delete images
        // The $delete shows the number of deleted items
        //$deleted = PostImage::destroy($ids);

        //return $deleted;

        $deleted = [];

        for($i = 0; $i<count($ids); $i++){
            $res = PostImage::find($ids[$i])->delete();

            if($res){
                $deleted[$i] = $ids[$i];
            }

        }

        // return the deleted items so we can update the page. 
        return json_encode($deleted); 
    }

      // seems to be idle
     public function edit(Request $request, $id){

        // Update the post...
        $post = Post::find($id);
      
              if (! Gate::allows('update-post', $post)) {
            abort(403);
        }

        // Update the post...
         
        redirect()->route('home');

    }

    public function filter(Request $request){

        $n1 = floor($this->postNumbers * 2/3);
        $n2 = $this->postNumbers - $n1;

        //taking data from request
        $validated = $request->validate([
            'type' => 'nullable|String',
            'category' => 'nullable|String',
            'city' => 'nullable|String',
            'province' => 'nullable|String',
            'from-price' => 'nullable|numeric',
            'to-price' => 'nullable|numeric',            
        ]);

        $posts = $this->gatherFilteredPosts($n1, $n2, $validated,  $request);

        if($request['page']){
            //return "The ajax   " . $request['page'];
            //$view = view('components.posts-component', ['posts' => $posts])->render();
            //return response()->json(['html'=>$view]);
            return response()->json(['posts' => $posts]);
        }

        //if($posts->count() > 0){
            //return $posts->count();
            return view('home', ['base_stat' => 'filter', 'posts' => $posts->toJson()]);

        //}else{
            //return "posts are empty";
           // return view('home');

        //}
    }


        public function gatherFilteredPosts($n1, $n2, $filters, Request $request){

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
            ->whereIn('user_id',$followings)
            ->orderBy('id')/*->get()*/;

            if($filters['type']){
                $posts1 = $posts1->where('type', $filters['type']);
            }

            if($filters['category']){
                $posts1 = $posts1->where('category', $filters['category']);
            }

            if($filters['city']){
                $posts1 = $posts1->where('city', $filters['city']);
            }

            if($filters['province']){
                $posts1 = $posts1->where('province', $filters['province']);
            }

            if($filters['from-price']){
                $posts1 = $posts1->where('price', '>', $filters['from-price']);
            }

            if($filters['to-price']){
                $posts1 = $posts1->where('price', '<', $filters['to-price']);
            }

            $posts1 = $posts1->get();


            if($posts1->count()>$n1){
                $posts1 = $posts1->slice(($page-1)*$n1, $n1);
            }

            //posts of your followers
            $posts2 = DB::table('posts')->where('acceptable', 1)
            ->whereIn('user_id',$followers)
            ->orderBy('id')/*->get()*/;

            if($filters['type']){
                $posts2 = $posts2->where('type', $filters['type']);
            }

            if($filters['category']){
                $posts2 = $posts2->where('category', $filters['category']);
            }

            if($filters['city']){
                $posts2 = $posts2->where('city', $filters['city']);
            }

            if($filters['province']){
                $posts2 = $posts2->where('province', $filters['province']);
            }

            if($filters['from-price']){
                $posts2 = $posts2->where('price', '>', $filters['from-price']);
            }

            if($filters['to-price']){
                $posts2 = $posts2->where('price', '<', $filters['to-price']);
            }

            $posts2 = $posts2->get();

            if($posts2->count()>$n2){
                $posts2 = $posts2->slice(($page-1)*$n2, $n2);
            }

            $posts = $posts1->merge($posts2);

            if($residual = ($n1+$n2)-($posts1->count()+$posts2->count())>0){
                //select others
                $all_posts = DB::table('posts')->where('acceptable', 1)->where('updated_at', '>', Carbon::now()->subDay(7))
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
             
            $posts = DB::table('posts')->where('acceptable', 1)->where('updated_at', '>', Carbon::now()->subDay(7))/*->get()*/;

            //filtering the posts
            if($filters['type']){
                $posts = $posts->where('type', $filters['type']);
            }

            if($filters['category']){
                $posts = $posts->where('category', $filters['category']);
            }

            if($filters['city']){
                $posts = $posts->where('city', $filters['city']);
            }

            if($filters['province']){
                $posts = $posts->where('province', $filters['province']);
            }

            if($filters['from-price']){
                $posts = $posts->where('price', '>', $filters['from-price']);
            }

            if($filters['to-price']){
                $posts = $posts->where('price', '<', $filters['to-price']);
            }

            $posts = $posts->get();

            if($posts->count()>($n1+$n2)){
                //$posts = $posts->random($n1+$n2);
                $posts = $posts->slice(($page-1)*($n1+$n2), ($n1+$n2));

            }

            return $posts;


        }

        //return DB::table('posts')->where('removed', 0)->where('acceptable', 1)->orderBy('id')->CursorPaginate(7);
 
        //return DB::table('posts')->where('removed', 0)->where('acceptable', 1)->CursorPaginate(7);
    }

    public function getUserName($id){
        $resp = [];
        $post = Post::find($id);

        array_push($resp, $post->user->name);
        array_push($resp, $id);
        return json_encode($resp);
        
    }

    public function getPostImages($id){
        $resp = [];
        $image_urls = [];
        $post = Post::find($id);
        $post_images = $post->postImages;
        foreach($post_images as $item){
            array_push($image_urls, Storage::url($item->img_path));

        }

        array_push($resp, $image_urls);
        array_push($resp, $id);
        return json_encode($resp);
        
    }

     //save post title.
    public function saveTitle(Request $request){
        $user = Auth::user();

        //taking data from request
        $validated = $request->validate([
            'title' => 'required|max:200',
        ]);

        $post_id = $request['post_id']; 
        $post = Post::find($post_id); 
        if($post){
            $post->title = $validated['title'];
            //$res = $user->posts()->save($post);
            $post->save(); 
            return $post->title;
        }

        return "missing-post";     
    }

    //save post description.
    public function saveDescription(Request $request){
        $user = Auth::user();

        //taking data from request
        $validated = $request->validate([
            'description' => 'required|string|max:2000',
        ]);

        $post_id = $request['post_id']; 
        $post = Post::find($post_id); 
        if($post){
            $post->description = $validated['description'];
            //$res = $user->posts()->save($post); 
            $post->save();
            return $post->description;
        }

        return "missing-post";
           
    }

     //save post price.
    public function savePrice(Request $request){
        $user = Auth::user();

        //taking data from request
        $validated = $request->validate([
            'price' => 'nullable|numeric',
        ]);

        $post_id = $request['post_id']; 
        $post = Post::find($post_id); 
        if($post){
            $post->price = $validated['price'];
            //$res = $user->posts()->save($post); 
            $post->save();
            return $post->price;
        }

        return "missing-post";
           
    }

    //save post price.
    public function saveType(Request $request){
        $user = Auth::user();

        //taking data from request
        $validated = $request->validate([
            'type' => 'nullable|String|max:20',
        ]);

        $post_id = $request['post_id']; 
        $post = Post::find($post_id); 
        if($post){
            $post->type = $validated['type'];
            //$res = $user->posts()->save($post); 
            $post->save();
            return $post->type;
        }

        return "missing-post";
           
    }


    //save post category.
    public function saveCategory(Request $request){
        $user = Auth::user();

        //taking data from request
        $validated = $request->validate([
            'category' => 'nullable|String|max:20',
        ]);

        $post_id = $request['post_id']; 
        $post = Post::find($post_id); 
        if($post){
            $post->category = $validated['category'];
            //$res = $user->posts()->save($post); 
            $post->save();
            return $post->category;
        }

        return "missing-post";
           
    }

     //save post province.
    public function saveProvince(Request $request){
        $user = Auth::user();

        //taking data from request
        $validated = $request->validate([
            'province' => 'nullable|String|max:20',
        ]);

        $post_id = $request['post_id']; 
        $post = Post::find($post_id); 
        if($post){
            $post->province = $validated['province'];
            //$res = $user->posts()->save($post); 
            $post->save();
            return $post->province;
        }

        return "missing-post";
           
    }

      //save post city.
     public function saveCity(Request $request){
        $user = Auth::user();

        //taking data from request
        $validated = $request->validate([
            'city' => 'nullable|String|max:30',
        ]);

        $post_id = $request['post_id']; 
        $post = Post::find($post_id); 
        if($post){
            $post->city = $validated['city'];
            //$res = $user->posts()->save($post); 
            $post->save();
            return $post->city;
        }

        return "missing-post";
           
    }

      //save post city.
     public function saveAddress(Request $request){
        $user = Auth::user();

        //taking data from request
        $validated = $request->validate([
            'address' => 'nullable|String|max:500',
        ]);

        $post_id = $request['post_id']; 
        $post = Post::find($post_id); 
        if($post){
            $post->address = $validated['address'];
            //$res = $user->posts()->save($post); 
            $post->save();
            return $post->address;
        }

        return "missing-post";
           
    }

    public function getUserImage($id){
        $user_id = Post::find($id)->user_id;
        $image_url = User::find($user_id)->img_path;

        return $image_url ?? '';
    }



}
