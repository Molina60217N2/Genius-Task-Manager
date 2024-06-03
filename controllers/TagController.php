<?php
  // file: controllers/ProfessorController.php

  require_once('./models/Tag.php');
  
  class TagController extends Controller {
    public function tagsperteam($teamid){
      $response = [];
      try {
        $tags = DB::table('tag')->where('teamid',$teamid)->get();
        // var_dump($tags);
        // exit();
        $response['code'] = '200';
        $response['message'] = 'Query ejecutada exitosamente';
        $response['data']['tags'] = $tags;
      } catch(error $error) {
        $response['code'] = '400';
        $response['message'] = $error;
      }
      
      return $response;
    }

  //   public function createTag($teamid) {
  //     $team = DB::table('team')->where('id',$teamid)->first();
  //     $teamname = $team[0]['name'];
  //     $tags = DB::table('tag')->where('teamid',$teamid)->get();
  //     $tag = ['name'=>'','color'=>'',
  //              'teamid'=>''];
  //   return view('tag/show',
  //     ['title'=>'Tag Create',
  //     'tag'=>$tag,
  //     'tags'=>$tags,
  //     'teamid'=>$teamid,
  //     'teamname' => $teamname,
  //     'show'=>false,'create'=>true,'edit'=>false]);
  // }

  public function addTag($request) {
    $response = [];
    // var_dump($request);
    // exit();
    $data = $request;
    $name = $data['name'];
    $color = $data['color'];
    $teamid = $data['teamid'];
    $tag = ['name'=>$name,'color'=>$color,
             'teamid'=>$teamid];
    try {
      DB::table('tag')->insert($tag);
      $response['code'] = '200';
      $response['message'] = 'Query ejecutada exitosamente';
    } catch(error $error) {
      $response['code'] = '400';
      $response['message'] = $error;
    }   
    return $response;
  }
  public function destroy($tag_id) {  
    DB::table('tag')->delete($tag_id);
    $response = [];
    $response['code'] = 200;
    $response['message'] = 'Etiqueta eliminada';
    return $response;
  }

  }
?>