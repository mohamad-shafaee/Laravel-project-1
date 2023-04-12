@php
$count = count($urls);
@endphp

<div id = "select-file-error" class = "box select-file-error-box">
                    <ul></ul>
            </div>

<div class="tab-gallery" id = "tab-gallery">
 
	<div class="img-edit-menu">
		<ul>
			<li class = "add-image">{{__('form.add-image')}}</li>
			<li class = "remove-image">{{__('form.remove')}}</li>
		</ul>

    </div>

    <input id="image-files" type="file" multiple style="display:none;">
    <input id ="post_id" type = "hidden" name = "post-id" value = "{{$id}}">
 
<div class="tabs">

    @if($count)
 
@for($i=0; $i< $count; $i++)
 
<div class="tab tab-{{$i}}">
	
		<img class="image" id="image" src="<?php echo $urls[$i] ?>" />
		<input id="select" type="checkbox" name="select">
		<div id = "post-image-id" style="display: none;">{{$imgIds[$i]}}</div>
 
</div>
 
@endfor
@endif

</div> 
</div>
 
@once 
@push('scripts')
<script type="text/javascript">

	$(document).ready(function(){

	addbtn = document.querySelector(".tab-gallery .img-edit-menu ul li.add-image");
    //addbtn = document.querySelector(".edit-post .tab-gallery .img-edit-menu ul li.add-image");
	addbtn.addEventListener("click", function(){
		document.querySelector(".tab-gallery #image-files").click();

	});

	var sfe = document.querySelector(".tab-gallery #select-file-error");

	fileInput = document.querySelector(".tab-gallery #image-files");
	let startIndx = 0;
    let request = [];

    //To set an i for added images 
    let countplus = [];

    //To account removed images
    //let removedimgs = [];
    //For image ids of the added images. with this ids the
    //image could be deleted immediately after uploading
    //without page refresh.
    let newlySavedImageIds = [];

	    fileInput.onchange = ({target}) => { 
        let allowedFiles = [];
        let files = target.files; 
        let num = files.length; 

        // allowed types
        let allowed_mime_types = [ 'image/jpg', 'image/jpeg', 'image/png' ];
    
        // allowed max size in MB
        let allowed_size_mb = 20;

        if(files){

            let fileNames = [];
            let errors = [];
            for(let i=0; i < num; i++){
                let name = files[i].name;
                fileNames[i] = name;
                if(allowed_mime_types.indexOf(files[i].type) == -1){
                    errors[i] = name + ": " + "{{__('form.incorrect-file-type')}}";
                    continue;

                }
                if(files[i].size > allowed_size_mb*1024*1024){
                    errors[i] = name + ": " + "{{__('form.file-size-exceeded')}}";
                    continue;
                }
                allowedFiles.push(files[i]);
            }

            //show errors
            for(let j=0; j<errors.length; j++){

                const ul = document.getElementById("select-file-error");
                const li = document.createElement("li");
                li.appendChild(document.createTextNode(errors[j]+"."));
                ul.appendChild(li);
            }

            let endInd = startIndx + allowedFiles.length;

            let fi = 0;

            for(let i=startIndx; i < endInd; i++){

            	//countplus shows the index for newly added images
                countplus[i] = {{$count}} + i;

                uploadFile(allowedFiles[fi], i);
                fi = fi + 1;

                
            }
            startIndx = endInd;
        }
    }

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

    function uploadFile(file, index){ 
    
    let fileSize = "";
    let fileLoaded = "";

    let fileName = getFileName(file);
        let inputId = document.querySelector('.tab-gallery #post_id').value; 
        let fd = new FormData();
        fd.append('post_id', inputId); 
        fd.append('file', file); 
        
        request[index] = new XMLHttpRequest(); 

        var imagesrc = URL.createObjectURL(file);

        let imageTab = `<div class="tab tab-${countplus[index]}">
                           <div class="details">
                           <div class="file-size">${fileSize}</div>
                           <div class="percent">${fileLoaded}%</div>
                           <div class="progress-bar"> 
                           <div class="progress" style="width: 0;"> </div>
                           </div>
                           </div>
		                   <img class="image" id="image" style="opacity: 1;" />
                           </div>`;

            
        	document.querySelector(".tab-gallery .tabs").insertAdjacentHTML("beforeend", imageTab);
        	document.querySelector(".tab-gallery .tabs .tab-" + countplus[index] + " img").src = imagesrc;
  
       request[index].upload.addEventListener("progress", function(event){ 
 
         fileLoaded = Math.floor((event.loaded / event.total) * 100);
                  
        let fileTotal = Math.floor(event.total / 1024);

        if(fileTotal < 1024){
            fileSize = fileTotal + " KB";
        }else{
            fileSize = (fileTotal / 1024).toFixed(3) + " MB";
        } 

       document.querySelector(".tab-gallery .tabs .tab-" + countplus[index] + " .file-size").innerHTML = fileSize;
       document.querySelector(".tab-gallery .tabs .tab-" + countplus[index] + " .percent").innerHTML = fileLoaded+"%";
       document.querySelector(".tab-gallery .tabs .tab-" + countplus[index] + " .progress-bar .progress").style.width = fileLoaded+"%";

      });

     request[index].onreadystatechange = function(){
            if(this.readyState == XMLHttpRequest.DONE){
                //console.log(req.responseText);
               
                if(this.responseText){
                	console.log(this.responseText);

                	//save id to make edit or delete of it possible
                	newlySavedImageIds[index] = this.responseText;
                	//insert one input tag with this id to related tab

                    let additionalHtml = `<input id="select" type="checkbox" name="select">
                                           <div id = "post-image-id" style="display: none;">${newlySavedImageIds[index]}</div>`;

                    document.querySelector(".tab-gallery .tabs .tab-" + countplus[index])
                    .insertAdjacentHTML("beforeend", additionalHtml);

                    document.querySelector(".tab-gallery .tabs .tab-" + countplus[index] + " img").style.opacity = 1;
                    document.querySelector(".tab-gallery .tabs .tab-" + countplus[index] + " .details").style.display = "none"; 

                }else{
                    
                }   
            } 
        } 

        request[index].open('POST', "{{route('save-post-files')}}");
        request[index].setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
  
        request[index].send(fd); 
    } 
 
	// For removing images
    //It is better to send data by ajax and dont refresh the page

	rmbtn = document.querySelector(".tab-gallery .img-edit-menu ul li.remove-image");
    //rmbtn = document.querySelector(".edit-post .tab-gallery .img-edit-menu ul li.remove-image");
	rmbtn.addEventListener("click", function(){
		
		//let tabi;
	    let isselectedi;
	    let id;
	    let selectedImageIds = [];
        //we should remove the count of removed images in the below count. 
		/*for(let i = 0; i < ({{$count}} + countplus.length - removedimgs.length); i++){
			//tabi = document.querySelector(".edit-post .tab-gallery .tab-" + i);
            //tabi = document.querySelector(".tab-gallery .tab-" + i);
            tabi = document.querySelector(".tab-gallery .tab");
			isselectedi = tabi.querySelector("input#select").checked;
			id = tabi.querySelector("div#post-image-id").innerHTML;
			//console.log("The value: "+id);
			//console.log("The check: "+isselectedi);
			if(isselectedi){
				selectedImageIds.push(id);
			}
		}*/
 
        let allTabs = [...document.querySelectorAll(".tab-gallery .tab")];
        for(let i=0; i < allTabs.length; i++){

            isselectedi = allTabs[i].querySelector("input#select").checked;
            if(isselectedi){
                id = allTabs[i].querySelector("div#post-image-id").innerHTML;
                selectedImageIds.push(id);
            }


            
            //console.log("The value: "+id);
            //console.log("The check: "+isselectedi);
            

        }

		// Sending the images to be deleted by creating a route.
        // In this method the page will be refreshed. 
        // So this method is not recomended. 

		// calling a route for deleting the selected images. 
		//let url = "{{ route('delete.post.images', ['postId' => $id, 'array' => ':imgArray']) }}";
		//url = url.replace(':imgArray', selectedImageIds);
		//console.log("The url is: "+url);
	    //location.href = url;


        //sending by Ajax

        ////////////

        let fd = new FormData();
        fd.append('post_id', {{$id}}); 
        let json_arr = JSON.stringify(selectedImageIds);

        fd.append('array', json_arr); 
        
        let req = new XMLHttpRequest(); 

             req.onreadystatechange = function(){
            if(this.readyState == XMLHttpRequest.DONE){
                //console.log(req.responseText);
               
                if(this.responseText){
                    console.log(this.responseText);
                    console.log(JSON.parse(this.responseText));
                    let deleted = JSON.parse(this.responseText);
                    

                    // now we should remove the deleted images from user interface. 
                    let tabs = [...document.querySelectorAll(".tab-gallery .tab")]; //nodelist to array
                    

                    for(let i=0; i<tabs.length; i++){

                        let valuei = tabs[i].querySelector("div#post-image-id").innerHTML;
                        

                        if(deleted.includes(valuei)){

                            tabs[i].remove();
                            
                        }
                         
                    }
                                        
                }else{
                    
                }
                
            }

        } 

        req.open('POST', "{{route('delete.post.images')}}");
        req.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
  
        req.send(fd);  
		
	});
 

	});

	
</script>
@endpush

@endonce

    
