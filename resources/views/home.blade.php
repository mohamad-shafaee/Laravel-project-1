<x-app-layout>
   
        <div class="post-container">
  
         </div> 

         <div class="ajax-load text-center" style="display:none">

    <p><img src="{{url('storage/userImages/default.png')}}">Loading More post</p>

</div>

</x-app-layout>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script type="text/javascript">
    //baseStat determines that the page is the main page or a search page
    let baseStat = "{{$base_stat}}";

    let page = 1;
   
   let firstPosts = JSON.parse('{!!$posts!!}');
   
   let uniquePostIds = [];
   let uniquePostIndexes = [];
   let postIds = [];
   let uniquePosts = [];

   firstPosts.forEach(fillPostIds);

   function fillPostIds(item, index, arr){
    postIds.push(item.id);
   }

   //console.log(postIds);

   postIds.forEach(createUniqueIndxs);


   function createUniqueIndxs(item, index, arr){
    if(!uniquePostIds.includes(item)){
       uniquePostIds.push(item);
       uniquePostIndexes.push(index); 
    }
   }

   //console.log(uniquePostIds);
   //console.log(uniquePostIndexes);

   //now we should add an array of objects with unique index
   uniquePostIndexes.forEach(createUniquePosts);

   function createUniquePosts(item, index, arr){
    uniquePosts.push(firstPosts[item]);
   }

   console.log(uniquePosts);

   function insertPost(item){
            let postStructure = `<div class="post post-${item.id}" id="post-${item.id}" >
            <div class="right-side">
            <div class="user-logo"> 

            <img class="user-icon" src="${getUserImage(item.id)}" />

            <div class="user-name">...</div>    
           </div>
           <div class="post-title-box">
            <div class="post-title">${item.title}</div>
           </div> 
                      
           </div>
           <div class="left-side"> <div class="post-date-box">
            <div class="post-date">${item.created_at}</div>
           </div> <div class="image-box"></div>

           </div>
           
            </div>`;
           document.querySelector(".post-container").insertAdjacentHTML("beforeend", postStructure);

           updateUsername(item.id);
           getItemImages(item.id);
        }

        function getUserImage(id){

            let req = new XMLHttpRequest();
            //let formData = new FormData();
            //formData.append("post_id", id);

            req.onreadystatechange = function() {
            if (req.readyState === XMLHttpRequest.DONE) {
                  //const status = request.status;
                  //console.log(status + " aaa " + request.responseText);
                  if(req.responseText !== ''){

                    document.querySelector(".post.post-" + id + " .user-logo img.user-icon").src = req.responseText;
                    
                  } else {

                    document.querySelector(".post.post-" + id + " .user-logo img.user-icon").src = "https://img.freepik.com/free-photo/vibrant-beauty-romantic-flora-nature_1232-4526.jpg";
                    
                  }
               }
            };

            url = "{{route('get-user-image', ['id'=> ':post-id'])}}";
            url = url.replace(':post-id', id);

            req.open('GET', url);
            req.setRequestHeader('X-CSRF-Token', document.head.querySelector('meta[name="csrf-token"]').content);

            req.send(); 

            return "";



        }

        

        function updateUsername(id){
             
        let req = new XMLHttpRequest();
        req.onreadystatechange = function(){
            if(req.readyState == XMLHttpRequest.DONE){
                console.log("The response is:...: " + req.responseText);
                console.log(typeof req.responseText);
                let resp = JSON.parse(req.responseText);
                let id = resp[1];
                document.querySelector(".post-container .post-" + id + " .right-side .user-name").innerHTML = resp[0];    
            }
        } 

        let url = "{{ route('get-user-name', ['id'=> ':postId']) }}";
        url = url.replace(':postId', id); 
        req.open('GET', url);
        req.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
        req.send(); 
        }

        function getItemImages(id){
        //we get up to 3 images of the item here
        let req = new XMLHttpRequest();
        req.onreadystatechange = function(){
            if(req.readyState == XMLHttpRequest.DONE){
                console.log("The response is:...: " + req.responseText);
                console.log(typeof req.responseText);
                let resp = JSON.parse(req.responseText);
                let id = resp[1];
                let image_urls = resp[0];
                let imageTag;

                for(let i=0; i<image_urls.length; i++){
                    console.log(image_urls[i]);
                    imageTag = `<img class="image" id="post-slide-image" src="${image_urls[i]}" />`;


                    document.querySelector(".post-container .post-" + id + " .left-side .image-box").insertAdjacentHTML("beforeend", imageTag);


                }
                

                //document.querySelector(".post-container .post-" + id + " .left-side").innerHTML =;    
            }
        } 

        let url = "{{ route('get-post-images', ['id'=> ':postId']) }}";
        url = url.replace(':postId', id); 
        req.open('GET', url);
        req.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
        req.send();
            
        }


 
    $(document).ready(function(){

        uniquePosts.forEach(insertPost);

        $(window).scroll(function() {

        console.log("scrolling... ");

        //if($(window).scrollTop() + $(window).height() >= $(document).height()) {
            if($(window).scrollTop() + $(window).height() >= ($(document).height()-1)) {

            page = page+1;
            console.log("The page is: " + page);

            loadMoreData(page);


        }

    });
         // The following listener just works for the first posts, not posts after scrollong.  
       

        makeFirstPostsClickable();

 
    });

    function makeFirstPostsClickable(){
        // let presentPosts = [...document.querySelectorAll(".post-container .post")];
        let presentPosts = document.querySelectorAll(".post-container .post");
        for(let i=0; i<presentPosts.length; i++){
            presentPosts[i].addEventListener("click", function(){
                let postId = this.id.split("-")[1];
                //console.log("I am here: " + postId);
                openPost(postId);

            });
        }


    }

    function makePostClickable(item){

        let post = document.querySelector(".post-container .post.post-" + item.id);
        post.addEventListener("click", function(){
                let postId = this.id.split("-")[1];
                //console.log("I am here Again: " + postId);

                openPost(postId);

            });
        

    }

    function openPost(id){
        let url = "{{ route('post-details', ['id'=> ':postId'])}}";
        url = url.replace(':postId', id);
        window.location.href = url;
         
    }
 
    function loadMoreData(page){


         let req = new XMLHttpRequest();

        req.onreadystatechange = function(){
            if(req.readyState == XMLHttpRequest.DONE){
                //console.log(req.responseText);
                let h = JSON.parse(req.responseText);
                let obj = h.posts;

                //object to array
               let arrPosts = Object.values(obj);

                if(arrPosts.length > 0){

                    for(let i=0; i<arrPosts.length; i++){
                        if(!checkUniqueness(arrPosts[i])){
                            insertPost(arrPosts[i]);

                            //add the post id to uniquePostIds array
                            uniquePostIds.push(arrPosts[i].id);

                            console.log("See Here: " + typeof arrPosts[i]);
                            makePostClickable(arrPosts[i]);

                            
                        }

                    }


                }

                    
                
            }
        } 
        let url;

        if(baseStat == "mainpage"){
            url = "{{ route('home') }}" + '?page=' + page;

        } else if(baseStat == "search"){
            url = "{{ route('search') }}" + '?page=' + page;

        } else if(baseStat == "myposts"){
            url = "{{ route('myposts') }}" + '?page=' + page;

        } else if(baseStat == "filter"){
            url = "{{ route('filter_posts') }}" + '?page=' + page;

        }
        //let url = "{{ route('home') }}" + '?page=' + page;
        //console.log(url);

        req.open('GET', url);
        req.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
        req.send();

    }

    function checkUniqueness(item){

        return uniquePostIds.includes(item.id);

    }


    </script>



