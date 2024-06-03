<?php
  // file: controllers/ProfessorController.php

  require_once('./models/Team.php');
  require_once('./models/UserModel.php');
  
  class TeamController extends Controller {
    
    //new index
    public function teamsperuser($userid) {  
      //la funcion de Auth::check() devuelve true o false
      $response = [];
      // Cookie::forget('userId');
      // $userid = Cookie::get('userId');
      $teamsids = DB::table('userteamrel')->where('userid',$userid)->get();
      
      // echo(sizeof($teamsids));
      // return;
      //equipos que administra
      $teamsadmin = DB::table('team')->where('useradminid', $userid)->get();
      //equipos a los que pertenece
      $teamsb = [];
      for ($i = 0; $i < sizeof($teamsids); $i++)  {
        $team = DB::table('team')->where('id', $teamsids[$i]['teamid'])->first();
        // Si se encontró un equipo con ese ID, añadirlo al array de equipos
        $teamsb[$i] = $team[0];
      }
      // $hasteamsadmin = false;
      // $hasteamsb = false;
      // if(sizeof($teamsadmin) > 0) {
      //   $hasteamsadmin = true;
      // }
      // if(sizeof($teamsb) > 0) {
      //   $hasteamsb = true;
      // }
      // return view('team/index',
      //  ['hasteamsadmin' => $hasteamsadmin,
      //   'hasteamsb' => $hasteamsb,
      //   'teamsb'=> $teamsb,
      //  'teamsadmin'=>$teamsadmin,
      //  'username'=>Cookie::get('username'),
      //  'title'=>'Equipos', 'login'=>Auth::check()]);

       $response['code'] = 200;
       $response['message'] = 'Query exitosa';
      //  $response['login'] = Auth::check();
       $response['data']['teamsadmin'] = $teamsadmin;
       $response['data']['teamsb'] = $teamsb;
      //  $response['data']['username'] = Cookie::get('username');
       $response['data']['userid'] = $userid;
      //  var_dump($response);
      //  exit();
       return $response;

      // return Team::all();
      
    }


    //new shows
    public function showTeam($id, $userid) {
      //Debemos buscar los usuarios relacionados al equipo y pasar los nombres
      //Debemos buscar al usuario administrador del equipo y pasar su nombre
      $team = Team::find($id);
      $user = DB::table('users')->where('id', $userid)->first();
      //Tener cuidado con mandar json a peticiones get porque se friega xd
      //Usuarios relacionados
      $users = [];
      //recorrer todos los ids y llamar a cada usuario, 
      //almacenar a los usuarios en $users para luego poderlos mostrar
      $usersids = DB::table('userteamrel')->where('teamid', $id)->get();
      for ($i = 0; $i < sizeof($usersids); $i++) {
        $user = DB::table('users')->where('id', $usersids[$i]['userid'])->first();
        $users[$i]['username'] = $user[0]['name'];
        $users[$i]['id'] = $user[0]['id'];
    }
    // var_dump($users);
    // return;
    $admin = DB::table('users')->where('id', $team[0]['useradminid'])->first();
    $users[sizeof($usersids) + 1]['username'] = $admin[0]['name'];
    $users[sizeof($usersids) + 1]['id'] = $admin[0]['id'];
    $adminname = $admin[0]['name'];
    $isadmin = false;
    if($admin[0]['id'] == $userid) {
      $isadmin = true;
    }
    $tasks = DB::table('task')->where('teamid', $id)->get();
    for($i = 0; $i < sizeof($tasks); $i++){
      $usert = DB::table('users')->where('id',$tasks[$i]['userid'])->first();
      $tagt = DB::table('tag')->where('id', $tasks[$i]['tagid'])->first();
      $tasks[$i]['username'] = $usert[0]['name'];
      $tasks[$i]['tagname'] = $tagt[0]['name'];
      $tasks[$i]['tagcolor'] = $tagt[0]['color'];
      $tasks[$i]['taskname'] = $tasks[$i]['name'];
      $tasks[$i]['taskdescription'] = $tasks[$i]['description'];
      $tasks[$i]['taskid'] = $tasks[$i]['id'];
    }
    $hastasks = false;
    if(sizeof($tasks) > 0){
      $hastasks = true;
    }
    // echo($tasks[0]['username']);
    // return;

    $response = [];
    $response['code'] = 200;
    $response['message'] = 'Query exitosa';
    //nombre del usuario actual
    $response['data']['username'] = $user[0]['name'];
    $response['data']['team'] = $team;
    $response['data']['users'] = $users;
    $response['data']['tasks'] = $tasks;
    //sabes si el usuario actual es administrador del equipo
    $response['data']['isadmin'] = $isadmin;
    //nombre del administrador del equipo, independientemente de si es el usuario logueado
    $response['data']['admin'] = $adminname;
    return $response;
    }


  //   public function create() {
  //   $team = ['name'=>'','description'=>''];
  //   return view('team/show',
  //     ['title'=>'Creación de equipo',
  //     'team'=>$team,
  //     'show'=>false,'create'=>true,'edit'=>false, 'login'=>Auth::check()]);
  // }

  public function addTeam($request) {
    $data = $request;
    $response = [];
    $name = $data['name'];
    $description = $data['description'];
    $admin = $data['userid'];
    $team = ['name'=>$name,'description'=>$description,
             'useradminid'=>$admin];
    try{
      DB::table('team')->insert($team);
      $response['code'] = '200';
      $response['message'] = 'Ingreso de equipo exitoso';
    } catch(error $error) {
      $response['code'] = '400';
      $response['message'] = 'Ingreso de equipo incorrecto';
      $response['error'] = $error;
    }

    // return redirect('/team');
    return $response;
  }

  //FUNCIONES PARA AGREGAR USUARIOS A EQUIPO
  //Esta función nos va a permitir retornar a los usuarios, la otra nos va a permitir agregarlos al equipo
  public function getusers(){
    $response = [];
    try{
      $users = UserModel::all();
      // $team = DB::table('team')->where('id',$teamid)->first();
      $response['code'] = '200';
      $response['message'] = 'Query exitosa';
      $response['data']['users'] = $users;
      // var_dump($response);
      // exit();
      // $response['data']['team'] = $team;
    } catch (error $error) {
      $response['code'] = '400';
      // $response['message'] = 'Datos incorrectos';
    }
    // return view('/team/adduser',
    // [
    //   'teamid'=>$teamid,
    //   'teamname'=>$teamname,
    //   'users'=>$users,
    //   'login'=>Auth::check(),
    // ]);
    return $response;
  }

  public function storeuser($request){
    $data = $request;
    $response = [];
    // var_dump($request);
    // exit();
    //Verificar que los campos no vengan vacíos
    $user = $data['userid'];
    $teamid = $data['teamid'];
    $rel = ['userid'=>$user,'teamid'=>$teamid];
    try {
      DB::table('userteamrel')->insert($rel);
      $response['code'] = '200';
      $response['message'] = 'Query exitosa';
    } catch(error $error) {
      $response['code'] = '400';
      $response['message'] = 'Datos inválidos';
      $response['error'] = $error;
    }
    return $response;
  }

  //VISTAS Y FUNCIONALIDADES DE EDICION
  // public function edit($team_id) {
  //   $team = DB::table('team')->find($team_id);
  //   return view('team/show',
  //     ['team'=>$team,
  //      'title'=>'Editar Información de Equipo',
  //      'login'=>Auth::check(),
  //      'show'=>false,'create'=>false,'edit'=>true]);
  // }

  public function updateTeam($request) {
    $response = [];
    $data = $request;
    $name = $data['name'];
    $description = $data['description'];
    $team_id = $data['teamid'];
    $team = ['name'=>$name,'description'=>$description];

    try {
      DB::table('team')->update($team_id,$team);
      $response['code'] = '200';
      $response['message'] = 'Query ejecutada de manera exitosa';
    } catch(error $error) {
      $response['code'] = '400';
      $response['message'] = 'Datos invalidos';
      $response['error'] = $error;
    }
    
    return $response;         

  }

  public function deleteteam($request) {
    $reponse = [];
    $team_id = $request['teamid'];
    $userid = $request['userid'];
    $team = DB::table('team')->where('id', $team_id)->get();
    // var_dump($userid);
    // var_dump($team);
    // return;
    if($team[0]['useradminid'] == $userid) {
        DB::table('team')->delete($team_id);
        $rels = DB::table('userteamrel')->where('teamid',$team_id)->get();
        for($i = 0; $i < sizeof($rels); $i++) {
          DB::table('userteamrel')->delete($rels[$i]['id']);
        }
        $tasks = DB::table('task')->where('teamid',$team_id)->get();
        for($i = 0; $i < sizeof($tasks); $i++) {
          DB::table('task')->delete($tasks[$i]['id']);
        }
        $tags = DB::table('tag')->where('teamid',$team_id)->get();
        for($i = 0; $i < sizeof($tags); $i++) {
          DB::table('tag')->delete($tags[$i]['id']);
        }
        $response['code'] = '200';
        $response['message'] = 'Equipo eliminado exitosamente';
    } else {
      $response['code'] = '400';
      $response['message'] = 'No tiene los permisos para hacer esto';
    }
    return $response;
  }

  //Las cookies quedan funcionando y se pueden ver y usar aun usando el api
  //***********************Funciones nuevas para el RestAPI*****************/

  }

?>