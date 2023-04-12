 <x-app-layout> 

    @php 
                    $u_id = $post->user_id; 
                    $post_id = $post->id;
                    
                    $user = Illuminate\Support\Facades\DB::table('users')->where('id', $u_id)->first();
                    $user_name = $user -> name;
                    $user_image = $user -> img_path;
                    $user_phone = $user -> phone;
                    $default_image = url('storage/userImages/default.png');

                    $post_m = App\Models\Post::where('id', $post_id)->first();

                    $owner =  Illuminate\Support\Facades\Gate::allows('update-post', $post_m);
    @endphp
 
    <div class="post-details">

        @if($owner)
        <div class="title"> {{__('post.edit-page')}}</div>
        @endif

        @if(!$owner)
        <div class="user-logo"> 

                    <img class="user-icon" src=@if($user_image) "{{$user_image}}" @else "{{$default_image}}" @endif  />
                    
                    <a class="user-name" href="{{ route('user-profile', ['user_id'=> $post->user_id]) }}">{{ $user_name}}</a>          
        </div>
        @endif
 
        <form id="form-1" method="post" action="{{ route('save-post', ['origin' => 'post']) }}" enctype="multipart/form-data">
            @if($owner)
            @csrf
            @endif

            <div class="box title-box"> 
                <div class="title">{{$post->title}}</div>
                @if($owner)
                <i class="icon edit-icon"></i> 
                <input id="title" name= "title" type="text"
                placeholder="{{__('form.enter-title')}}" value="{{$post->title}}">
                <div class="title-status"></div> 
                @endif
            </div>

            <div class="box date-box"> <div class="date">{{$post->created_at}}</div></div>
            

            <div class="box description-box"> 
                    <div class="description">{{$post->description}}</div>
                    @if($owner)
                    <i class="icon edit-icon"></i> 
                <input id="description" name= "description" type="text" placeholder="{{__('form.enter-description')}}" value="{{$post->description}}">
                <div class="description-status"></div>
                @endif
            </div>

            @php

        //$post = App\Models\Post::where('id', $post_id)->first();
        $img_urls = [];
        $img_ids = [];                   
                     
        foreach($post_m->postImages as $img){
                        
            array_push($img_urls, url('storage/'. substr($img->img_path, 7)));
            array_push($img_ids, $img->id);

                    } 
        @endphp

                @if(count($img_urls) > 0)
                <div class="image-gallery">
                @if(!$owner)
                <x-post-slide-show :urls="$img_urls" :id="$post_id"/>
                @endif
                @if($owner)
                <x-post-images-show :id="$post_id" :imgIds="$img_ids" :urls="$img_urls" />
                @endif
                </div> 
                @endif


                @if(!is_null($user_phone) || !empty($user_phone))
           <div class="box phone-box">
            <label>{{__('post.user-phone')}}</label>
            @guest
            <div class="auth-link">
                <a href="{{ route('login', ['back' => 'post-details', 'id' => $post_id]) }}">{{__('post.auth-for-phone')}}</a>
            </div>
            @endguest
            @auth
             <div class="phone">{{$user_phone}}</div>
             @endauth
           </div>
           @endif 

@if($post->price | $owner)
            <div class = "box price-box"> 
                <label for="price">{{__('form.price')}}</label> 
                    <div class="price">{{$post->price}}</div>
                    @if($owner)
                    <i class="icon edit-icon"></i> 
                    <input id="price" name= "price" type="text" value="{{$post->price}}">
                    <div class="price-status"></div>
                    @endif
            </div>

@endif

@if($post->type !=="" | $owner) 
 
            <div class = "box type-box">
                <label for="type">{{__('form.type')}}</label>
                @if($owner)
                <select id="type" name="type">
                <option value="" >{{__('post.select-one')}}</option>
                 <option value="{{__('post.buy')}}" {{$post->type == __('post.buy') ? 'selected' : ''}}>{{__('post.buy')}}</option>
                 <option value="{{__('post.sell')}}" {{$post->type == __('post.sell') ? 'selected' : ''}}>{{__('post.sell')}}</option>
                <option value="{{__('post.inform')}}" {{$post->type == __('post.inform') ? 'selected' : ''}}>{{__('post.inform')}}</option>
                 
             </select>
             <div class="type-status"></div>
             @endif
             @if(!$owner)
             <div class="type">{{$post->type}}</div>

             @endif
                
            </div>
