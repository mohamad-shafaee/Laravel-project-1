<x-guest-layout>

    <div class="pass-change-card">
      <div class="logo-box">
        <a href="/">
                <x-application-logo class="logo" />
        </a>
      </div>

      <div class = "title">{{__('form.pass-change')}}</div> 
      <div class = "pass-change-args">{{__('form.enter-new-password-and-confirm')}}</div>

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

            <div class = "alert alert-pass-change"></div>

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

            <button class="submit">
                    {{ __('form.pass-change') }}
            </button>

           

        </form>

         <button class="close" style="display: none">
                    {{ __('form.close') }}
            </button>
    </div>

</x-guest-layout> 

<script type="text/javascript">

    document.addEventListener("DOMContentLoaded", function(){

      let alert = document.querySelector("form .alert.alert-pass-change");

    let btnSubmit = document.querySelector('form button.submit');

    btnSubmit.addEventListener('click', function(event){
        event.preventDefault();

        let formData = new FormData();
        let input = document.querySelector('form input#password').value;
        let input_confirm = document.querySelector('form input#password_confirmation').value;
           formData.append("password", input);
           formData.append("password_confirmation", input_confirm);
           formData.append("phone", "{{$phone}}");
           formData.append("token", "{{$token}}");

           let request  = new XMLHttpRequest();
          
           request.onreadystatechange = function() {
                if (request.readyState === XMLHttpRequest.DONE) {
                  const status = request.status;
                  console.log(" Yes " + "{{$phone}}" + "   " + "{{$token}}" + request.responseText);
                if (request.responseText == "pass-changed") {
                 // The request has been completed successfully 
                 alert.classList.add('success');
                 alert.innerHTML = "{{__('form.password-changed')}}";
                 let passBoxs = document.querySelectorAll('form .box.password-box');
                 passBoxs.forEach(e=>{
                    e.style.display = 'none';
                 });
                 
                 document.querySelector('form button.submit').style.display = 'none'; 
                 document.querySelector('button.close').style.display = 'block';
                 document.querySelector('button.close').addEventListener("click", function(){
                    
                    
                    window.location.replace("{{route('home')}}");
                 });


                 
                  } else {
                  // Oh no! There has been an error with the request!
                  alert.classList.add('error');
                 alert.innerHTML = "{{__('form.password-change-error')}}";

                 document.querySelector('button.close').style.display = 'block';
                 document.querySelector('button.close').addEventListener("click", function(){
                    
                    window.location.replace("{{route('home')}}");
                 });
                  
               }
             }
        }; 

             request.open('POST', "{{route('change-pass')}}");
             request.setRequestHeader('X-CSRF-Token', document.head.querySelector('meta[name="csrf-token"]').content);

             request.send(formData);  
    });  
    }); 

</script>



