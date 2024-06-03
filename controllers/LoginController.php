<?php

  // file: controllers/LoginController.php
  require_once('models/UserModel.php');
  
  class LoginController extends Controller {

    public function showLoginForm() {
      return view('auth/login',
        ['error'=>false,'login'=>Auth::check()]);
      // header('Location: /index.html'); 
      return;
    }

    public function login($request) {

          // Obtener el primer elemento del array (la clave JSON)
    $jsonKey = key($request);
    
    
    // Decodificar la clave JSON para obtener los datos del formulario
    $data = $request;
    // var_dump($request);
    // exit();
    // Acceder a los datos2
    //por algun motivo el body de vue me hace molinasalas16@gmail_com
    //se arreglo creo que me faltaron headers en el vue, le deje el accept pero no le habia puesto el content tpe y por eso no servía
    $email = $data['email'];
    // $cadena = trim(stripslashes($data['email']), '"');
    $password = $data['password'];
    
        // var_dump(json_encode($request));
        // exit();
    $response = [];
      // $email = Input::get('email');   
      // $password = Input::get('password');
      if (Auth::attempt(['email' => $email,
        'password' => $password])) {
        $user = DB::table('users')->where('email', $email)->first();
        $userid = $user[0]['id'];
        Cookie::queue('userId', $userid, 1000);
        Cookie::queue('username', $user[0]['name'], 1000);
        //Aqui debemos redirigir a la vista que viene despues del login, en este caso
        //sería a ver los equipos
        $response['code'] = 200;
        $response['message'] = 'Login exitoso';
        $response['data']['userid'] = $userid;
        $response['data']['username'] = $user[0]['name'];
        //En vue: 
        //if(response.code == 200){redirigir al home}
        return $response;
      }
      $response['code'] = 400;
      $response['message'] = 'Login fallido';
      // $prueba = 'Holaaaaa';
      return $response;



      //En vue: 
      //else{volver a intentar el login y decir que las credenciales están mal}
    }
    
    // public function loginFails() {
    //   return view('auth/login',
    //     ['error'=>true,'login'=>Auth::check()]);
    // }

    public function logout() {
      Auth::logout();
      return redirect('/');
    }
  }
?>