<?php

  // file: controllers/RegisterController.php  
  require_once('models/UserModel.php');
  
  class RegisterController extends Controller {
      
    public function showRegistrationForm() {
      return view('Auth/registration',
        ['login'=>Auth::check()]); 
    }

    public function register($request) {
      $response = [];
      $data = $request;
      $name = $data['name'];
      $email = $data['email']; 
      $password = $data['password'];
      $user = [
        'name'=>$name,'email'=>$email,  
        'password'=>$password];
      try {
        UserModel::create($user);
        $response['code'] = 200;
        $response['message'] = 'Registro exitoso'; 
      } catch (error $error) {
        $response['code'] = 400;
        $response['message'] = 'Registro incorrecto';
        $response['error'] = $error;
      }
      return $response;
    }
  }
?>