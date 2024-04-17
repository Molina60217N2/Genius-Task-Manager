<?php

  // file: controllers/LoginController.php
  require_once('models/UserModel.php');
  
  class LoginController extends Controller {

    public function showLoginForm() {
      return view('auth/login',
        ['error'=>false,'login'=>Auth::check()]);
    }

    public function login() {
      $email = Input::get('email');   
      $password = Input::get('password');
      if (Auth::attempt(['email' => $email,
        'password' => $password])) {
        $user = DB::table('users')->where('email', $email)->first();
        $userid = $user[0]['id'];
        Cookie::queue('userId', $userid, 1000);
        Cookie::queue('username', $user[0]['name'], 1000);
        //Aqui debemos redirigir a la vista que viene despues del login, en este caso
        //sería a ver los equipos
        return redirect('/team');
      }
      return redirect('/loginFails');
    }
    
    public function loginFails() {
      return view('auth/login',
        ['error'=>true,'login'=>Auth::check()]);
    }

    public function logout() {
      Auth::logout();
      return redirect('/');
    }
  }
?>