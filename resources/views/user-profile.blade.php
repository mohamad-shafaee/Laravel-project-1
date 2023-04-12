<x-app-layout> 

    @php
 
    $path = $observed->img_path; 
    $id = $observed->id; 
    $alter = url('storage/userImages/default.png');
     
    @endphp

    <div class="box user-profile-box">
        <div class="title user-profile-title">{{__('user.user-infos')}}</div>
 
            <div class="user-image-box">
                <x-image-frame :url="$path" :alter="$alter" :editable="false"/>
             
            </div>
 
        <div class="field-box user-name-box">
            <div class="label">{{__('user.user-name')}}</div>
            <div class="value">{{$observed->name}}</div> 
        </div>

        <div class="field-box user-email-box">
            <div class="label">{{__('user.email')}}</div>
            <div class="value">{{$observed->email}}</div> 
        </div>

        <div class="field-box user-mellicode-box">
            <div class="label">{{__('user.mellicode')}}</div>
            <div class="value">{{$observed->mellicode}}</div> 
        </div>

        <div class="field-box user-phone-box">
            <div class="label">{{__('user.phone')}}</div>
            <div class="value">{{$observed->phone}}</div> 
        </div>

        <div class="follow-box">

            @php

            $isAB = 'N';
            $isBA = 'N'; 

        if(isset($observer)){

        
        //if the observer followed observed previously.
        $isAB = App\Models\Follow::where([['user_id_1', $observer->id], ['user_id_2', $id]])->firstOr(function(){
            return 'isnt';
        });

        //if the observed followed observer previously.
        $isBA = App\Models\Follow::where([['user_id_2', $observer->id], ['user_id_1', $id]])->firstOr(function(){
            return 'isnt';
        });
 
        }
        @endphp

        <a class="follow un" href="#" style="display: none;">{{__('user.unfollow')}}</a> 
        <a class="follow reject" href="#" style="display: none;">{{__('user.reject-following')}}</a>
        <a class="follow up" href="#" style="display: none;">{{__('user.follow')}} </a>

         </div>
 
        
        <div class="close-btn">{{__('form.close')}}</div>
 
    </div> 
</x-app-layout>
 
<script type="text/javascript">

$(document).ready(function(){

    if({{isset($observer)}}){

         if('{{$isAB}}' !== 'isnt'){
        document.querySelector(".user-profile-box .follow-box .follow.un").style.display="block";
    }else{
        document.querySelector(".user-profile-box .follow-box .follow.up").style.display="block";

    }

    /*if('{{$isBA}}' !== 'isnt'){
        document.querySelector(".user-profile-box .follow-box .follow.reject").style.display="block";
    }*/
    }
 
    let unfollow = document.querySelector(".user-profile-box .follow-box .follow.un");
    if(unfollow){
        unfollow.addEventListener("click", function(event){

            event.preventDefault();

            if (confirm("{{__('user.unfollow?')}}") == true){

                let req = new XMLHttpRequest();

        req.onreadystatechange = function(){
            if(req.readyState == XMLHttpRequest.DONE){
                //console.log(req.responseText);
               
                if(req.responseText == 'deleted'){
                    //The user has been unfollowed. So the unfollow tag should be removed and follow tag be appeared.
                    document.querySelector(".user-profile-box .follow-box .follow.un").style.display = "none";
                    document.querySelector(".user-profile-box .follow-box .follow.up").style.display="block";
                    
                }else{
                    
                } 
            }
        }

        req.open('GET', "{{ route('unfollow', ['followed_id' => $observed->id]) }}");
        req.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
        req.send();

            }
        });
    }

    let follow = document.querySelector(".user-profile-box .follow-box .follow.up");

    if(follow){
         follow.addEventListener("click", function(event){
        event.preventDefault();

        let req = new XMLHttpRequest();

        req.onreadystatechange = function(){
            if(req.readyState == XMLHttpRequest.DONE){
                //console.log(req.responseText);
               
                if(req.responseText !== ''){
                    document.querySelector(".user-profile-box .follow-box .follow.un").style.display = "block";
                    document.querySelector(".user-profile-box .follow-box .follow.up").style.display="none";
                    
                }else{
                    
                }
                
            }

        }

        req.open('GET', "{{ route('follow', ['observed_id'=> $observed->id]) }}");
        req.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
        req.send();



    });


    }



       

    
 
});
 
</script>

