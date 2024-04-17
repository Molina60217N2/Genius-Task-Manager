<?php

require_once('./models/Professor.php');
require_once('./controllers/ProfessorController.php');
require_once('./controllers/TeamController.php');
require_once('./controllers/TagController.php');
require_once('./controllers/TaskController.php');

Route::get('/', 'LoginController@showLoginForm');

//TEAM ROUTES
Route::resource('team','TeamController');
Route::get('/team/(:number)/delete', 'TeamController@destroy');
Route::get('/team/(:number)/add/user','TeamController@adduser');
Route::post('/team/store/user', 'TeamController@storeuser');

//TAG ROUTES
// Route::get('/team/(:number)/tag','TagController@index');
Route::resource('tag','TagController');
Route::get('/team/(:number)/tag','TagController@tagsperteam');
Route::get('/team/(:number)/tag/create','TagController@createTag');
Route::get('/tag/(:number)/delete','TagController@destroy');

//TASK ROUTES
Route::resource('task', 'TaskController');
Route::get('/team/(:number)/task','TaskController@tasksperteam');
Route::get('/team/(:number)/task/create','TaskController@createTask');
Route::get('/task/(:number)/delete','TaskController@destroy');
Route::get('/tasks/user', 'TaskController@tasksperuser');
Route::post('/task/filter','TaskController@filter');

      // Authentication Routes  
    Route::get('login', 
    'LoginController@showLoginForm');
    Route::get('loginFails', 
    'LoginController@LoginFails');           
    Route::post('login', 
            'LoginController@login');  
    Route::get('logout', 'LoginController@logout');  

    // Registration Routes  
    Route::get('register', 
    'RegisterController@showRegistrationForm');  
    Route::post('register', 
            'RegisterController@register');


    //TESTING ROUTES

    //Esta ruta se encarga de rutear automaticamente el CRUD
    //esta ruta nos puede ayudar si queremos pasar varios parámetros, a una funcion
    Route::get('team/(:number)/(:number)/si', 'TeamController@test');
    Route::get('/', 'LoginController@showLoginForm');
    Route::resource('professor', 'ProfessorController');
    Route::get('/professor/(:number)/delete','ProfessorController@destroy');
    // Route::get('/professor/(:number)/edit','ProfessorController@edit');
    // Route::get('/professor/(:number)','ProfessorController@show');  
    Route::dispatch();
?>