@endif
@if($post->category !=="" | $owner) 

            <div class = "box category-box">
                <label for="category">{{__('form.category')}}</label>
                @if($owner)
                <select id="category" name="category">
                    <option value="" >{{__('post.select-one')}}</option>
                 <option value="{{__('post.property')}}" {{$post->category == __('post.property') ? 'selected' : ''}}>{{__('post.property')}}</option>
                 <option value="{{__('post.machin')}}" {{$post->category == __('post.machin') ? 'selected' : ''}}>{{__('post.machin')}}</option>
                <option value="{{__('post.electronic')}}" {{$post->category == __('post.electronic') ? 'selected' : ''}}>{{__('post.electronic')}}</option>
                <option value="{{__('post.clothing')}}" {{$post->category == __('post.clothing') ? 'selected' : ''}}>{{__('post.clothing')}}</option>
                <option value="{{__('post.raw')}}" {{$post->category == __('post.raw') ? 'selected' : ''}}>{{__('post.raw')}}</option>
                <option value="{{__('post.food')}}" {{$post->category == __('post.food') ? 'selected' : ''}}>{{__('post.food')}}</option>
                <option value="{{__('post.service')}}" {{$post->category == __('post.service') ? 'selected' : ''}}>{{__('post.service')}}</option>
                 
               </select>
               <div class="category-status"></div>
               @endif
               @if(!$owner)
             <div class="category">{{$post->category}}</div>

             @endif
                
            </div>
@endif
@if($post->province !=="" | $owner) 


            <div class = "box province-box">
                <label for="province">{{__('form.province')}}</label>
                @if($owner)
                <select id="province" name="province">
                    <option value="" >{{__('post.select-one')}}</option>                
