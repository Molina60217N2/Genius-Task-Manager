<?php
  // file: controllers/ProfessorController.php

  require_once('./models/Task.php');
  
  class TaskController extends Controller {
    
    public function tasksperteam($teamid) {  
        $tasks = DB::table('task')->where('teamid',$teamid)->get();
        for ($i = 0; $i < sizeof($tasks); $i++) {
          $user = DB::table('users')->where('id', $tasks[$i]['userid'])->get();
          $tasks[$i]['username'] = $user[0]['name'];

          $tag = DB::table('tag')->where('id',$tasks[$i]['tagid'])->get();
          $tasks[$i]['tagname'] = $tag[0]['name'];
          $tasks[$i]['tagcolor'] = $tag[0]['color'];
        }
        $hastasks = false;
        if(sizeof($tasks) > 0){
          $hastasks = true;
        }
      return view(
        'task/index',
        ['tasks'=>$tasks,
        'user' => false,
        'team'=>true,
        'hastasks' => $hastasks,
        'teamid'=>'/'.$teamid, 'login'=>Auth::check()]
      );
    }

    public function show($id) {
      $prof = Professor::find($id);
      return view('professor/show',
        ['professor'=>$prof,
         'title'=>'Professor Detail',
        'show'=>true, 'create'=>false, 'edit'=>false]);
    }

    public function createTask($teamid) {
    $task = ['name'=>'','description'=>'',
               'tagid'=>'','teamid'=>'', 'userid'=>''];
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
    return view('task/show',
      ['title'=>'Creación de Tarea',
      'task'=>$task,
      'teamid'=>$teamid,
      'tags'=>$tags,
      'users' =>$users,
      'show'=>false,'create'=>true,'edit'=>false, 'login'=>Auth::check()]);
  }

  public function store($smth = null) {
    $name = Input::get('name');
    $description = Input::get('description');
    $tagid = Input::get('tagid');
    $teamid = Input::get('teamid');
    $userid = Input::get('userid');
    $task = ['name'=>$name,'description'=>$description,
             'tagid'=>$tagid,'teamid'=>$teamid, 'userid'=>$userid];
    DB::table('task')->insert($task);
    return redirect('/team' . '/' . $teamid);
  }

  //UTILITIES FUNCTIONS

  public function tasksperuser(){
    $userid = Cookie::get('userId');
    $tasks = DB::table('task')->where('userid', $userid)->get();
    for ($i = 0; $i < sizeof($tasks); $i++) {
      $user = DB::table('users')->where('id', $tasks[$i]['userid'])->get();
      $tasks[$i]['username'] = $user[0]['name'];

      $tag = DB::table('tag')->where('id',$tasks[$i]['tagid'])->get();
      $tasks[$i]['tagname'] = $tag[0]['name'];
      $tasks[$i]['tagcolor'] = $tag[0]['color'];
    }
    $hastasks = false;
    if(sizeof($tasks) > 0){
      $hastasks = true;
    }
    // echo('hola');
    // return;
    return view(
      'task/index',
      ['tasks'=>$tasks,
      'user'=>true,
      'team' => false,
      'hastasks' => $hastasks,
      'teamid' => '',
      'login'=>Auth::check()]
    );
  }

  public function edit($prof_id) {
    $prof = DB::table('professor')->find($prof_id);
    return view('professor/show',
      ['professor'=>$prof,
       'title'=>'Professor Edit','courses'=>false,
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

  public function destroy($task_id) {
    $task = DB::table('task')->where('id',$task_id)->first();
    $teamid = $task[0]['teamid'];  
    DB::table('task')->delete($task_id);
    return redirect('/team'.'/'.$teamid);
  }

  }
?>