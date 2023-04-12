<x-app-layout>
   
        <div class="post-container">

            @isset($posts) 
            @if($posts)
             
              <x-posts-component :posts="$posts"> </x-posts-component> 

            @endif  
            @endisset
  
         </div> 

         <div class="ajax-load text-center" style="display:none">

    <p><img src="{{url('storage/userImages/default.png')}}">Loading More post</p>

</div>

</x-app-layout>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script type="text/javascript">

    let page = 1;
   
 
 
    $(document).ready(function(){ 


        $(window).scroll(function() {

        console.log("scrolling... ");

        //if($(window).scrollTop() + $(window).height() >= $(document).height()) {
            if($(window).scrollTop() + $(window).height() >= ($(document).height()-1)) {

            page = page+1;
            console.log("The page is: " + page);

            loadMoreData(page);

        }

    });
 
    });
 
    function loadMoreData(page){


         let req = new XMLHttpRequest();

        req.onreadystatechange = function(){
            if(req.readyState == XMLHttpRequest.DONE){
                //console.log(req.responseText);
                //Note that for the next line to work we should remove the toJson() function after 
                //gather posts function in the MainPageController
                let h = JSON.parse(req.responseText);
                //console.log(h.html); 
                document.querySelector(".post-container").insertAdjacentHTML("beforeend", h.html);
                //console.log('type is: '+ " *** " +h.posts);    
                
            }
        }



        let url = "{{ route('home') }}" + '?page=' + page;
        console.log(url);

        req.open('GET', url);
        req.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
        req.send();

    }

    

    </script>