<option value="{{__('iranplaces.azarbaijane-sharghi')}}" {{$post->province == __('iranplaces.azarbaijane-sharghi') ? 'selected' : ''}}>{{__('iranplaces.azarbaijane-sharghi')}}</option>
<option value="{{__('iranplaces.azarbaijane-gharbi')}}" {{$post->province == __('iranplaces.azarbaijane-gharbi') ? 'selected' : ''}}>{{__('iranplaces.azarbaijane-gharbi')}}</option>
<option value="{{__('iranplaces.ardebil')}}"
{{$post->province == __('iranplaces.ardebil') ? 'selected' : ''}}>{{__('iranplaces.ardebil')}}</option>
<option value="{{__('iranplaces.isfahan')}}" {{$post->province == __('iranplaces.isfahan') ? 'selected' : ''}}>{{__('iranplaces.isfahan')}}</option>
<option value="{{__('iranplaces.alborz')}}" {{$post->province == __('iranplaces.alborz') ? 'selected' : ''}}>{{__('iranplaces.alborz')}}</option>
<option value="{{__('iranplaces.ilam')}}" {{$post->province == __('iranplaces.ilam') ? 'selected' : ''}}>{{__('iranplaces.ilam')}}</option>
<option value="{{__('iranplaces.booshehr')}}" {{$post->province == __('iranplaces.booshehr') ? 'selected' : ''}}>{{__('iranplaces.booshehr')}}</option>
<option value="{{__('iranplaces.tehran')}}" {{$post->province == __('iranplaces.tehran') ? 'selected' : ''}}>{{__('iranplaces.tehran')}}</option>
<option value="{{__('iranplaces.charmahal')}}" {{$post->province == __('iranplaces.charmahal') ? 'selected' : ''}}>{{__('iranplaces.charmahal')}}</option>
<option value="{{__('iranplaces.khorasan-jonobi')}}" {{$post->province == __('iranplaces.khorasan-jonobi') ? 'selected' : ''}}>{{__('iranplaces.khorasan-jonobi')}}</option>
<option value="{{__('iranplaces.khorasan-razavi')}}" {{$post->province == __('iranplaces.khorasan-razavi') ? 'selected' : ''}}>{{__('iranplaces.khorasan-razavi')}}</option>
<option value="{{__('iranplaces.khorasan-shomali')}}" {{$post->province == __('iranplaces.khorasan-shomali') ? 'selected' : ''}}>{{__('iranplaces.khorasan-shomali')}}</option>
<option value="{{__('iranplaces.khozestan')}}" {{$post->province == __('iranplaces.khozestan') ? 'selected' : ''}}>{{__('iranplaces.khozestan')}}</option>
<option value="{{__('iranplaces.zanjan')}}" {{$post->province == __('iranplaces.zanjan') ? 'selected' : ''}}>{{__('iranplaces.zanjan')}}</option>
<option value="{{__('iranplaces.semnan')}}" {{$post->province == __('iranplaces.semnan') ? 'selected' : ''}}>{{__('iranplaces.semnan')}}</option>
<option value="{{__('iranplaces.sistan')}}" {{$post->province == __('iranplaces.sistan') ? 'selected' : ''}}>{{__('iranplaces.sistan')}}</option>
<option value="{{__('iranplaces.fars')}}" {{$post->province == __('iranplaces.fars') ? 'selected' : ''}}>{{__('iranplaces.fars')}}</option>
<option value="{{__('iranplaces.ghazvin')}}" {{$post->province == __('iranplaces.ghazvin') ? 'selected' : ''}}>{{__('iranplaces.ghazvin')}}</option>
<option value="{{__('iranplaces.ghom')}}" {{$post->province == __('iranplaces.ghom') ? 'selected' : ''}}>{{__('iranplaces.ghom')}}</option>
<option value="{{__('iranplaces.kordestan')}}" {{$post->province == __('iranplaces.kordestan') ? 'selected' : ''}}>{{__('iranplaces.kordestan')}}</option>
<option value="{{__('iranplaces.kerman')}}" {{$post->province == __('iranplaces.kerman') ? 'selected' : ''}}>{{__('iranplaces.kerman')}}</option>
<option value="{{__('iranplaces.kermanshah')}}" {{$post->province == __('iranplaces.kermanshah') ? 'selected' : ''}}>{{__('iranplaces.kermanshah')}}</option>
<option value="{{__('iranplaces.kohgiloye')}}" {{$post->province == __('iranplaces.kohgiloye') ? 'selected' : ''}}>{{__('iranplaces.kohgiloye')}}</option>
<option value="{{__('iranplaces.golestan')}}" {{$post->province == __('iranplaces.golestan') ? 'selected' : ''}}>{{__('iranplaces.golestan')}}</option>
<option value="{{__('iranplaces.gilan')}}" {{$post->province == __('iranplaces.gilan') ? 'selected' : ''}}>{{__('iranplaces.gilan')}}</option>
<option value="{{__('iranplaces.lorestan')}}" {{$post->province == __('iranplaces.lorestan') ? 'selected' : ''}}>{{__('iranplaces.lorestan')}}</option>
<option value="{{__('iranplaces.mazandaran')}}" {{$post->province == __('iranplaces.mazandaran') ? 'selected' : ''}}>{{__('iranplaces.mazandaran')}}</option>
<option value="{{__('iranplaces.markazi')}}" {{$post->province == __('iranplaces.markazi') ? 'selected' : ''}}>{{__('iranplaces.markazi')}}</option>
<option value="{{__('iranplaces.hormozgan')}}" {{$post->province == __('iranplaces.hormozgan') ? 'selected' : ''}}>{{__('iranplaces.hormozgan')}}</option>
<option value="{{__('iranplaces.hamedan')}}" {{$post->province == __('iranplaces.hamedan') ? 'selected' : ''}}>{{__('iranplaces.hamedan')}}</option>
<option value="{{__('iranplaces.yazd')}}" {{$post->province == __('iranplaces.yazd') ? 'selected' : ''}}>{{__('iranplaces.yazd')}}</option>
             </select>
             <div class="province-status"></div>
             @endif
             @if(!$owner)
             <div class="province">{{$post->province}}</div>

             @endif 
            </div>
@endif
@if($post->city !=="" | $owner) 

            <div class = "box city-box"> 
                <label for="city">{{__('form.city')}}</label> 
                    <div class="city">{{$post->city}}</div>
                    @if($owner)
                    <i class="icon edit-icon"></i> 
                    <input id="city" name= "city" type="text" value="{{$post->city}}">
                    <div class="city-status"></div>
                    @endif
            </div>
