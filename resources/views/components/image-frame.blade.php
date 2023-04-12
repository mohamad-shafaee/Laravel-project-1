<div class = "box select-file-error-box">
                    <ul class = "select-file-error"></ul>
            </div>

<div class="image-frame"> 

    @if($editable == "true")
 
    <div class="img-edit-menu">
        <ul>
            <li class="update-image">@if($url) {{__('form.change-image')}} @else {{__('form.set-image')}} @endif
            </li> 
            
            <li class="remove-image">{{__('form.remove')}} </li> 
        </ul>

    </div>

    @endif

    <input id="image-file" type="file" style="display:none;">
   

    <div class="image-tab">
                           <div class="details">
                           <div class="file-size">${fileSize}</div>
                           <div class="percent">${fileLoaded}%</div>
                           <div class="progress-bar"> 
                           <div class="progress" style="width: 0;"> </div>
                           </div>
                           </div>
                
        <img class="image" src=@if($url) "{{$url}}" @else "{{$alter}}" @endif/>
        
        
    </div>

</div>


<script type="text/javascript">

    $(document).ready(function(){


        if("{{$url}}" == ""){

            if(rmImgBtn = document.querySelector(".image-frame .img-edit-menu ul li.remove-image")){
                rmImgBtn.style.opacity = 0.4;
                rmImgBtn.style.cursor = "none";
                rmImgBtn.style.pointerEvents = "none";


            }

            
        }

    updateBtn = document.querySelector(".image-frame .img-edit-menu ul li.update-image");
    //addbtn = document.querySelector(".edit-post .tab-gallery .img-edit-menu ul li.add-image");

    if(updateBtn){
        updateBtn.addEventListener("click", function(){
        document.querySelector(".image-frame #image-file").click();

    });


    }
    

    fileInput = document.querySelector(".image-frame #image-file");
    fileInput.onchange = ({target}) => {

        let file = target.files[0];

        // allowed types
        let allowed_mime_types = [ 'image/jpg', 'image/jpeg', 'image/png' ];
    
        // allowed max size in MB
        let allowed_size_mb = 20;

                if(file){

                let errors = [];
                let fileName = file.name;

                if(allowed_mime_types.indexOf(file.type) == -1){
                    errors.push(fileName + ": " + "{{__('form.incorrect-file-type')}}");

                }
                if(file.size > allowed_size_mb*1024*1024){
                    errors.push(fileName + ": " + "{{__('form.file-size-exceeded')}}");
                    
                }

            //show errors
            for(let j=0; j<errors.length; j++){

                const ul = document.getElementById("select-file-error");
                const li = document.createElement("li");
                li.appendChild(document.createTextNode(errors[j]+"."));
                ul.appendChild(li);
            }

            if(errors.length == 0){
                uploadFile(file);

            }
            
        }

    }

    //

    function getFileName(file){

         if(file){
                let name = file.name;
                       if(name.length>12){
                    let splitName = name.split('.');
                    name = splitName[0].substring(0,11) + "... ." + splitName[1];
                                         }
                return name;
                }

    } 

    function uploadFile(file){ 
    
    let fileSize = "";
    let fileLoaded = "";

    let fileName = getFileName(file);
        //let inputId = document.querySelector('.image-frame #user_id').value; 
        let fd = new FormData();
        //fd.append('user_id', inputId); 
        fd.append('file', file); 
        
        let request = new XMLHttpRequest(); 

        let imagesrc = URL.createObjectURL(file);

        /*let imageTab = `<div class="details">
                           <div class="file-size">${fileSize}</div>
                           <div class="percent">${fileLoaded}%</div>
                           <div class="progress-bar"> 
                           <div class="progress" style="width: 0;"> </div>
                           </div>
                           </div>
                           <img class="image" />`;

            
            document.querySelector(".image-frame .image-tab").insertAdjacentHTML("beforeend", imageTab);
            document.querySelector(".image-frame .image-tab img.image").src = imagesrc;*/

            document.querySelector(".image-frame .image-tab .details").style.display = "block";
            document.querySelector(".image-frame .image-tab img.image").src = imagesrc;

  

       request.upload.addEventListener("progress", function(event){ 
 
         fileLoaded = Math.floor((event.loaded / event.total) * 100);
                  
        let fileTotal = Math.floor(event.total / 1024);

        if(fileTotal < 1024){
            fileSize = fileTotal + " KB";
        }else{
            fileSize = (fileTotal / 1024).toFixed(3) + " MB";
        } 

       document.querySelector(".image-frame .image-tab .details .file-size").innerHTML = fileSize;
       document.querySelector(".image-frame .image-tab .details .percent").innerHTML = fileLoaded+"%";
       document.querySelector(".image-frame .image-tab .details .progress-bar .progress").style.width = fileLoaded+"%";

      });

     request.onreadystatechange = function(){
            if(this.readyState == XMLHttpRequest.DONE){
                //console.log(req.responseText);
               
                if(this.responseText){
                    console.log(this.responseText);

                    //change update image btn content
                let updateImgBtn = document.querySelector(".image-frame .img-edit-menu ul li.update-image");
 
                if(updateImgBtn.innerHTML.trim() == "{{__('form.set-image')}}".trim()){
                   updateImgBtn.innerHTML = "{{__('form.change-image')}}";
                }

            document.querySelector(".image-frame .img-edit-menu ul li.remove-image").style.opacity = 1;
            document.querySelector(".image-frame .img-edit-menu ul li.remove-image").style.cursor = "pointer";
            document.querySelector(".image-frame .img-edit-menu ul li.remove-image").style.pointerEvents = "auto";

            //hide details div 
            document.querySelector(".image-frame .image-tab .details").style.display = "none";

                }else{
                    
                }
  
            }

        } 

        request.open('POST', "{{route('save-profile-image')}}");
        request.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
  
        request.send(fd); 
    }


    // Removing file
    let removeBtn = document.querySelector(".image-frame .img-edit-menu ul li.remove-image");

    if(removeBtn){

        removeBtn.addEventListener("click", function(){

        //open a dialog to user confirm the removing image.
        let conf = confirm("Are you sure to remove your profile image?");

        if(conf){
            
            //send an Ajax to remove the file from server and database.
        
        let request = new XMLHttpRequest();

         request.onreadystatechange = function(){
            if(this.readyState == XMLHttpRequest.DONE){
                //console.log(req.responseText);
               
                if(this.responseText){
                    console.log(this.responseText);
                    //set a default image for the ui

            document.querySelector(".image-frame .image-tab img").src = "{{$alter}}";
            //disable the remove btn itself
            document.querySelector(".image-frame .img-edit-menu ul li.remove-image").style.opacity = 0.4;
            document.querySelector(".image-frame .img-edit-menu ul li.remove-image").style.cursor = "none";
            document.querySelector(".image-frame .img-edit-menu ul li.remove-image").style.pointerEvents = "none";
                    
                }else{
                    
                }
            }
        }

        request.open('GET', "{{route('remove-profile-image')}}");
        request.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
        request.send(); 


        }


    });


    }
    


});

    </script>
