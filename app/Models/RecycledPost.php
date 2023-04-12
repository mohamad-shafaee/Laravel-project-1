<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;

class RecycledPost extends Model
{
    use HasFactory;
    //use SpatialTrait;

    //protected $spatialFields = ['position'];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
    */
    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function recycledPostImages(){
        return $this->hasMany(RecycledPostImage::class);
    }

}