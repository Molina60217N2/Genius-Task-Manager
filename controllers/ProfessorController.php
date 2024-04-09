<?php
  // file: controllers/ProfessorController.php

  require_once('./models/Professor.php');
  
  class ProfessorController extends Controller {
    
    public function index() {  
      return view('professor/index',
       ['professors'=>Professor::all(),
        'title'=>'Professors List', 'login'=>Auth::check()]);
    }

    public function show($id) {
      // $book = DB::table('book')->find(3);
      // // echo($book[0]['title']);

      // // return;
      $prof = Professor::find($id);
      return view('professor/show',
        ['professor'=>$prof,
         'title'=>'Professor Detail',
        'show'=>true, 'create'=>false, 'edit'=>false]);
    }

    public function create() {
      $prof = ['name'=>'','degree'=>'',
               'email'=>'','phone'=>''];
    return view('professor/show',
      ['title'=>'Professor Create',
      'professor'=>$prof,'courses'=>false,
      'show'=>false,'create'=>true,'edit'=>false]);
  }

  public function store($smth = null) {
    $name = Input::get('name');
    $degree = Input::get('degree');
    $email = Input::get('email');
    $phone = Input::get('phone');
    $prof = ['name'=>$name,'degree'=>$degree,
             'email'=>$email,'phone'=>$phone];
    DB::table('professor')->insert($prof);
    return redirect('/professor');
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

  public function destroy($prof_id) {  
    DB::table('professor')->delete($prof_id);
    return redirect('/professor');
  }

  }
?>