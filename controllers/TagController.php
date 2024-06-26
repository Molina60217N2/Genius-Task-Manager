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
      $team = DB::table('team')->where('id',$teamid)->first();
      $teamname = $team[0]['name'];
      $tags = DB::table('tag')->where('teamid',$teamid)->get();
      $tag = ['name'=>'','color'=>'',
               'teamid'=>''];
    return view('tag/show',
      ['title'=>'Tag Create',
      'tag'=>$tag,
      'tags'=>$tags,
      'teamid'=>$teamid,
      'teamname' => $teamname,
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
  public function destroy($prof_id) {  
    DB::table('professor')->delete($prof_id);
    return redirect('/professor');
  }

  }
?>