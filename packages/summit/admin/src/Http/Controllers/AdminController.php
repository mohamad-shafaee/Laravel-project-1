<?php

namespace Summit\Admin\Http\Controllers;


class AdminController extends Controller
{


     /**
     * Contains route related configuration
     *
     * @var array
     */
     //protected $_config;

     /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       // $this->middleware('admin')->except(['create','store']);

        //$this->_config = request('_config');
        

        //$this->middleware('guest', ['except' => 'destroy']);
    }

    public function createLogin(){

        //I added withError() and withErrors() functions and worked surprisingly! 
        //The problem was that the web middleware which creates a $errors variable did not work.
        //I fixed the problem for we middleware and all things were nice. 
        //return view($this->_config['view'])->withErrors(null);
        return view('admin::auth.login');//->withErrors(null);
    }

    public function store(){

        //updating the session.
    }


}