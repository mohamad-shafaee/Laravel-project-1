<x-guest-layout>

    <div class="confirm-phone-card">
      <div class="logo-box">
        <a href="/">
                <x-application-logo class="logo" />
        </a>
      </div>

      <div class = "title">{{__('user.confirm-phone')}}</div> 

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

            <!-- OTP -->
            <div class = "box otp-box">

                <input id="otp" class="otp" name = "otp" type="text" required />
                <label id= "otp-label" for="otp" >{{__('form.otp')}}</label>

            </div>

            <div class = "alert otp-alert"></div>

           

            <div class="resend-otp">
                    {{ __('form.resend-otp') }}    
            </div>

            <button class="submit">
                    {{ __('form.confirm-otp') }}
            </button>


        </form>

        <button class="close" style="display:none;">
                    {{ __('form.close') }}
            </button>
    </div>

</x-guest-layout>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script type="text/javascript">


$(document).ready(function(){

    let resend = document.querySelector(".confirm-phone-card .resend-otp");
    resend.addEventListener("click", function(){
        let req = new XMLHttpRequest();
        //let fd = new FormData();
        //fd.append('name', 'phone');

        req.onreadystatechange = function(){
            if(req.readyState == XMLHttpRequest.DONE){
                console.log(req.responseText);
               
                if(req.responseText == 'opt-sent'){
                    document.querySelector(".confirm-phone-card .otp-alert").innerHTML = "{{__('user.otp-sent')}}";

                }else if (req.responseText == 'otp-throttled'){
                    document.querySelector(".confirm-phone-card .otp-alert").innerHTML = "{{__('user.otp-send-throttled')}}";
                }
                
            }

        }

        req.open('GET', "{{route('resend-otp')}}");
        req.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
  
        req.send();



    });


    let inputOtp = document.querySelector(".confirm-phone-card form input.otp");
    inputOtp.addEventListener("change", function(){

        token = inputOtp.value;

        check = verifyPhoneOtp(token);
    });

    function verifyPhoneOtp(token){

        let req = new XMLHttpRequest();
        let fd = new FormData();
        fd.append('token', token); 

        req.onreadystatechange = function(){
            if(req.readyState == XMLHttpRequest.DONE){
                console.log(req.responseText  + typeof req.responseText);
                if(req.responseText == 'confirmed'){
                    document.querySelector(".confirm-phone-card .otp-alert").innerHTML = "{{__('user.otp-confirmed')}}";

                    document.querySelector(".confirm-phone-card button.close").style.display = 'block';




                } else if(req.responseText == 'not-confirmed') {
                    document.querySelector(".confirm-phone-card .otp-alert").innerHTML = "{{__('user.otp-confirmation-error')}}";

                }
 
            }

        } 

        req.open('POST', "{{route('confirm-otp')}}");
        req.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
  
        req.send(fd);

    }

    let closeBtn = document.querySelector(".confirm-phone-card button.close");
    closeBtn.addEventListener("click", function(){
        window.location.href = "{{ route('home')}}";

    });



    });



</script>



