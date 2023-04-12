<div class="header">
        <div class="header__top">
            <div class="header__top__right"> 

                <ul class="right-content-menu">
                    
                    <li>
                        @guest

                                                        

                            <a  href="{{ route('register') }}" >
                                      <i class="icon plus-icon"></i>  {{ __('form.register') }}
                            </a>

                            <i class="space"></i>

                                    
                            <a  href="{{ route('login') }}" >
                                       <i class="icon account-icon"></i> {{ __('form.sign-in') }}
                            </a>

                        @endguest


                    @auth

                    @php

                    $u_id = Illuminate\Support\Facades\Auth::id();

                    @endphp

                    <span class="dropdown-toggle">

                        @if(asset('storage/user_image/user'. $u_id .'.jpg'))
                    <img class="user-icon" src="{{URL::asset('storage/user_image/user'. $u_id .'.jpg')}}" />

                    @else
                    <img class="user-icon" src="{{URL::asset('storage/user_image/user.jpg')}}" />

                    @endif
                       <!-- <i class="icon account-icon"></i> -->

                        <span class="name">{{ auth()->user()->name }}</span>

                        <i class="icon arrow-down-icon"></i>
                    </span>

                     <ul class="dropdown-account-list">
                            
                                    <li>
                                        <a href="{{ route('myprofile') }}">{{ __('user.myprofile') }}</a>
                                    </li>

                                    <li>
                                        <a href="{{ route('myposts') }}">{{ __('user.myposts') }}</a>
                                    </li>

                                    
                                    <li>
                                        <a href="{{ route('user-session-destroy') }}">{{ __('user.logout') }}</a>
                                    </li>
                            
                        </ul>

 

                    @endauth
                    </li>
                </ul>

            </div>
            <div class="header__top__left">
                <span class="logo-container">
                    <a href="{{ route('home') }}">
                        <img class="logo" src="{{URL::asset('images/logo-1.png')}}" />
                    
                    </a>
                    
                </span>
                
            </div>
            
        </div>

        <div class="header__bottom">

            <!--Hamburger for small screens (mobile) -->
            <span class="icon-box-1" >
                <span class="icon icon-menu"></span>
                <span class="icon icon-menu-close"></span>
            </span>

            <div class = "header__bottom__menu">
                <ul>
                                    <li>
                                        <a href="{{ route('home') }}">{{ __('form.radar') }}</a>
                                    </li>

                                    <li>
                                        <a href="{{ route('create-post') }}">{{ __('form.add-post') }}</a>
                                    </li>

                                    <li>
                                        <a href="{{ route('home') }}">{{ __('form.search') }}</a>
                                    </li>

                                    <li>
                                        <a href="{{ route('home') }}">{{ __('form.contact-us') }}</a>
                                    </li>
                                </ul>
            </div>

            <!--Radar icon for small screens (mobile) -->
            <span class="icon-box-2">
                    <a class="icon icon-radar" href="#">
                        <img src="{{URL::asset('images/logo-1.png')}}" />
                    
                    </a>
                </span>
            

            <!--creating new post (mobile) -->
            <span class="icon-box-3">
                    <a class="icon icon-addpost" href="{{ route('create-post') }}">
                        <img src="{{URL::asset('images/logo-1.png')}}" />
                    
                    </a>
                </span> 


        </div>

        <div class="filter-section">

            <form id = "filter-form" method = "post" action = "{{ route('filter_posts') }}" enctype = "multipart/form-data">
            @csrf
            
            <div class="type">

                <label for="type">{{__('form.type')}}</label>
                <select id="type" name="type">
                <option value="" >{{__('post.select-one')}}</option>
                 <option value="{{__('post.buy')}}">{{__('post.buy')}}</option>
                 <option value="{{__('post.sell')}}">{{__('post.sell')}}</option>
                <option value="{{__('post.inform')}}">{{__('post.inform')}}</option>
                 
             </select>
                
            </div>

            <div class="category">

                <label for="category">{{__('form.category')}}</label>
                <select id="category" name="category">
                    <option value="" >{{__('post.select-one')}}</option>
                 <option value="{{__('post.property')}}">{{__('post.property')}}</option>
                 <option value="{{__('post.machin')}}">{{__('post.machin')}}</option>
                <option value="{{__('post.electronic')}}">{{__('post.electronic')}}</option>
                <option value="{{__('post.clothing')}}">{{__('post.clothing')}}</option>
                <option value="{{__('post.raw')}}">{{__('post.raw')}}</option>
                <option value="{{__('post.food')}}">{{__('post.food')}}</option>
                <option value="{{__('post.service')}}">{{__('post.service')}}</option>
                 
               </select>
                
            </div>

