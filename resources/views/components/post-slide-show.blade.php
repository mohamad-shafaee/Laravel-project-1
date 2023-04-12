 <div class="slide-image-box box-{{$id}}"> 
	<img class="image" id="post-slide-image" src="<?php echo $urls[0] ?>" />
	<div></div>
	@if(count($urls)>1)
	<a class="next" id="next-{{$id}}" >N</a>
    <a class="prev" id="prev-{{$id}}">P</a>
    @endif
</div>

@once 
 <script type="text/javascript">
//These arrays are declared before other codes since they are used during for loop of the posts
//current image of each post which is set to zero initially.
//Note that if the post has not any image it is still set to zero.
let postsInfo = new Array();
let currentPostsStat = new Array();

</script> 
@endonce

 
@push('scripts') 
<script type="text/javascript">
//This script is pushed since it have time to all post be published.
 
$(document).ready(function(){ 

for(let i=0; i<postsInfo.length; i++){
	currentPostsStat[i] = [postsInfo[i][0], 0];
} 
//console.log(currentPostsStat);

//let nexts = new Array();
//let prevs = new Array();

let next = document.querySelector(".slide-image-box .next");
next.addEventListener("click", function(){
	let postId = this.id.split("-")[1]; 
		let src = getNextImgAndInc(postId);

		document.querySelector(".slide-image-box.box-"+ postId + " img.image").src = src;

});


//Gives the next image src and updates the currentPostsStat
function getNextImgAndInc(postId){
	
	let currentImgId = currentPostsStat.find(element => element[0] == postId)[1];
	//console.log("The current :" + currentImgId);
	let postInfo = postsInfo.find(element => element[0] == postId);
	let len = postInfo[2];
	let nextImgId;

		if(currentImgId < (len-1)){
			
			++currentPostsStat.find(element => element[0] == postId)[1];
			nextImgId = currentImgId + 1;

		}else{
			  currentPostsStat.find(element => element[0] == postId)[1] = 0;
			  nextImgId = 0;
		}

	let nextSrc = postInfo[1][nextImgId];
	//console.log(nextSrc); 
	return nextSrc; 

}

let prev = document.querySelector(".slide-image-box .prev");
prev.addEventListener("click", function(){
        let postId = this.id.split("-")[1]; 
		let src = getPrevImgAndDec(postId);
		//console.log(src + "  ***  " + "{{count($urls)}}" + "   " + postId);
		document.querySelector(".slide-image-box.box-"+ postId + " img.image").src = src;

});


//Gives the previous image src and updates the currentPostsStat
function getPrevImgAndDec(postId){
	
	let currentImgId = currentPostsStat.find(element => element[0] == postId)[1];
	//console.log("The current :" + currentImgId);
	let postInfo = postsInfo.find(element => element[0] == postId);
	let len = postInfo[2];
	let prevImgId;

		if(currentImgId > 0){
			
			--currentPostsStat.find(element => element[0] == postId)[1];
			prevImgId = currentImgId - 1;

		}else{
			  currentPostsStat.find(element => element[0] == postId)[1] = len-1;
			  prevImgId = len-1;
		}

	let prevSrc = postInfo[1][prevImgId];
	//console.log(prevSrc); 
	return prevSrc;  
} 
 
});
 
</script> 
@endpush

<script type="text/javascript">

$(".slide-image-box .box-{{$id}}").ready(function(){

	postsInfo.push([{{$id}}, @json($urls), {{count($urls)}}]);

});

</script> 

 



    
