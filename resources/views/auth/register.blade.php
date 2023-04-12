<x-guest-layout>

    <div class="register-card">
      <div class="logo-box">
        <a href="/">
                <x-application-logo class="logo" />
        </a>
      </div>

      <div class = "title">{{__('form.register')}}</div> 

      @if ($errors->any())

        <div class="alert-box">
         
         <div class="alert-title">
            {{ __('form.errors-title') }}
         </div>

         <ul class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>

        </div>
       @endif 

        <form method="POST" action="#">
            @csrf

            <!-- Phone or Email -->
            <div class = "box phone-email-box">

                <input id="phone-or-email" class="phone-email" name = "pe" type="text" required />
                <label id= "phone-or-email-label" for="phone-email" >{{__('form.phone-or-email')}}</label>

            </div>

            <div class = "alert alert-registered"></div>

            <!-- Password -->
            <div class="box password-box"> 
                <input id="password" type="password" name="password" required />
                <label for="password">{{__('form.pass')}}</label>
            </div>

            <!-- Confirm Password -->
            <div class="box password-box"> 
                <input id="password_confirmation" type="password" name="password_confirmation" required />
                <label for="password">{{__('form.pass-confirm')}}</label>
            </div>

            <div class="already-registered">
                <a href="{{ route('login') }}">
                    {{ __('form.already-registered?') }}
                </a>     
            </div>

            <button class="submit">
                    {{ __('form.register') }}
            </button>

        </form>
    </div>

</x-guest-layout>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script type="text/javascript">


$(document).ready(function(){

    var input = document.querySelector(".register-card form input#phone-or-email");

    input.addEventListener("change", function(){
        //send an ajax request to check that if it has been registered previously.

        //first check that input is Email or Phone number

        let data = input.value;
        

        if(dataValidType(data) == "phone" || dataValidType(data) == "email"){
            let regfd = new FormData();
            if(dataValidType(data) == "phone"){
                            regfd.append('name', 'phone');
                            regfd.append('phone', data);

            } else if (dataValidType(data) == "email"){
                regfd.append('name', 'email');
                regfd.append('email', data);

            }

            let req = new XMLHttpRequest();

        req.onreadystatechange = function(){
            if(req.readyState == XMLHttpRequest.DONE){
                //console.log(req.responseText);
               
                if(req.responseText){
                    document.querySelector(".register-card form .alert.alert-registered").innerHTML = "{{__('form.user-unavailable')}}";

                }else{
                    document.querySelector(".register-card form .alert.alert-registered").innerHTML = "{{__('form.user-available')}}";
                }
                
            }

        }

        req.open('POST', "{{route('check-user-availablity')}}");
        req.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
  
        req.send(regfd);

        } else if (dataValidType(data) == "invalid phone"){

            document.querySelector(".register-card form .alert.alert-registered").innerHTML = "{{__('form.invalid-phone')}}";

        } else if (dataValidType(data) == "invalid email"){

            document.querySelector(".register-card form .alert.alert-registered").innerHTML = "{{__('form.invalid-email')}}";
            
        }



        /*if(dataValidType(data)){

            let regfd = new FormData();
        if(!isNaN(data)){
            //data is a phone number
            regfd.append('name', 'phone');
            regfd.append('phone', data);
        }else{
            regfd.append('name', 'email');
            regfd.append('email', data);
        }

        let req = new XMLHttpRequest();

        req.onreadystatechange = function(){
            if(req.readyState == XMLHttpRequest.DONE){
                //console.log(req.responseText);
               
                if(req.responseText){
                    document.querySelector(".register-card form .alert.alert-registered").innerHTML = "{{__('form.user-unavailable')}}";

                }else{
                    document.querySelector(".register-card form .alert.alert-registered").innerHTML = "{{__('form.user-available')}}";
                }
                
            }

        }

        req.open('POST', "{{route('check-user-availablity')}}");
        req.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
  
        req.send(regfd);

        }*/
        



    });

    function dataValidType(data){
        if(!isNaN(data)){
            //data should be a phone number
            if(data.length == 11){
                return "phone";
            }else{
                return "invalid phone";
            }
        }else{
            //data should be an email
            if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(data)){
                return "email";
            }else{
                return "invalid email";
            }

        }
    }


    
    var btnSubmit = document.querySelector('form button.submit');

    btnSubmit.addEventListener('click', function(event){
        event.preventDefault();

        var inputpe = document.querySelector('form input#phone-or-email');
        var inputpass = document.querySelector('form input#password');

        var peValidated = dataValidType(inputpe.value);
 
        if(peValidated == "phone" || peValidated == "email"){

            if(peValidated == "phone") {

                inputpe.name = 'phone';
             
          $(this).closest('form')
            .attr('action', "{{route('register-by-phone')}}")
            .submit();
 
            } else if (peValidated == "email"){

                   inputpe.name = 'email';
             
          $(this).closest('form')
            .attr('action', "{{route('register-by-email')}}")
            .submit(); 
            }

        } else if (peValidated == "invalid phone") {

            document.querySelector(".register-card form .alert.alert-registered").innerHTML = "{{__('form.invalid-phone')}}";

        } else if (peValidated == "invalid email"){

            document.querySelector(".register-card form .alert.alert-registered").innerHTML = "{{__('form.invalid-email')}}";
            
        }


    });



    });



</script>



