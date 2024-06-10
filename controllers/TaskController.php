<?php
  // file: controllers/ProfessorController.php

  require_once('./models/Task.php');
  
  class TaskController extends Controller {
    
    public function tasksperteam($teamid) {  
      $response = [];
      try{
        $tasks = DB::table('task')->where('teamid',$teamid)->get();
        for ($i = 0; $i < sizeof($tasks); $i++) {
          $user = DB::table('users')->where('id', $tasks[$i]['userid'])->get();
          $tasks[$i]['username'] = $user[0]['name'];

          $tag = DB::table('tag')->where('id',$tasks[$i]['tagid'])->get();
          $tasks[$i]['tagname'] = $tag[0]['name'];
          $tasks[$i]['tagcolor'] = $tag[0]['color'];
        }
        $response['code'] = '200';
        $response['message'] = 'Query completada exitosamente';
        $response['data']['tasks'] = $tasks;
      } catch(error $error) {
        $response['code'] = '400';
        $response['message'] = $error;
      }
      return $response;
    }

    public function createTask($teamid) {
    $response = [];
    try {
      $tags = DB::table('tag')->where('teamid', $teamid)->get();
      $usersids = DB::table('userteamrel')->where('teamid', $teamid)->get();
      $users = [];
      for ($i = 0; $i < sizeof($usersids); $i++) {
          $user = DB::table('users')->where('id', $usersids[$i]['userid'])->first();
          $users[$i] = $user[0];
          //$users[$i]['username'] = $user[0]['name'];
      }
      $own = DB::table('users')-> where('id', Cookie::get('userId'))->first();
      $users[sizeof($usersids)] = $own[0];
      $response['code'] = '200';
      $response['message'] = 'Query ejecutada exitosamente';
      $response['data']['users'] = $users;
      $response['data']['tags'] = $tags;
    } catch(error $error) {
      $response['code'] = '400';
      $response['message'] = $error;
    }
    return $response;
  }

  public function addTask($request) {
    $response = [];
    $data = $request;
    // var_dump($request);
    // exit();
    $name = $data['name'];
    $description = $data['description'];
    $tagid = $data['tagid'];
    $teamid = $data['teamid'];
    $userid = $data['userid'];
    $task = ['name'=>$name,'description'=>$description,
             'tagid'=>$tagid,'teamid'=>$teamid, 'userid'=>$userid];
    try {
      DB::table('task')->insert($task);
      $response['code'] = '200';
      $response['message'] = 'Query ejecutada correctamente';
    } catch(error $error) {
      $response['code'] = '400';
      $response['message'] = $error;
    }
             return $response;
  }

  //UTILITIES FUNCTIONS

  public function tasksperuser($user_id){
    $response = [];
    $userid = $user_id;
   try {
    $userS = DB::table('users')->where('id', $userid)->first();
    $tasks = DB::table('task')->where('userid', $userid)->get();
    $rel = DB::table('userteamrel')->where('userid',$userid)->get();
    $teams = [];
    for($i = 0; $i < sizeof($rel); $i++){
      $team = DB::table('team')->where('id',$rel[$i]['teamid'])->first();
      $teams[] = $team[0];
    }
    for ($i = 0; $i < sizeof($tasks); $i++) {
      $user = DB::table('users')->where('id', $tasks[$i]['userid'])->first();
      $username = $user[0]['name'];
      $tag = DB::table('tag')->where('id',$tasks[$i]['tagid'])->get();
      $tasks[$i]['tagname'] = $tag[0]['name'];
      $tasks[$i]['tagcolor'] = $tag[0]['color'];
      $team = DB::table('team')->where('id', $tasks[$i]['teamid'])->first();
      $tasks[$i]['teamname'] = $team[0]['name'];
      $tasks[$i]['username'] = $username;
    }
    $adminteams = DB::table('team')->where('useradminid',$userid)->get();
    for($i = 0; $i < sizeof($adminteams); $i++){
      $teams[] = $adminteams[$i];
    }
    $response['code'] = '200';
    $response['data']['tasks'] = $tasks;
    //retorna los equipos para hacer el selector del filtrado
    $response['data']['teams'] = $teams;
    $response['data']['username'] = $userS[0]['name'];
    $response['message'] = 'Query ejectuada correctamente';
   } catch(error $error) {
    $response['code'] = '400';
    $response['message'] = $error;
   }
    return $response;
  }

  public function filter($request){
    $response = [];
    $data = json_decode($request['json'], true);
    $teamid = $data['teamid'];
    $userid = Cookie::get('userId');
    try{
      $user = DB::table('users')->where('id',$userid)->first();
      $username = $user[0]['name'];
      $tasks = DB::table('task')->where('teamid', $teamid)->get();
      $rel = DB::table('userteamrel')->where('userid',$userid)->get();
      $teams = [];
      for($i = 0; $i < sizeof($rel); $i++){
        $team = DB::table('team')->where('id',$rel[$i]['teamid'])->first();
        $teams[] = $team[0];
      }
      $adminteams = DB::table('team')->where('useradminid',$userid)->get();
      for($i = 0; $i < sizeof($adminteams); $i++){
        $teams[] = $adminteams[$i];
      }
      $usertasks = [];
      for($i = 0; $i < sizeof($tasks); $i++) {
        if($tasks[$i]['userid'] == $userid){
          $usertasks[] = $tasks[$i];
        }
      }
      for($i = 0; $i < sizeof($usertasks); $i++) {
        $tag = DB::table('tag')->where('id',$usertasks[$i]['tagid'])->get();
        $usertasks[$i]['tagname'] = $tag[0]['name'];
        $usertasks[$i]['tagcolor'] = $tag[0]['color'];
        $team = DB::table('team')->where('id', $usertasks[$i]['teamid'])->first();
        $usertasks[$i]['teamname'] = $team[0]['name'];
      }
      $response['code'] = '200';
      $response['message'] = 'Query ejecutada exitosamente';
      $response['data']['tasks'] = $usertasks;
      $response['data']['teams'] = $teams;
      $response['data']['filtername'] = $usertasks[0]['teamname'];
      $response['data']['username'] = $username;
    } catch(error $error) {
      $response['code'] = '400';
      $response['message'] = $error;
    }
    // echo($teamid);
    // return;
    return $response;

  }
  // public function edit($task_id) {
  //   $task = DB::table('task')->find($task_id);
  //   $tags = DB::table('tag')->where('teamid', $task[0]['teamid'])->get();
  //   for($i = 0; $i < sizeof($tags); $i++){
  //     $tags[$i]['tagname'] = $tags[$i]['name'];
  //     $tags[$i]['tagid'] = $tags[$i]['id'];
  //   }
  //   $usersids = DB::table('userteamrel')->where('teamid', $task[0]['teamid'])->get();
  //   $users = [];
  //   for ($i = 0; $i < sizeof($usersids); $i++) {
  //     $user = DB::table('users')->where('id', $usersids[$i]['userid'])->first();
  //     $users[$i] = $user[0];
  //     $users[$i]['username'] = $user[0]['name'];
  //     $users[$i]['userid'] = $user[0]['id'];
  //   }
  //   $own = DB::table('users')-> where('id', Cookie::get('userId'))->first();
  //   $users[sizeof($usersids)] = $own[0];
  //   return view('task/show',
  //     [
  //     'title'=>'Edición de Tarea',
  //     'tasku'=>$task,
  //     'teamid'=>$task[0]['teamid'],
  //     'tagsu'=>$tags,
  //     'usersu' =>$users,
  //     'login'=>Auth::check(),
  //     'show'=>false,'create'=>false,'edit'=>true]);
  // }

  
  public function updateTask($request) {
    $response = [];
    $data = $request;
    // var_dump($request);
    // exit();
    $name = $data['name'];
    $description = $data['description'];
    $tag_id = $data['tagid'];
    $user_id = $data['userid'];
    $team_id = $data['teamid'];
    $task_id = $data['taskid'];
    $task = ['name'=>$name,'description'=>$description,
             'tagid'=>$tag_id,'userid'=>$user_id, 'teamid'=>$team_id];
    try {
      DB::table('task')->update($task_id,$task);
      $response['code'] = '200';
      $response['message'] = 'Query ejecutada exitosamente';
    } catch(error $error) {
      $response['code'] = '400';
      $response['message'] = $error;
    }
    
    return $response;
  }

  public function destroy($request) {
    $response = [];
    $data = $request;
    $task_id = $data['taskid'];
    try {
    $task = DB::table('task')->where('id',$task_id)->first();
    $teamid = $task[0]['teamid'];  
    DB::table('task')->delete($task_id);
    $response['code'] = '200';
    $response['message'] = 'Query ejecutada exitosamente';
    $response['data']['teamid'] = $teamid;
    } catch(error $error) {
      $response['code'] = '400';
      $response['message'] = $error;
    }
    return $response;
  }

  }
?>