<x-app-layout> 

    @php

    $user = auth()->user();
    $path = $user->img_path;

    $id = $user->id;

    $alter = url('storage/userImages/default.png');
     
    @endphp

    <div class="box user-profile-box">
        <div class="title user-profile-title">{{__('user.user-infos')}}</div>
 
            <div class="user-image-box">
                <x-image-frame :url="$path" :alter="$alter" :editable="true"/>
             
            </div>

        <form id="form" method="post" action="#" enctype="multipart/form-data">
            @csrf

        <div class="field-box user-name-box">
            <div class="label">{{__('user.user-name')}}</div>
            <div class="value">{{$user->name}}</div>
            <i class="icon edit-icon"></i>
            <input class="input-name" id="user-name" name= "user-name" type="text" placeholder="{{__('user.user-name')}}" value="{{$user->name}}"><div class="name-status"></div>
        </div>

        <div class="field-box user-email-box">
            <div class="label">{{__('user.email')}}</div>
            <div class="value">{{$user->email}}</div>
            <i class="icon edit-icon"></i>
            <input id="user-email" name= "email" type="text" placeholder="{{__('user.email')}}" value="{{$user->email}}"><div class="email-status"></div>
        </div>

        <div class="field-box user-mellicode-box">
            <div class="label">{{__('user.mellicode')}}</div>
            <div class="value">{{$user->mellicode}}</div>
            <i class="icon edit-icon"></i>
            <input id="user-mellicode" name= "mellicode" type="text" placeholder="{{__('user.mellicode')}}" value="{{$user->mellicode}}"><div class="mellicode-status"></div>
        </div>

        <div class="field-box user-phone-box">
            <div class="label">{{__('user.phone')}}</div>
            <div class="value">{{$user->phone}}</div>
            <i class="icon edit-icon"></i>
            <input id="user-phone" name= "phone" type="text" placeholder="{{__('user.phone')}}" value="{{$user->phone}}"><div class="phone-status"></div>
        </div>

        
        <div class="field-box passwords-box">
            <div class="label">{{__('user.change-password')}}</div>

            <div class="password-change-box">

            <!-- Old Password -->
            <div class="box password-box"> 
                <label for="password">{{__('form.current-pass')}}</label>
                <input id="current-password" type="password" name="current_password" />
            </div>

            <!-- Password -->
            <div class="box password-box"> 
                <label for="password">{{__('form.new-pass')}}</label>
                <input id="new-password" type="password" name="password" />
            </div>

            <!-- Confirm Password -->
            <div class="box password-box"> 
                <label for="password">{{__('form.new-pass-confirm')}}</label>
                <input id="new-password-confirmation" type="password" name="password_confirmation" />
            </div>

            <div class="password-status"></div>

            <button class="submit-pass">
                    {{ __('form.save-pass') }}
            </button>

             </div>

            

        </div>

            

    </form> 
    </div> 
</x-app-layout>
 
<script type="text/javascript">

