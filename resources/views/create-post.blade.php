<x-app-layout> 

    @php

    $type = "";
    $category = "";
    $country = "";
    $province = "";
    $city = "";
    $address = "";
    $title = "";
    $description = "";     

    if(isset($post)){ 
        $post->type ? $type = $post->type : $type ="";
        $post->category ? $category = $post->category : $category ="";
        $post->country ? $country = $post->country : $country ="";
        $post->province ? $province = $post->province : $province ="";
        $post->city ? $city = $post->city : $city ="";
        $post->address ? $address = $post->address : $address =""; 
        $post->title ? $title = $post->title : $title ="";
        $post->description ? $description = $post->description : $description =""; 
        if($title == "untitled") $title = "";
        if($description == "empty") $description = "";
        $post->price ? $price = $post->price : $price =""; 
        $post_id = $post->id;

    }
 
    @endphp
 
    <div class = "create-post">

        <div class = "title">{{__('form.create-title')}}</div>

        <div id="map"></div>


        <form id = "form-1" method = "post" action = "{{ route('save-post') }}" enctype = "multipart/form-data">
            @csrf

            <div class = "box title-box">
                <label for="title">{{__('form.title')}}</label>
                <input id="title" name= "title" type="text"
                placeholder="{{__('form.enter-title')}}" value="{{old('title') ? old('title') : $title}}">
            </div>

            <div class = "box description-box">
                <label for="description">{{__('form.description')}}</label>
                <input id="description" name= "description" type="text" placeholder="{{__('form.enter-description')}}" value="{{old('description') ? old('description') : $description}}">
            </div>

                <!--
                <div id="map" style="height: 400px;"></div>
                -->

            <div class = "more"> {{__('form.complete-more-optional-later')}}</div>

            <div class = "box price-box">
                <label for="price">{{__('form.price')}}</label>
                <input id="price" name= "price" type="text" value="{{old('price') ? old('price') : $price}}">
            </div>


            <div class = "box type-box">
                <label for="type">{{__('form.type')}}</label>
                <select id="type" name="type">
                <option value="" >{{__('post.select-one')}}</option>
                 <option value="{{__('post.buy')}}" {{$post->type == __('post.buy') ? 'selected' : ''}}>{{__('post.buy')}}</option>
                 <option value="{{__('post.sell')}}" {{$post->type == __('post.sell') ? 'selected' : ''}}>{{__('post.sell')}}</option>
                <option value="{{__('post.inform')}}" {{$post->type == __('post.inform') ? 'selected' : ''}}>{{__('post.inform')}}</option>
                 
             </select>
                
            </div>

            <div class = "box category-box">
                <label for="category">{{__('form.category')}}</label>
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
                
            </div>
 
            <div class = "box province-box">
                <label for="province">{{__('form.province')}}</label>

                <select id="province" name="province">
                    <option value="" >{{__('post.select-one')}}</option>
                 <option value="{{__('iranplaces.khorasan-razavi')}}" {{$post->province == __('iranplaces.khorasan-razavi') ? 'selected' : ''}}>{{__('iranplaces.khorasan-razavi')}}</option>
                 <option value="{{__('iranplaces.khorasan-jonobi')}}" {{$post->province == __('iranplaces.khorasan-jonobi') ? 'selected' : ''}}>{{__('iranplaces.khorasan-jonobi')}}</option>
                <option value="{{__('iranplaces.khorasan-shomali')}}" {{$post->province == __('iranplaces.khorasan-shomali') ? 'selected' : ''}}>{{__('iranplaces.khorasan-shomali')}}</option>
                 
             </select> 
            </div>

            <div class = "box city-box">
                <label for="city">{{__('form.city')}}</label>
                <select id="city" name="city">
                    <option value="" >{{__('post.select-one')}}</option>
             </select>
                
            </div>
 
            <div class = "box address-box">
                <label for="address">{{__('form.address')}}</label>
                <input id="address" name= "address" type="text" value="{{old('address') ? old('address') : $address}}"  >
            </div>
 
            <input type = "hidden" name = "post-id" value = "{{$post_id}}">

            <div class = "box submit-box">
                <input id = "submit-1" type ="submit" value="{{__('form.register-post')}}" >
            </div>

        </form>
 
        <div class ="alert post-save-alert" id="post-save-alert"></div>
 
        <div id = "select-file-error" class = "box select-file-error-box">
                    <ul></ul>
            </div>

            <button class = "box close-box"> 
                
            </button>

        @php


        $img_urls = [];
        $img_ids = [];
        $id = $post->id;
                     
        foreach($post->PostImages as $img){
                        
            array_push($img_urls, url('storage/'. substr($img->img_path, 7)));
            array_push($img_ids, $img->id);

        }

        @endphp
 
        <x-post-images-show :id="$id" :imgIds="$img_ids" :urls="$img_urls" />     

    </div> 

</x-app-layout> 

<script type="text/javascript"> 

$(document).ready(function(){
 
     form1 = document.getElementById('form-1');
     form1.onsubmit = (e) => {
         e.preventDefault();
         let alert = document.querySelector("#post-save-alert"); 
         let formData = new FormData(form1);
         let request  = new XMLHttpRequest();
          
        request.onreadystatechange = function() {
                if (request.readyState === XMLHttpRequest.DONE) {
                    console.log(request.responseText);
                  const status = request.status;
                if (status === 0 || (status >= 200 && status < 400)) {
                 // The request has been completed successfully
                 alert.classList.add('success');
                 alert.innerHTML = "{{__('form.post-updated')}}"; 
                 
                  } else if(request.responseText == "unacceptable-post") {
                  // Oh no! There has been an error with the request!
                  alert.classList.add('error');
                 //alert.innerHTML = "{{__('form.post-updating-error')}}";
                  alert.innerHTML = "{{__('form.unacceptable-post')}}";
                  
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

            let closeBtn = document.querySelector(".create-post button.close-box");
    closeBtn.addEventListener("click", function(){
        window.location.href = "{{ route('home')}}";

    });  
 
});
 
</script>

