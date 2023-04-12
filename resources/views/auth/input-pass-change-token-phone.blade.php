<x-guest-layout>
    <div class="forgot-pass-card">
      <div class="logo-box">
        <a href="/">
                <x-application-logo class="logo" />
        </a>
      </div>

        <div class="forgot-pass-args">
            {{ __('form.enter-phone-otp') }}
        </div>

        

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

        <form method="POST" id="enter-otp" action="#">
            @csrf

            <!-- otp -->
            <div class = "box otp-box">

                <input id="otp" class="otp" name = "otp" type="text" required />
                <label id= "otp-label" for="otp" >{{__('form.otp')}}</label>
                <input type = "hidden" id="phone" name = "phone" value = "{{$phone}}">

            </div>

            <div class = "alert alert-otp"></div>


            <button class="submit">
                    {{ __('form.submit') }}
            </button>

        </form>
    </div>
</x-guest-layout>



<script type="text/javascript">

    document.addEventListener("DOMContentLoaded", function(){

        function validateOtp(data){
        if(!isNaN(data) & data.length == 6){
          return true;
        }else{
           return false;

        }
    } 
    
    let btnSubmit = document.querySelector('form button.submit');
    let alert = document.querySelector('form .alert.alert-otp');

    btnSubmit.addEventListener('click', function(event){
        event.preventDefault();

        let input = document.querySelector(".forgot-pass-card form input.otp").value;

        let isOtpValid = validateOtp(input);
 
        if(isOtpValid){

           let formData = new FormData();
           formData.append("token", input);
           let phone = document.querySelector(".forgot-pass-card form input#phone").value;
           formData.append("phone", phone);
           let request  = new XMLHttpRequest(); 
          
           request.onreadystatechange = function() {
                if (request.readyState === XMLHttpRequest.DONE) {
                  //const status = request.status;
                  //console.log(status + " aaa " + request.responseText);
                  if(request.responseText == "otp-confirmed"){

                    let url = "{{ route('pass-change-form', ['phone'=> $phone, 'token' => ':token' ]) }}";
                    url = url.replace(':token', input);
                    location.replace(url);
                  } else {
                    alert.classList.add('error');
                    alert.innerHTML = "{{__('form.confirmation-error')}}";
                  }


                
             }
        };

            /*setTimeout(function(){ 
               alert.innerHTML = ""; 
               if(alert.classList.contains('success')){
                       alert.classList.remove('success');
                }else if(alert.classList.contains('error')){
                    alert.classList.remove('error');
                         }
            
                 }, 3000);*/

             request.open('POST', "{{route('confirm-pass-change')}}");
             request.setRequestHeader('X-CSRF-Token', document.head.querySelector('meta[name="csrf-token"]').content);

             request.send(formData); 

            
             
          

        } else {

            document.querySelector(".forgot-pass-card form .alert.alert-otp").innerHTML = "{{__('form.invalid-otp')}}";

        } 
    });
    });
 
</script>


