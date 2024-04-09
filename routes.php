<?php

require_once('./models/Professor.php');
require_once('./controllers/ProfessorController.php');

require_once('./controllers/BookController.php');
require_once('./controllers/AuthorController.php');
require_once('./controllers/PublisherController.php');
    
    //BOOK'S ROUTES  
    Route::resource('/book', 'BookController');    
    Route::get('/','BookController@index');
    Route::get('/book/(:number)/delete','BookController@destroy');
    // Route::get('/books', 'BookController@index');
    // Route::get('/book/(:number)','BookController@show');
    
    //AUTHOR'S ROUTES
    Route::resource('/author', 'AuthorController');
    Route::get('/author/(:number)/delete','AuthorController@destroy');

    //PUBLISHER'S ROUTES
    Route::resource('/publisher','PublisherController');
    Route::get('/publisher/(:number)/delete','PublisherController@destroy');

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
    Route::resource('professor', 'ProfessorController');
    Route::get('/professor/(:number)/delete','ProfessorController@destroy');
    // Route::get('/professor/(:number)/edit','ProfessorController@edit');
    // Route::get('/professor/(:number)','ProfessorController@show');  
    Route::dispatch();
?>
