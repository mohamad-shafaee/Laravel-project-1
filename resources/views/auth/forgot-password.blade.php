<x-guest-layout>
    <div class="forgot-pass-card">
      <div class="logo-box">
        <a href="/">
                <x-application-logo class="logo" />
        </a>
      </div>

        <div class="forgot-pass-args">
            {{ __('form.forgot-password-arguments') }}
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

        <form id="phone-email" method="POST" action="#">
            @csrf

            <!-- Phone or Email -->
            <div class = "box phone-email-box">

                <input id="phone-or-email" class="phone-email" name = "pe" type="text" required />
                <label id= "phone-or-email-label" for="phone-email" >{{__('form.phone-or-email')}}</label>

            </div>

            <div class = "alert alert-pe"></div>


            <button class="submit">
                    {{ __('form.submit') }}
            </button>

        </form>
    </div>
</x-guest-layout>



<script type="text/javascript">

document.addEventListener("DOMContentLoaded", function(){
 
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

    let btnSubmit = document.querySelector('form button.submit');

    btnSubmit.addEventListener('click', function(event){
        event.preventDefault();

        let inputpe = document.querySelector('form input#phone-or-email'); 

        let peValidated = dataValidType(inputpe.value);
 
        if(peValidated == "phone" || peValidated == "email"){

            if(peValidated == "phone") {

                inputpe.name = 'phone';

                let form1 = document.querySelector("form#phone-email");
                form1.action = "{{route('change-pass-by-phone')}}";
                form1.submit();
             
          /*$(this).closest('form')
            .attr('action', "{{route('change-pass-by-phone')}}")
            .submit();*/
 
            } else if (peValidated == "email"){

                   inputpe.name = 'email';
             
          

        } 

    } else if (peValidated == "invalid phone") {

            document.querySelector(".forgot-pass-card form .alert.alert-pe").innerHTML = "{{__('form.invalid-phone')}}";

        } else if (peValidated == "invalid email"){

            document.querySelector(".forgot-pass-card form .alert.alert-pe").innerHTML = "{{__('form.invalid-email')}}";
            
        }
    }); 
    });
 
</script>


