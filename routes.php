<?php

require_once('./models/Professor.php');
require_once('./controllers/ProfessorController.php');
require_once('./controllers/TeamController.php');
require_once('./controllers/TagController.php');
require_once('./controllers/TaskController.php');


//Para el API mejor hace todo por get, total no está evaluando seguridad xd
Route::get('/', 'LoginController@showLoginForm');

//TEAM ROUTES
// Route::resource('team','TeamController');
Route::get('teams/(:number)', 'TeamController@teamsperuser');
Route::get('team/(:number)/(:number)', 'TeamController@showTeam');
Route::post('/team/delete', 'TeamController@deleteteam');
//Esta funcion retorna a todos los usuarios. Debería hacer un controlador de usuarios? sí, pero no hay tiempo xd
Route::get('/team/users','TeamController@getusers');
//Esta funcion es la que agrega el usuario como tal
Route::post('/team/store/user', 'TeamController@storeuser');
Route::post('/team/update', 'TeamController@updateTeam');
Route::post('/team/create', 'TeamController@addTeam');
/**Formato del json: 
 * {"name":"nuevo team desde postman","description":"nuevo teampostman","useradmin":"4"}
 */
//TAG ROUTES
// Route::get('/team/(:number)/tag','TagController@index');
Route::resource('tag','TagController');
Route::get('/team/(:number)/tag','TagController@tagsperteam');
Route::post('/team/tag/create','TagController@addTag');
Route::get('/tag/(:number)/delete','TagController@destroy');

//TASK ROUTES
Route::resource('task', 'TaskController');
Route::get('/team/(:number)/task','TaskController@tasksperteam');
//Como antes, esta retorna los usuarios y las etiquetas
Route::get('/team/(:number)/task/create','TaskController@createTask');
//Esta almacena las tareas
Route::post('/team/store/task', 'TaskController@addTask');
Route::post('/task/delete','TaskController@destroy');
Route::post('/task/update', 'TaskController@updateTask');
Route::get('/tasks/user/(:number)', 'TaskController@tasksperuser');
Route::post('/task/filter','TaskController@filter');

      // Authentication Routes  
//     Route::get('login', 
//     'LoginController@showLoginForm');
    Route::get('loginFails', 
    'LoginController@LoginFails');           
    Route::post('/login', 
            'LoginController@login');  
    Route::get('logout', 'LoginController@logout');  

    // Registration Routes  
    Route::get('register', 
    'RegisterController@showRegistrationForm');  
    Route::post('/register', 
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
