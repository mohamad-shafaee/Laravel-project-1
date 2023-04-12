     
                   @php

                   $postsArray = array();

                   @endphp


                    @foreach($posts as $post)

                    @php

                    $u_id = $post->user_id; 
                    $post_id = $post->id;
                    
 
                    $user = Illuminate\Support\Facades\DB::table('users')->where('id', $u_id)->first();
                    $user_name = $user -> name;
                    $user_phone = $user -> phone;

                    $post_m = App\Models\Post::where('id', $post_id)->first();

                    $owner =  Illuminate\Support\Facades\Gate::allows('update-post', $post_m);

                    $img_urls = array();
                     
                    foreach($post_m->postImages as $img){
                        //$img_urls[] = url('storage/'. substr($img->img_path, 7));
                        array_push($img_urls, url('storage/'. substr($img->img_path, 7))); 
                    } 
                   
                    @endphp

                    @if(!in_array($post_id, $postsArray))

                    @php
                    array_push($postsArray, $post_id);


                    @endphp
 
            <div class="post" id="post-{{$post->id}}">
            <div class="right-side">
                
            <div class="user-logo"> 

                    @if(asset('storage/userImages/user-'. $post->user_id .'.jpg'))
                    <img class="user-icon" src="{{URL::asset('storage/userImages/user-'. $post->user_id .'.jpg')}}" />
                    @else
                    <img class="user-icon" src="{{URL::asset('storage/userImages/default.png')}}" />
                    @endif
         
                    <a class="user-name" href="{{ route('user-profile', ['user_id'=> $post->user_id]) }}">{{ $user_name}}</a>
                     
           </div>
           <div class="post-title-box">
           <!-- <div class="pre-word">{{__('post.title')}}</div> -->
            <div class="post-title">{{$post->title}}</div>
           </div>

           @if(!is_null($post->description) || !empty($post->description))
           <div class="post-description-box">
            <!--<div class="pre-word">{{__('post.description')}}</div> -->
            <div class="post-description">{{$post->description}}</div>
           </div>
           @endif
          <!--
           @if(!is_null($post->type) || !empty($post->type))
           <div class="post-type">{{$post->type}}</div>
           @endif
           -->

           @if(!is_null($user_phone) || !empty($user_phone))
           <div class="post-user-phone-box">
            <div class="pre-word">{{__('post.user-phone')}}</div>
            @guest
            <div class="auth-link">
                <a href="{{ route('login') }}">{{__('post.auth-for-phone')}}</a>
            </div>
            @endguest
            @auth
             <div class="post-user-phone">{{$user_phone}}</div>
             @endauth
           </div>
           @endif 
           
           @if(!is_null($post->created_at) || !empty($post->created_at)) 
           <div class="post-date-box">
            <div class="pre-word">{{__('post.created-date')}}</div>
            <div class="post-date">{{$post->created_at}}</div>
           </div>
           @endif

           <div class = "more-less">
           <div class = "more" id="more-{{$post->id}}">{{__('post.more')}}</div>  
           <div class = "less" id="less-{{$post->id}}">{{__('post.less')}}</div>  
           </div>
               
            @if($owner)
            <div class="post-edit">
                  <ul> 
                       <li class="edit"><a href="{{ route('post-edit-page', ['id' => $post->id]) }}">{{__('post.edit')}}</a></li>
                       <li class="delete"><a href="{{ route('delete-post', ['id' => $post->id]) }}">{{__('post.delete')}}</a></li>

                  </ul>

            </div>

           @endif 
            
           <div class = "share-box">
                   <ul>
                        <li><i class = "pm">{{__('general.pergarmap')}}</i></li>
                        <li><i class = "pm">{{__('general.instagram')}}</i></li>

                    </ul>
 
            </div>
 
            </div>
 
            <div class="left-side">

                @if(count($img_urls) > 0)
                <x-post-slide-show :urls="$img_urls" :id="$post_id"/> 
                @endif

            </div> 

            </div> 
            @endif
            @endforeach
 
@once 
@push('scripts')
<script type="text/javascript"> 

    

    var mors = new Array();
    
    mors = [...document.querySelectorAll(".post .right-side .more-less .more")];

    for(let i = 0; i < mors.length; i++){
        mors[i].addEventListener("click", function(){
            //let indx = mors.indexOf(this);

            //let typ = document.querySelector(".post-" + indx + " .post-type");
            //typ.style.display = "block";

            let postId = this.id.split("-")[1];

            //console.log("The post-id is: " + postId);

            let userPhone = document.querySelector("#post-" + postId + " .post-user-phone-box");
            userPhone.style.display = "block";

            //let stat = document.querySelector("#post-" + postId + " .post-status-box");
           // stat.style.display = "block";

            let dat = document.querySelector("#post-" + postId + " .post-date-box");
            dat.style.display = "block";

            let more = document.querySelector("#post-" + postId + " .more-less .more");
            more.style.display = "none";

            let less = document.querySelector("#post-" + postId + " .more-less .less");
            less.style.display = "block";



        });
    }
     
     // for less ...

     var leses = new Array();
    leses = [...document.querySelectorAll(".post .right-side .more-less .less")];

    for(let i = 0; i < leses.length; i++){
        leses[i].addEventListener("click", function(){
            //let indx = leses.indexOf(this);

           // let typ = document.querySelector(".post-" + indx + " .post-type");
            //typ.style.display = "none";
             let postId = this.id.split("-")[1];

            let userPhone = document.querySelector("#post-" + postId + " .post-user-phone-box");
            userPhone.style.display = "none";

            //let stat = document.querySelector("#post-" + postId + " .post-status-box");
            //stat.style.display = "none";

            let dat = document.querySelector("#post-" + postId + " .post-date-box");
            dat.style.display = "none";

            let less = document.querySelector("#post-" + postId + " .more-less .less");
            less.style.display = "none";

            let more = document.querySelector("#post-" + postId + " .more-less .more");
            more.style.display = "block";

        });
    }

    // End of less

  // for delete ...

     var deletes = new Array();
    deletes = [...document.querySelectorAll(".post .right-side .post-edit .delete a")];

    for(let i = 0; i < deletes.length; i++){
        deletes[i].addEventListener("click", function(event){ 

            if (!confirm("{{__('form.delete-post?')}}")){
                event.stopPropagation(); 
                event.preventDefault();

            }

        });
    }

    // End of less




</script>
@endpush

@endonce
