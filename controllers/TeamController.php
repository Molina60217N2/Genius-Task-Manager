<?php
  // file: controllers/ProfessorController.php

  require_once('./models/Team.php');
  require_once('./models/UserModel.php');
  
  class TeamController extends Controller {

    //HHACER UNA FUNCION QUE PERMITA AGREGAR USUARIOS AL EQUIPO
    
    public function index() {  
      //la funcion de Auth::check() devuelve true o false
      $userid = Cookie::get('userId');
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
      $hasteamsadmin = false;
      $hasteamsb = false;
      if(sizeof($teamsadmin) > 0) {
        $hasteamsadmin = true;
      }
      if(sizeof($teamsb) > 0) {
        $hasteamsb = true;
      }
      return view('team/index',
       ['hasteamsadmin' => $hasteamsadmin,
        'hasteamsb' => $hasteamsb,
        'teamsb'=> $teamsb,
       'teamsadmin'=>$teamsadmin,
       'username'=>Cookie::get('username'),
       'title'=>'Equipos', 'login'=>Auth::check()]);
    }

    public function show($id) {
      //Debemos buscar los usuarios relacionados al equipo y pasar los nombres
      //Debemos buscar al usuario administrador del equipo y pasar su nombre
      $team = Team::find($id);
      //Usuarios relacionados
      $users = [];
      //recorrer todos los ids y llamar a cada usuario, 
      //almacenar a los usuarios en $users para luego poderlos mostrar
      $usersids = DB::table('userteamrel')->where('teamid', $id)->get();
      for ($i = 0; $i < sizeof($usersids); $i++) {
        $user = DB::table('users')->where('id', $usersids[$i]['userid'])->first();
        $users[$i]['username'] = $user[0]['name'];
    }
    $admin = DB::table('users')->where('id', $team[0]['useradminid'])->first();
    $users[sizeof($usersids)]['username'] = $admin[0]['name'];
    $adminname = $admin[0]['name'];
    $isadmin = false;
    if($admin[0]['id'] == Cookie::get('userId')) {
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
      return view('team/show',
        ['team'=>$team,
         'title'=>$team[0]['name'],
         'users' => $users,
         'tasks' => $tasks,
         'hastasks' => $hastasks,
         'admin'=>$adminname,
         'show'=>true, 'create'=>false, 'edit'=>false, 'isadmin'=>$isadmin, 'login'=>Auth::check()]);
    }

    public function create() {
    $team = ['name'=>'','description'=>''];
    return view('team/show',
      ['title'=>'Creación de equipo',
      'team'=>$team,
      'show'=>false,'create'=>true,'edit'=>false, 'login'=>Auth::check()]);
  }

  public function store($smth = null) {
    $name = Input::get('name');
    $description = Input::get('description');
    $admin = Cookie::get('userId');
    $team = ['name'=>$name,'description'=>$description,
             'useradminid'=>$admin];
    DB::table('team')->insert($team);

    return redirect('/team');
  }

  //FUNCIONES PARA AGREGAR USUARIOS A EQUIPO
  public function adduser($teamid){
    $users = UserModel::all();
    $team = DB::table('team')->where('id',$teamid)->first();
    $teamname = $team[0]['name'];
    return view('/team/adduser',
    [
      'teamid'=>$teamid,
      'teamname'=>$teamname,
      'users'=>$users,
      'login'=>Auth::check(),
    ]);
  }

  public function storeuser($smth = null){
    $user = Input::get('user');
    $teamid = Input::get('teamid');
    // echo($user);
    // return;
    $rel = ['userid'=>$user,'teamid'=>$teamid];
    DB::table('userteamrel')->insert($rel);
    return redirect("/team".'/'.$teamid);
  }

  //VISTAS Y FUNCIONALIDADES DE EDICION PENDIENTES
  public function edit($team_id) {
    $team = DB::table('team')->find($team_id);
    return view('team/show',
      ['team'=>$team,
       'title'=>'Editar Información de Equipo',
       'login'=>Auth::check(),
       'show'=>false,'create'=>false,'edit'=>true]);
  }

  public function update($_,$prof_id = null) {
    $name = Input::get('name');
    $degree = Input::get('degree');
    $email = Input::get('email');
    $phone = Input::get('phone');
    $prof = ['name'=>$name,'degree'=>$degree,
             'email'=>$email,'phone'=>$phone];
             DB::table('professor')->update($prof_id,$prof);
             echo($prof_id);
             return;
             return redirect('/professor');
             Professor::update($prof_id, $prof);
             
    
  }

  public function destroy($team_id) {
    $userid = Cookie::get('userId');
    $team = DB::table('team')->where('useradminid', $userid)->get();
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
    }
    return redirect('/team');
  }

  //TESTING FUNCTIONS

  }
?>