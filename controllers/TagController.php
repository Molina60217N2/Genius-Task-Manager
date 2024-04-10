<?php
  // file: controllers/ProfessorController.php

  require_once('./models/Tag.php');
  
  class TagController extends Controller {
    public function tagsperteam($teamid){
      $tags = DB::table('tag')->where('teamid',$teamid)->get();
      $hastags = false;
      if(sizeof($tags) > 0) {
        $hastags = true;
      }
      return view(
        'tag/index',
        ['tags'=>$tags,
        'hastags'=> $hastags,
        'teamid'=>$teamid, 'login'=>Auth::check()]
      );
    }

    public function createTag($teamid) {
      $tag = ['name'=>'','color'=>'',
               'teamid'=>''];
    return view('tag/show',
      ['title'=>'Tag Create',
      'tag'=>$tag,
      'teamid'=>$teamid,
      'show'=>false,'create'=>true,'edit'=>false]);
  }

  public function store($smth = null) {
    $name = Input::get('name');
    $color = Input::get('color');
    $teamid = Input::get('teamid');
    $tag = ['name'=>$name,'color'=>$color,
             'teamid'=>$teamid];
    DB::table('tag')->insert($tag);
    
    return redirect("/team" . "/" . $teamid);
  }

  //FALTAN LOS EDITS
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
             
             
    
  }

  public function destroy($prof_id) {  
    DB::table('professor')->delete($prof_id);
    return redirect('/professor');
  }

  }
?>