<div class="province">

<label for="province">{{__('form.province')}}</label>

<select id="province" name="province">
                <option value="" >{{__('post.select-one')}}</option>  
                
<option value="{{__('iranplaces.azarbaijane-sharghi')}}">{{__('iranplaces.azarbaijane-sharghi')}}</option>
<option value="{{__('iranplaces.azarbaijane-gharbi')}}">{{__('iranplaces.azarbaijane-gharbi')}}</option>
<option value="{{__('iranplaces.ardebil')}}">{{__('iranplaces.ardebil')}}</option>
<option value="{{__('iranplaces.isfahan')}}">{{__('iranplaces.isfahan')}}</option>
<option value="{{__('iranplaces.alborz')}}">{{__('iranplaces.alborz')}}</option>
<option value="{{__('iranplaces.ilam')}}">{{__('iranplaces.ilam')}}</option>
<option value="{{__('iranplaces.booshehr')}}">{{__('iranplaces.booshehr')}}</option>
<option value="{{__('iranplaces.tehran')}}">{{__('iranplaces.tehran')}}</option>
<option value="{{__('iranplaces.charmahal')}}">{{__('iranplaces.charmahal')}}</option>
<option value="{{__('iranplaces.khorasan-jonobi')}}">{{__('iranplaces.khorasan-jonobi')}}</option>
<option value="{{__('iranplaces.khorasan-razavi')}}">{{__('iranplaces.khorasan-razavi')}}</option>
<option value="{{__('iranplaces.khorasan-shomali')}}">{{__('iranplaces.khorasan-shomali')}}</option>
<option value="{{__('iranplaces.khozestan')}}">{{__('iranplaces.khozestan')}}</option>
<option value="{{__('iranplaces.zanjan')}}">{{__('iranplaces.zanjan')}}</option>
<option value="{{__('iranplaces.semnan')}}">{{__('iranplaces.semnan')}}</option>
<option value="{{__('iranplaces.sistan')}}">{{__('iranplaces.sistan')}}</option>
<option value="{{__('iranplaces.fars')}}">{{__('iranplaces.fars')}}</option>
<option value="{{__('iranplaces.ghazvin')}}">{{__('iranplaces.ghazvin')}}</option>
<option value="{{__('iranplaces.ghom')}}">{{__('iranplaces.ghom')}}</option>
<option value="{{__('iranplaces.kordestan')}}">{{__('iranplaces.kordestan')}}</option>
<option value="{{__('iranplaces.kerman')}}">{{__('iranplaces.kerman')}}</option>
<option value="{{__('iranplaces.kermanshah')}}">{{__('iranplaces.kermanshah')}}</option>
<option value="{{__('iranplaces.kohgiloye')}}">{{__('iranplaces.kohgiloye')}}</option>
<option value="{{__('iranplaces.golestan')}}">{{__('iranplaces.golestan')}}</option>
<option value="{{__('iranplaces.gilan')}}">{{__('iranplaces.gilan')}}</option>
<option value="{{__('iranplaces.lorestan')}}">{{__('iranplaces.lorestan')}}</option>
<option value="{{__('iranplaces.mazandaran')}}">{{__('iranplaces.mazandaran')}}</option>
<option value="{{__('iranplaces.markazi')}}">{{__('iranplaces.markazi')}}</option>
<option value="{{__('iranplaces.hormozgan')}}">{{__('iranplaces.hormozgan')}}</option>
<option value="{{__('iranplaces.hamedan')}}">{{__('iranplaces.hamedan')}}</option>
<option value="{{__('iranplaces.yazd')}}">{{__('iranplaces.yazd')}}</option>
                 
             </select> 
                
            </div>

            <div class="city">

                <label for="city">{{__('form.city')}}</label>
                <input id="city" name= "city" type="text"
                placeholder="{{__('form.enter-city')}}" value="">
                
            </div>

            <div class="price"> 
                <label id="price" for="price">{{__('form.price')}}</label>
                <div class="inputs-container">

                <label for="price">{{__('form.from-price')}}</label>
                <input id="from-price" name="from-price" type="text">
                <label for="price">{{__('form.to-price')}}</label>
                <input id="to-price" name="to-price" type="text">
                </div>
            
                
            </div>

            <div class="distance">
                
            </div>


            <button id="filter-submit" class="submit">
                    {{ __('form.filter') }}
            </button>

        </form>

        </div>


            <div class="search-container">
                    <form id="search-form"  role="search" action="{{ route('search') }}" method="GET" >
                        <input id = "search-input" type="search" name="term" placeholder="{{ __('form.search') }}" required />

                        <i class="icon icon-search" id="search"></i>  
                    </form>
            </div>


        
    </div>


    <script type="text/javascript">




