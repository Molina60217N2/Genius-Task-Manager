<?php

require_once('./models/Professor.php');
require_once('./controllers/ProfessorController.php');
require_once('./controllers/TeamController.php');
require_once('./controllers/TagController.php');
require_once('./controllers/TaskController.php');


//Para el API mejor hace todo por get, total no está evaluando seguridad xd
Route::get('/api', 'LoginController@showLoginForm');

//TEAM ROUTES
// Route::resource('team','TeamController');
Route::get('/api/teams/(:number)', 'TeamController@teamsperuser');
Route::get('/api/team/(:number)/(:number)', 'TeamController@showTeam');
Route::post('/api/team/delete', 'TeamController@deleteteam');
//Esta funcion retorna a todos los usuarios. Debería hacer un controlador de usuarios? sí, pero no hay tiempo xd
Route::get('/api/team/users','TeamController@getusers');
//Esta funcion es la que agrega el usuario como tal
Route::post('/api/team/store/user', 'TeamController@storeuser');
Route::post('/api/team/update', 'TeamController@updateTeam');
Route::post('/api/team/create', 'TeamController@addTeam');
/**Formato del json: 
 * {"name":"nuevo team desde postman","description":"nuevo teampostman","useradmin":"4"}
 */
//TAG ROUTES
// Route::get('/team/(:number)/tag','TagController@index');
Route::resource('/apitag','TagController');
Route::get('/api/team/(:number)/tag','TagController@tagsperteam');
Route::post('/api/team/tag/create','TagController@addTag');
Route::get('/api/tag/(:number)/delete','TagController@destroy');

//TASK ROUTES
Route::resource('/apitask', 'TaskController');
Route::get('/api/team/(:number)/task','TaskController@tasksperteam');
//Como antes, esta retorna los usuarios y las etiquetas
Route::get('/api/team/(:number)/task/create','TaskController@createTask');
//Esta almacena las tareas
Route::post('/api/team/store/task', 'TaskController@addTask');
Route::post('/api/task/delete','TaskController@destroy');
Route::post('/api/task/update', 'TaskController@updateTask');
Route::get('/api/tasks/user/(:number)', 'TaskController@tasksperuser');
Route::post('/api/task/filter','TaskController@filter');

      // Authentication Routes  
//     Route::get('login', 
//     'LoginController@showLoginForm');
    Route::get('loginFails', 
    'LoginController@LoginFails');           
    Route::post('/api/login', 
            'LoginController@login');  
    Route::get('logout', 'LoginController@logout');  

    // Registration Routes  
    Route::get('register', 
    'RegisterController@showRegistrationForm');  
    Route::post('/api/register', 
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
