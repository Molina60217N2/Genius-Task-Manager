<?php

require_once('./models/Author.php');
require_once('./models/BookAuthor.php');
require_once('./models/Book.php');

class AuthorController extends Controller {
    
    public function index(){
        $authors = Author::all(); 
        return view('author/index', 
        ['authors' => $authors,
        'title' => 'Página principal de Autores' ]
        );
    }

    public function show($id){
            $author = Author::find($id);
            $books = Book::where('author_id', $id)->get();
            // $books = [];
            // for($i = 0; $i < sizeof($relBook); $i++){
            //     $book = Book::find($relBook[$i]['book_id']);
            //     $books[$i]['title'] = $book[0]['title'];
            //     $books[$i]['book_id'] = $book[0]['id'];
            // }
        return view('author/show',
          ['author'=>$author,
          'books' => $books,
          'title'=>'Datos del Autor',
          'show'=>true, 'create'=>false, 'edit'=>false]);
    }

    public function create(){
        $author = ['author'=>'','nationality'=>'','birth_year'=>'','fields'=>''];
        return view(
            'author/show',
            ['title'=>'Creación de Autor',
            'author'=>$author, 
            'show'=>false,'create'=>true,'edit'=>false]
        );
    }
    public function store($smth = null) {
        $author = Input::get('author');
        $nationality = Input::get('nationality');
        $birth_year = Input::get('birth_year');
        $fields = Input::get('fields');
        $author = ['author'=>$author,'nationality'=>$nationality,
                 'birth_year'=>$birth_year,'fields'=>$fields];
        DB::table('author')->insert($author);
        return redirect('/author');
      }

    public function edit($author_id){
        $author = DB::table('author')->find($author_id);
        return view(
            'author/show',
            ['author'=>$author,
            'title'=>'Edición de Autor',
            'show'=>false,'create'=>false,'edit'=>true]
        );
    }

    public function update($_,$author_id=null){
        return;
    }

    public function destroy($author_id) {
        DB::table('author')->delete($author_id);
        return redirect('/author');
    }
}