$(document).ready(function(){

    let filterSectionStat = 0;

    document.querySelector(".header__bottom .icon-box-2")
    .addEventListener("click", function(){
        if(filterSectionStat == 0){
        document.querySelector(".header .filter-section").style.display = "block";
        filterSectionStat = 1;    
        }else if(filterSectionStat == 1){
            document.querySelector(".header .filter-section").style.display = "none";
            filterSectionStat = 0;
        }
        

    });

    let khorasanRazaviCities = [
    "{{__('iranplaces.khorasan-razavi-cities.bakharz')}}",
    "{{__('iranplaces.khorasan-razavi-cities.bajestan')}}",
    "{{__('iranplaces.khorasan-razavi-cities.bardaskan')}}",
    "{{__('iranplaces.khorasan-razavi-cities.binalood')}}",
    "{{__('iranplaces.khorasan-razavi-cities.taibad')}}",
    "{{__('iranplaces.khorasan-razavi-cities.torbatejam')}}",
    "{{__('iranplaces.khorasan-razavi-cities.torbatehaidarie')}}",
    "{{__('iranplaces.khorasan-razavi-cities.joghatay')}}",
    "{{__('iranplaces.khorasan-razavi-cities.jovain')}}",
    "{{__('iranplaces.khorasan-razavi-cities.chenaran')}}",
    "{{__('iranplaces.khorasan-razavi-cities.khalilabad')}}",
    "{{__('iranplaces.khorasan-razavi-cities.khaf')}}",
    "{{__('iranplaces.khorasan-razavi-cities.khooshab')}}",
    "{{__('iranplaces.khorasan-razavi-cities.davarzan')}}",
    "{{__('iranplaces.khorasan-razavi-cities.dargaz')}}",
    "{{__('iranplaces.khorasan-razavi-cities.rashtkhar')}}",
    "{{__('iranplaces.khorasan-razavi-cities.zaveh')}}",
    "{{__('iranplaces.khorasan-razavi-cities.zabarkhan')}}",
    "{{__('iranplaces.khorasan-razavi-cities.sabzevar')}}",
    "{{__('iranplaces.khorasan-razavi-cities.sarakhs')}}",
    "{{__('iranplaces.khorasan-razavi-cities.sheshtamad')}}",
    "{{__('iranplaces.khorasan-razavi-cities.salehabad')}}",
    "{{__('iranplaces.khorasan-razavi-cities.fariman')}}",
    "{{__('iranplaces.khorasan-razavi-cities.firoozeh')}}",
    "{{__('iranplaces.khorasan-razavi-cities.ghoochan')}}",
    "{{__('iranplaces.khorasan-razavi-cities.kashmar')}}",
    "{{__('iranplaces.khorasan-razavi-cities.kalat')}}",
    "{{__('iranplaces.khorasan-razavi-cities.koohsorkh')}}",
    "{{__('iranplaces.khorasan-razavi-cities.golbahar')}}",
    "{{__('iranplaces.khorasan-razavi-cities.gonabad')}}",
    "{{__('iranplaces.khorasan-razavi-cities.mashhad')}}",
    "{{__('iranplaces.khorasan-razavi-cities.mahvelat')}}",
    "{{__('iranplaces.khorasan-razavi-cities.nishaboor')}}",
    ];



    document.querySelector(".header .filter-section #province").onchange = function(){

        let citySelect = document.querySelector(".header .filter-section #city");

        let val = this.value;
        console.log("The prov: " + val);
        if(val == "{{__('iranplaces.khorasan-razavi')}}"){

            for(let i = 0; i < khorasanRazaviCities.length; i++){
                let opt = document.createElement('option');
                opt.value = khorasanRazaviCities[i];
                opt.innerHTML = khorasanRazaviCities[i];
                citySelect.appendChild(opt);

            }


        }
}


    /* $(".header .filter-section #province").change(function(){
        let val = $(this).val();
        if(val == "{{__('iranplaces.khorasan-razavi')}}"){
           $(".header .filter-section #city")
           .html("<option value ='{{__('iranplaces.gonabad')}}'>{{__('iranplaces.gonabad')}}</option><option value ='{{__('iranplaces.torbate-haydarie')}}'>{{__('iranplaces.torbate-haydarie')}}</option>");

        }
    }); */

  
  let searchIcon = document.getElementById("search");
  searchIcon.addEventListener("click", function(){
     document.getElementById("search-form").submit();
  });

  var input = document.getElementById("search-input");

// Execute a function when the user presses a key on the keyboard
input.addEventListener("keypress", function(event) {
  // If the user presses the "Enter" key on the keyboard
  if (event.key === "Enter") {
    
    document.getElementById("search-form").submit();
    
  }
});

//Hammenu for mobile.
var menuStat = 0;

var hammenu = document.querySelector('.header__bottom .icon-box-1 .icon-menu');
hammenu.addEventListener("click", function(){
//this.style.backgroundImage = "url('../images/icon-menu-close.svg')";
this.style.display = "none";
let mClose = document.querySelector('.header__bottom .icon-box-1 .icon-menu-close');
mClose.style.display = "inline-block";
 let menu = document.querySelector('.header__bottom__menu');
 menu.style.display = "block";
 menuStat = 1;
});


var menuClose = document.querySelector('.header__bottom .icon-box-1 .icon-menu-close');
menuClose.addEventListener("click", function(){
//this.style.backgroundImage = "url('../images/icon-menu-close.svg')";
this.style.display = "none";
let hmenu = document.querySelector('.header__bottom .icon-box-1 .icon-menu');
hmenu.style.display = "inline-block";
 let menu = document.querySelector('.header__bottom__menu');
 menu.style.display = "none";
 menuStat = 0;
});


// Close the menu if user clicks every element in page except the menu itself.
/*window.addEventListener('click', function(event){
    var box = document.querySelector('.header__bottom .header__bottom__menu');
    if (event.target != box && event.target.parentNode != box){
        box.style.display = 'none';
        let mClose = document.querySelector('.header__bottom .icon-box-1 .icon-menu-close');
        mClose.style.display = "none";
        let hmenu = document.querySelector('.header__bottom .icon-box-1 .icon-menu');
        hmenu.style.display = "inline-block";
        
        menuStat = 0;
    }
});*/
/*var menuBox = document.querySelector('.header__bottom .header__bottom__menu');
// Listen for click events on body
document.body.addEventListener('click', function (event) {
    if (menuBox.contains(event.target)) {
        //menu clicked - we should handle menu clicks here
        alert("Yesss");
        //console.log('ggg');

    } else {
        if(menuStat){
            //closing the menu
            menuClose.style.display = "none";
            let hmenu = document.querySelector('.header__bottom .icon-box-1 .icon-menu');
            hmenu.style.display = "inline-block";
            let menu = document.querySelector('.header__bottom__menu');
            menu.style.display = "none";
            menuStat = 0;
        } else {
            //Normal click continue
        }
    }
});*/




var allElements = document.querySelector('.container');
allElements.addEventListener("click", function(){

});


var dropToggleStat = 0;
var dropToggle = document.querySelector(".header .header__top .header__top__right .right-content-menu span.dropdown-toggle");

if(dropToggle){
    dropToggle.addEventListener("click", function(){
    var dropAccountList = document.querySelector(".header .header__top .header__top__right .right-content-menu .dropdown-account-list");
    if(dropToggleStat == 0){
        dropAccountList.style.display = "block";
        dropToggleStat = 1;

    }else{
        dropAccountList.style.display = "none";
        dropToggleStat = 0;

    }

    });
}











});








    </script>