@endif
@if($post->address !=="" | $owner) 
 
            <div class = "box address-box"> 
                <label for="address">{{__('form.address')}}</label> 
                    <div class="address">{{$post->address}}</div>
                    @if($owner)
                    <i class="icon edit-icon"></i> 
                    <input id="address" name= "address" type="text" value="{{$post->address}}"  >
                    <div class="address-status"></div>
                    @endif
            </div>
@endif 

            @if($owner)

            <input type = "hidden" name = "post-id" value = "{{$post_id}}">

            @endif

            
            


        </form>

        <div class ="alert post-save-alert" id="post-save-alert"></div>
        <button class="close"></button>

    </div> 

</x-app-layout>


<script type="text/javascript">

    document.addEventListener("DOMContentLoaded", function(){

        document.querySelector(".post-details .title-box .edit-icon")
    .addEventListener("click", function(){
        document.querySelector(".post-details .title-box input").style.display = "block";
        document.querySelector(".post-details .title-box .title").style.display = "none";
        document.querySelector(".post-details .title-box .edit-icon").style.display = "none";
    });

    document.querySelector(".post-details .description-box .edit-icon")
    .addEventListener("click", function(){
        document.querySelector(".post-details .description-box input").style.display = "block";
        document.querySelector(".post-details .description-box .description").style.display = "none";
        document.querySelector(".post-details .description-box .edit-icon").style.display = "none";
    });

    document.querySelector(".post-details .price-box .edit-icon")
    .addEventListener("click", function(){
        document.querySelector(".post-details .price-box input").style.display = "block";
        document.querySelector(".post-details .price-box .price").style.display = "none";
        document.querySelector(".post-details .price-box .edit-icon").style.display = "none";
    });

    document.querySelector(".post-details .city-box .edit-icon")
    .addEventListener("click", function(){
        document.querySelector(".post-details .city-box input").style.display = "block";
        document.querySelector(".post-details .city-box .city").style.display = "none";
        document.querySelector(".post-details .city-box .edit-icon").style.display = "none";
    });

    document.querySelector(".post-details .address-box .edit-icon")
    .addEventListener("click", function(){
        document.querySelector(".post-details .address-box input").style.display = "block";
        document.querySelector(".post-details .address-box .address").style.display = "none";
        document.querySelector(".post-details .address-box .edit-icon").style.display = "none";
    });
 
    $("#province").change(function(){
        let val = $(this).val();
        if(val == "{{__('iranplaces.khorasan-razavi')}}"){
           $("#city").html("<option value ='{{__('iranplaces.gonabad')}}'>{{__('iranplaces.gonabad')}}</option><option value ='{{__('iranplaces.torbate-haydarie')}}'>{{__('iranplaces.torbate-haydarie')}}</option>");

        }
    }); 

     form1 = document.getElementById('form-1');
     form1.onsubmit = (e) => {
         e.preventDefault();
         let alert = document.querySelector("#post-save-alert");
         //alert.className = "";
         //alert.classList.add('alert', 'post-save-alert');
         let formData = new FormData(form1);
         let request  = new XMLHttpRequest();
          
        request.onreadystatechange = function() {
                if (request.readyState === XMLHttpRequest.DONE) {
                  const status = request.status;
                if (status === 0 || (status >= 200 && status < 400)) {
                 // The request has been completed successfully 
                 alert.classList.add('success');
                 alert.innerHTML = "{{__('form.post-updated')}}"; 
                 
                  } else {
                  // Oh no! There has been an error with the request!
                  alert.classList.add('error');
                 alert.innerHTML = "{{__('form.post-updating-error')}}";
                  
               }
             }
        };

            setTimeout(function(){ 
               alert.innerHTML = ""; 
               if(alert.classList.contains('success')){
                       alert.classList.remove('success');
                }else if(alert.classList.contains('error')){
                    alert.classList.remove('error');
                         }
            
                 }, 3000);

             request.open('POST', "{{route('save-post')}}");

             request.send(formData); 
            };

            //End of form-1 submission.  
   
    let closeBtn = document.querySelector(".post-details button.close");
    closeBtn.addEventListener("click", function(){
        window.location.href = "{{ route('home')}}";

    });
 
});

