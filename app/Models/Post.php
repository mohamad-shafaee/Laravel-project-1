<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class Post extends Model
{
    use HasFactory;
    use SpatialTrait;

    protected $spatialFields = ['position'];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
    */
    protected $guarded = [];



    public function user(){
        return $this->belongsTo(User::class);
    }

    public function postImages(){
        return $this->hasMany(PostImage::class);
    }

    public static function moveToRecycledPosts($id){
        $user = Auth::user();
        $post = self::find($id);
        $recy_post = new RecycledPost(['title'=>$post->title, 'description'=>$post->description, 
            'type'=>$post->type, 'category'=>$post->category, 'province'=>$post->province, 'city'=>$post->city, 'price'=>$post->price, 'address'=>$post->address, 'score'=>$post->score]);
            $user->recycledPosts()->save($recy_post); 

            //Move the files of post to a new place
            $post_images = PostImage::where('post_id', $post->id)->get();
            
            foreach($post_images as $img){
                $recy_post_image = new RecycledPostImage;
                $recy_post_image->img_path = 'public/recycledPostImages/'. substr($img->img_path,18);
                $recy_post->recycledPostImages()->save($recy_post_image);
                Storage::move($img->img_path, $recy_post_image->img_path);
                //delete the post images
                $img->delete();
            }

            //move the post_images records to recycled_post_images

            

            //delete the post
            return $post->delete();





    }

    
}