$(document).ready(function(){

    let passChangeStat = 0;

    let nameEditBtn = document.querySelector(".user-profile-box .user-name-box .edit-icon");
    let emailEditBtn = document.querySelector(".user-profile-box .user-email-box .edit-icon");
    let codeEditBtn = document.querySelector(".user-profile-box .user-mellicode-box .edit-icon");
    let phoneEditBtn = document.querySelector(".user-profile-box .user-phone-box .edit-icon");
    let passChange = document.querySelector(".user-profile-box .passwords-box .label");

    nameEditBtn.addEventListener("click", function(){
        document.querySelector(".user-profile-box .user-name-box .value").style.display = "none";
        document.querySelector(".user-profile-box .user-name-box .edit-icon").style.display = "none";
        //document.querySelector(".user-profile-box .user-name-box .save-icon").style.display = "block";
        document.querySelector(".user-profile-box .user-name-box input").style.display = "block";
    }); 

    let inputName = document.querySelector('.user-profile-box .user-name-box input');
    let nameStat =  document.querySelector('.user-profile-box .user-name-box .name-status');

    let inputEmail = document.querySelector('.user-profile-box .user-email-box input');
    let emailStat =  document.querySelector('.user-profile-box .user-email-box .email-status');

    let inputMellicode = document.querySelector('.user-profile-box .user-mellicode-box input');
    let mellicodeStat =  document.querySelector('.user-profile-box .user-mellicode-box .mellicode-status');

    let inputPhone = document.querySelector('.user-profile-box .user-phone-box input');
    let phoneStat =  document.querySelector('.user-profile-box .user-phone-box .phone-status');

    let passStat =  document.querySelector('.user-profile-box .passwords-box .password-status');

    function validated(type, input){
        if(type == "name"){
            if(input.length > 1 && input.length < 21){
             return input; 
         }else{
            return false;
         }
        }

        if(type == "email"){
            if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(input)){
                return input;
            }else{
                return false;
            }
        }

        if(type == "mellicode"){
        
            if(/^\d{10}$/.test(input)){
                return input;
            }else{
                return false;
            }
        }

        if(type == "phone"){
        
            if(/^\d{11}$/.test(input)){
                return input;
            }else{
                return false;
            }
        }

        if(type == "password"){
        
            if(input.length >= 8){
                return input;
            }else{
                return false;
            }
        }


    }

    inputName.addEventListener("change", function(){
        let fd = new FormData();

        let input = document.querySelector('.user-profile-box .user-name-box input').value;
        let name = validated("name", input);
        if(name){

            fd.append('user_name', name);

        fd.append('user_id', "{{auth()->user()->id}}");
        let req = new XMLHttpRequest();
        
        req.onreadystatechange = function(){
            if(req.readyState == XMLHttpRequest.DONE){
                if(req.responseText == name){
                    //console.log("A true res: "+ req.responseText);
                    nameStat.classList.add('success');
                    nameStat.innerHTML = "{{__('profile.name-saved')}}";

                    //change the username in all view parts
                    document.querySelector(".header .header__top .dropdown-toggle .name").innerHTML = name;

                }else{
                    nameStat.classList.add('error');
                    nameStat.innerHTML = "{{__('profile.name-save-error')}}";

                }                    
            }
        }

        setTimeout(function(){ 
               nameStat.innerHTML = ""; 
               if(nameStat.classList.contains('success')){
                       nameStat.classList.remove('success');
                }else if(nameStat.classList.contains('error')){
                    nameStat.classList.remove('error');
                         } 
                 }, 5000); 

        let url = "{{ route('save-username-profile') }}";
        req.open('POST', url);
        req.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
        req.send(fd);
        }else{
               nameStat.classList.add('error');
               nameStat.innerHTML = "{{__('profile.invalid-name')}}";


        }
        
    });

    inputEmail.addEventListener("change", function(){
        let fd = new FormData();

        let input = document.querySelector('.user-profile-box .user-email-box input').value;

        let email = validated("email", input);
        if(email){
                   fd.append('user_email', email);

        fd.append('user_id', "{{auth()->user()->id}}");
        let req = new XMLHttpRequest();
        
        req.onreadystatechange = function(){
            if(req.readyState == XMLHttpRequest.DONE){
                console.log("A true res: "+ req.responseText + "  " + typeof req.responseText);

                if(req.responseText == email){
                    //console.log("A true res: "+ req.responseText);
                    emailStat.classList.add('success');
                    emailStat.innerHTML = "{{__('profile.email-saved')}}";

                }else if(JSON.parse(req.responseText).user_email){
                    let errorObj = JSON.parse(req.responseText);
                    //console.log("the message " + typeof errorObj);
                    let message = errorObj.user_email;
                    //console.log("the message " + message);
                    emailStat.classList.add('error');
                    //emailStat.innerHTML = "{{__('profile.email-save-error')}}";
                    emailStat.innerHTML = message;                  

                }                    
            }
        }

        setTimeout(function(){ 
               emailStat.innerHTML = ""; 
               if(emailStat.classList.contains('success')){
                       emailStat.classList.remove('success');
                }else if(emailStat.classList.contains('error')){
                    emailStat.classList.remove('error');
                         } 
                 }, 5000); 

        let url = "{{ route('save-useremail-profile') }}";
        req.open('POST', url);
        req.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
        req.send(fd); 
           }else{
               emailStat.classList.add('error');
               emailStat.innerHTML = "{{__('profile.invalid-email')}}"; 
        } 
    });



     inputMellicode.addEventListener("change", function(){
        let fd = new FormData();

        let input = document.querySelector('.user-profile-box .user-mellicode-box input').value;
        let mellicode = validated("mellicode", input);
        if(mellicode){

            fd.append('mellicode', mellicode);

        fd.append('user_id', "{{auth()->user()->id}}");
        let req = new XMLHttpRequest();
        
        req.onreadystatechange = function(){
            if(req.readyState == XMLHttpRequest.DONE){
                console.log("A true res: "+ req.responseText);
                if(req.responseText == mellicode){
                    //console.log("A true res: "+ req.responseText);
                    mellicodeStat.classList.add('success');
                    mellicodeStat.innerHTML = "{{__('profile.mellicode-saved')}}";

                }else if(JSON.parse(req.responseText).mellicode){
                    let errorObj = JSON.parse(req.responseText);
                    //console.log("the message " + typeof errorObj);
                    let message = errorObj.mellicode;
                    //console.log("the message " + message);
                    mellicodeStat.classList.add('error');
                    
                    mellicodeStat.innerHTML = message;                  

                }                   
            }
        }

        setTimeout(function(){ 
               mellicodeStat.innerHTML = ""; 
               if(mellicodeStat.classList.contains('success')){
                       mellicodeStat.classList.remove('success');
                }else if(mellicodeStat.classList.contains('error')){
                    mellicodeStat.classList.remove('error');
                         } 
                 }, 5000); 

        let url = "{{ route('save-mellicode-profile') }}";
        req.open('POST', url);
        req.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
        req.send(fd);
        }else{
               mellicodeStat.classList.add('error');
               mellicodeStat.innerHTML = "{{__('profile.invalid-mellicode')}}";


        }
        
    });


      inputPhone.addEventListener("change", function(){
        let fd = new FormData();

        let input = document.querySelector('.user-profile-box .user-phone-box input').value;
        let phone = validated("phone", input);
        if(phone){

            fd.append('phone', phone);

        fd.append('user_id', "{{auth()->user()->id}}");
        let req = new XMLHttpRequest();
        
        req.onreadystatechange = function(){
            if(req.readyState == XMLHttpRequest.DONE){
                console.log("A true res: "+ req.responseText);
                if(req.responseText == phone){
                    //console.log("A true res: "+ req.responseText);
                    phoneStat.classList.add('success');
                    phoneStat.innerHTML = "{{__('profile.phone-saved')}}";

                }else if(JSON.parse(req.responseText).phone){
                    let errorObj = JSON.parse(req.responseText);
                    //console.log("the message " + typeof errorObj);
                    let message = errorObj.phone;
                    //console.log("the message " + message);
                    phoneStat.classList.add('error');
                    
                    phoneStat.innerHTML = message;                  

                }                   
            }
        }

        setTimeout(function(){ 
               phoneStat.innerHTML = ""; 
               if(phoneStat.classList.contains('success')){
                       phoneStat.classList.remove('success');
                }else if(phoneStat.classList.contains('error')){
                    phoneStat.classList.remove('error');
                         } 
                 }, 5000); 

        let url = "{{ route('save-phone-profile') }}";
        req.open('POST', url);
        req.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
        req.send(fd);
        }else{
               phoneStat.classList.add('error');
               phoneStat.innerHTML = "{{__('profile.invalid-phone')}}";


        }
        
    });





    ////////////////

    emailEditBtn.addEventListener("click", function(){
        document.querySelector(".user-profile-box .user-email-box .value").style.display = "none";
        document.querySelector(".user-profile-box .user-email-box .edit-icon").style.display = "none";
        document.querySelector(".user-profile-box .user-email-box input").style.display = "block";
    });

    codeEditBtn.addEventListener("click", function(){
        document.querySelector(".user-profile-box .user-mellicode-box .value").style.display = "none";
        document.querySelector(".user-profile-box .user-mellicode-box .edit-icon").style.display = "none";
        document.querySelector(".user-profile-box .user-mellicode-box input").style.display = "block";
    });

    phoneEditBtn.addEventListener("click", function(){
        document.querySelector(".user-profile-box .user-phone-box .value").style.display = "none";
        document.querySelector(".user-profile-box .user-phone-box .edit-icon").style.display = "none";
        document.querySelector(".user-profile-box .user-phone-box input").style.display = "block";
    });

    passChange.addEventListener("click", function(){

        let passbox = document.querySelector(".user-profile-box .passwords-box .password-change-box");

        if(passChangeStat == 0){
            passbox.style.display = "block";
            passChangeStat = 1;
        }else{
            passbox.style.display = "none";
            passChangeStat = 0;
        }     
    });


    submitPassBtn = document.querySelector(".user-profile-box .passwords-box .submit-pass");
    submitPassBtn.addEventListener("click", function(event){
        event.preventDefault();

        let fd = new FormData();

        let currPass = document.querySelector('.user-profile-box .passwords-box .password-change-box .password-box input#current-password').value;
        let newPass = document.querySelector('.user-profile-box .passwords-box .password-change-box .password-box input#new-password').value;
        let newConfPass = document.querySelector('.user-profile-box .passwords-box .password-change-box .password-box input#new-password-confirmation').value;

        let newPassV = validated("password", newPass);
        if(newPassV){

            fd.append('current_password', currPass);
            fd.append('new_password', newPassV);
            fd.append('new_password_confirmation', newConfPass);

        fd.append('user_id', "{{auth()->user()->id}}");
        let req = new XMLHttpRequest();
        
        req.onreadystatechange = function(){
            if(req.readyState == XMLHttpRequest.DONE){
                console.log("A true res: "+ req.responseText);
                if(req.responseText == 'password-changed'){
                    //console.log("A true res: "+ req.responseText);
                    passStat.classList.add('success');
                    passStat.innerHTML = "{{__('profile.new-password-saved')}}";

                }else if(
                JSON.parse(req.responseText).current_password | 
                JSON.parse(req.responseText).new_password |
                JSON.parse(req.responseText).new_password_confirmation){
                    let errorObj = JSON.parse(req.responseText);
                    //console.log("the message " + typeof errorObj);
                    let message = errorObj.current_password +
                                  errorObj.new_password +
                                  errorObj.new_password_confirmation;
                    //console.log("the message " + message);
                    passStat.classList.add('error');
                    
                    passStat.innerHTML = message;                  

                }                   
            }
        }

        setTimeout(function(){ 
               passStat.innerHTML = ""; 
               if(passStat.classList.contains('success')){
                       passStat.classList.remove('success');
                }else if(passStat.classList.contains('error')){
                    passStat.classList.remove('error');
                         } 
                 }, 5000); 

        let url = "{{ route('save-password') }}";
        req.open('POST', url);
        req.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
        req.send(fd);
        }else{
               passStat.classList.add('error');
               passStat.innerHTML = "{{__('profile.invalid-password')}}";


        }



    });
 
});
 
</script>