///////////////////////////////////



  function validated(type, input){
        if(type == "title"){
            if(input.length > 1 && input.length < 201){
             return input; 
         }else{
            return false;
         }
        }
 
        if(type == "price"){
        
            if(isNaN(input)){
                return false;
            }else{
                return input;
            }
        }

         if(type == "desc"){
        
            if(input.length < 2000){
                return input;
            }else{
                return false;
            }
        }

        if(type == "city"){
        
            if(input.length < 30){
                return input;
            }else{
                return false;
            }
        }

        if(type == "address"){
        
            if(input.length < 500){
                return input;
            }else{
                return false;
            }
        }


        
    }

     let inputTitle = document.querySelector('.post-details form .title-box input');
     let titleStat = document.querySelector('.post-details form .title-box div.title-status');

     inputTitle.addEventListener("change", function(){
        let fd = new FormData();

        //let input = document.querySelector('.post-details form .title-box input').value;
        let input = this.value;

        let title = validated("title", input);
        if(title){

            fd.append('title', title);

        fd.append('post_id', "{{$post->id}}");
        let req = new XMLHttpRequest();
        
        req.onreadystatechange = function(){
            if(req.readyState == XMLHttpRequest.DONE){
                if(req.responseText == title.trim()){
                    //console.log("A true res: "+ req.responseText);
                    titleStat.classList.add('success');
                    titleStat.innerHTML = "{{__('post.title-saved')}}";

                }else{
                    titleStat.classList.add('error');
                    titleStat.innerHTML = "{{__('post.title-save-error')}}";

                }                    
            }
        }

        setTimeout(function(){ 
               titleStat.innerHTML = ""; 
               if(titleStat.classList.contains('success')){
                       titleStat.classList.remove('success');
                }else if(titleStat.classList.contains('error')){
                    titleStat.classList.remove('error');
                         } 
                 }, 5000); 

        let url = "{{ route('save-post-title') }}";
        req.open('POST', url);
        //req.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
        req.setRequestHeader('X-CSRF-Token', document.head.querySelector('meta[name="csrf-token"]').content);
        req.send(fd);
        }else{
               titleStat.classList.add('error');
               titleStat.innerHTML = "{{__('post.invalid-title')}}";


        }
        
    });


     let inputDesc = document.querySelector('.post-details form .description-box input');
     let descStat = document.querySelector('.post-details form .description-box div.description-status');

     inputDesc.addEventListener("change", function(){
        let fd = new FormData();

        //let input = document.querySelector('.post-details form .title-box input').value;
        let input = this.value;

        let desc = validated("desc", input);
        if(desc){

            fd.append('description', desc);

        fd.append('post_id', "{{$post->id}}");
        let req = new XMLHttpRequest();
        
        req.onreadystatechange = function(){
            if(req.readyState == XMLHttpRequest.DONE){
                if(req.responseText == desc.trim()){
                    //console.log("A true res: "+ req.responseText);
                    descStat.classList.add('success');
                    descStat.innerHTML = "{{__('post.description-saved')}}";

                }else{
                    descStat.classList.add('error');
                    descStat.innerHTML = "{{__('post.description-save-error')}}";

                }                    
            }
        }

        setTimeout(function(){ 
               descStat.innerHTML = ""; 
               if(descStat.classList.contains('success')){
                       descStat.classList.remove('success');
                }else if(descStat.classList.contains('error')){
                    descStat.classList.remove('error');
                         } 
                 }, 5000); 

        let url = "{{ route('save-post-description') }}";
        req.open('POST', url);
        //req.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
        req.setRequestHeader('X-CSRF-Token', document.head.querySelector('meta[name="csrf-token"]').content);
        req.send(fd);
        }else{
               descStat.classList.add('error');
               descStat.innerHTML = "{{__('post.invalid-description')}}";


        }
        
    });



     let inputPrice = document.querySelector('.post-details form .price-box input');
     let priceStat = document.querySelector('.post-details form .price-box div.price-status');

     inputPrice.addEventListener("change", function(){
        let fd = new FormData();

        //let input = document.querySelector('.post-details form .title-box input').value;
        let input = this.value;

        let price = validated("price", input);
        if(price){

            fd.append('price', price);

        fd.append('post_id', "{{$post->id}}");
        let req = new XMLHttpRequest();
        
        req.onreadystatechange = function(){
            if(req.readyState == XMLHttpRequest.DONE){
                if(req.responseText == price.trim()){
                    //console.log("A true res: "+ req.responseText);
                    priceStat.classList.add('success');
                    priceStat.innerHTML = "{{__('post.price-saved')}}";

                }else{
                    priceStat.classList.add('error');
                    priceStat.innerHTML = "{{__('post.price-save-error')}}";

                }                    
            }
        }

        setTimeout(function(){ 
               priceStat.innerHTML = ""; 
               if(priceStat.classList.contains('success')){
                       priceStat.classList.remove('success');
                }else if(priceStat.classList.contains('error')){
                    priceStat.classList.remove('error');
                         } 
                 }, 5000); 

        let url = "{{ route('save-post-price') }}";
        req.open('POST', url);
        //req.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
        req.setRequestHeader('X-CSRF-Token', document.head.querySelector('meta[name="csrf-token"]').content);
        req.send(fd);
        }else{
               priceStat.classList.add('error');
               priceStat.innerHTML = "{{__('post.invalid-price')}}";


        }
        
    });

     let inputType = document.querySelector('.post-details form .type-box select');
     let typeStat = document.querySelector('.post-details form .type-box div.type-status');

     inputType.addEventListener("change", function(){
        let fd = new FormData();

        //let input = document.querySelector('.post-details form .title-box input').value;
        let input = this.value;

        if(input){

            fd.append('type', input);

        fd.append('post_id', "{{$post->id}}");
        let req = new XMLHttpRequest();
        
        req.onreadystatechange = function(){
            if(req.readyState == XMLHttpRequest.DONE){
                if(req.responseText == input){
                    //console.log("A true res: "+ req.responseText);
                    typeStat.classList.add('success');
                    typeStat.innerHTML = "{{__('post.type-saved')}}";

                }else{
                    typeStat.classList.add('error');
                    typeStat.innerHTML = "{{__('post.type-save-error')}}";

                }                    
            }
        }

        setTimeout(function(){ 
               typeStat.innerHTML = ""; 
               if(typeStat.classList.contains('success')){
                       typeStat.classList.remove('success');
                }else if(typeStat.classList.contains('error')){
                    typeStat.classList.remove('error');
                         } 
                 }, 5000); 

        let url = "{{ route('save-post-type') }}";
        req.open('POST', url);
        //req.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
        req.setRequestHeader('X-CSRF-Token', document.head.querySelector('meta[name="csrf-token"]').content);
        req.send(fd);
        }else{
               typeStat.classList.add('error');
               typeStat.innerHTML = "{{__('post.invalid-type')}}";


        }
        
    });


     let inputCategory = document.querySelector('.post-details form .category-box select');
     let categoryStat = document.querySelector('.post-details form .category-box div.category-status');

     inputCategory.addEventListener("change", function(){
        let fd = new FormData();

        //let input = document.querySelector('.post-details form .title-box input').value;
        let input = this.value;

        if(input){

            fd.append('category', input);

        fd.append('post_id', "{{$post->id}}");
        let req = new XMLHttpRequest();
        
        req.onreadystatechange = function(){
            if(req.readyState == XMLHttpRequest.DONE){
                if(req.responseText == input){
                    //console.log("A true res: "+ req.responseText);
                    categoryStat.classList.add('success');
                    categoryStat.innerHTML = "{{__('post.category-saved')}}";

                }else{
                    categoryStat.classList.add('error');
                    categoryStat.innerHTML = "{{__('post.category-save-error')}}";

                }                    
            }
        }

        setTimeout(function(){ 
               categoryStat.innerHTML = ""; 
               if(categoryStat.classList.contains('success')){
                       categoryStat.classList.remove('success');
                }else if(categoryStat.classList.contains('error')){
                    categoryStat.classList.remove('error');
                         } 
                 }, 5000); 

        let url = "{{ route('save-post-category') }}";
        req.open('POST', url);
        //req.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
        req.setRequestHeader('X-CSRF-Token', document.head.querySelector('meta[name="csrf-token"]').content);
        req.send(fd);
        }else{
               categoryStat.classList.add('error');
               categoryStat.innerHTML = "{{__('post.invalid-category')}}";


        }
        
    });

     let inputProvince = document.querySelector('.post-details form .province-box select');
     let provinceStat = document.querySelector('.post-details form .province-box div.province-status');

     inputProvince.addEventListener("change", function(){
        let fd = new FormData();

        //let input = document.querySelector('.post-details form .title-box input').value;
        let input = this.value;

        if(input){

            fd.append('province', input);

        fd.append('post_id', "{{$post->id}}");
        let req = new XMLHttpRequest();
        
        req.onreadystatechange = function(){
            if(req.readyState == XMLHttpRequest.DONE){
                if(req.responseText == input){
                    //console.log("A true res: "+ req.responseText);
                    provinceStat.classList.add('success');
                    provinceStat.innerHTML = "{{__('post.province-saved')}}";

                }else{
                    provinceStat.classList.add('error');
                    provinceStat.innerHTML = "{{__('post.province-save-error')}}";

                }                    
            }
        }

        setTimeout(function(){ 
               provinceStat.innerHTML = ""; 
               if(provinceStat.classList.contains('success')){
                       provinceStat.classList.remove('success');
                }else if(provinceStat.classList.contains('error')){
                    provinceStat.classList.remove('error');
                         } 
                 }, 5000); 

        let url = "{{ route('save-post-province') }}";
        req.open('POST', url);
        //req.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
        req.setRequestHeader('X-CSRF-Token', document.head.querySelector('meta[name="csrf-token"]').content);
        req.send(fd);
        }else{
               provinceStat.classList.add('error');
               provinceStat.innerHTML = "{{__('post.invalid-province')}}";


        }
        
    });


     let inputCity = document.querySelector('.post-details form .city-box input');
     let cityStat = document.querySelector('.post-details form .city-box div.city-status');

     inputCity.addEventListener("change", function(){
        let fd = new FormData();

        let input = this.value;

        let city = validated("city", input);
        if(city){

            fd.append('city', city);

        fd.append('post_id', "{{$post->id}}");
        let req = new XMLHttpRequest();
        
        req.onreadystatechange = function(){
            if(req.readyState == XMLHttpRequest.DONE){
                if(req.responseText == city.trim()){
                    //console.log("A true res: "+ req.responseText);
                    cityStat.classList.add('success');
                    cityStat.innerHTML = "{{__('post.city-saved')}}";

                }else{
                    cityStat.classList.add('error');
                    cityStat.innerHTML = "{{__('post.city-save-error')}}";

                }                    
            }
        }

        setTimeout(function(){ 
               cityStat.innerHTML = ""; 
               if(cityStat.classList.contains('success')){
                       cityStat.classList.remove('success');
                }else if(cityStat.classList.contains('error')){
                    cityStat.classList.remove('error');
                         } 
                 }, 5000); 

        let url = "{{ route('save-post-city') }}";
        req.open('POST', url);
        //req.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
        req.setRequestHeader('X-CSRF-Token', document.head.querySelector('meta[name="csrf-token"]').content);
        req.send(fd);
        }else{
               cityStat.classList.add('error');
               cityStat.innerHTML = "{{__('post.invalid-city')}}";


        }
        
    });



     let inputAddress = document.querySelector('.post-details form .address-box input');
     let addressStat = document.querySelector('.post-details form .address-box div.address-status');

     inputAddress.addEventListener("change", function(){
        let fd = new FormData();

        let input = this.value;

        let address = validated("address", input);
        if(address){

            fd.append('address', address);

        fd.append('post_id', "{{$post->id}}");
        let req = new XMLHttpRequest();
        
        req.onreadystatechange = function(){
            if(req.readyState == XMLHttpRequest.DONE){
                if(req.responseText == address.trim()){
                    //console.log("A true res: "+ req.responseText);
                    addressStat.classList.add('success');
                    addressStat.innerHTML = "{{__('post.address-saved')}}";

                }else{
                    addressStat.classList.add('error');
                    addressStat.innerHTML = "{{__('post.address-save-error')}}";

                }                    
            }
        }

        setTimeout(function(){ 
               addressStat.innerHTML = ""; 
               if(addressStat.classList.contains('success')){
                       addressStat.classList.remove('success');
                }else if(addressStat.classList.contains('error')){
                    addressStat.classList.remove('error');
                         } 
                 }, 5000); 

        let url = "{{ route('save-post-address') }}";
        req.open('POST', url);
        //req.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
        req.setRequestHeader('X-CSRF-Token', document.head.querySelector('meta[name="csrf-token"]').content);
        req.send(fd);
        }else{
               addressStat.classList.add('error');
               addressStat.innerHTML = "{{__('post.invalid-address')}}";


        }
    
    });



/*$(document).ready(function(){

        
    });*/








</script>

