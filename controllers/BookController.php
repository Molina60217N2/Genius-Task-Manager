<?php 
require_once('./models/Book.php');
require_once('./models/Author.php');
require_once('./models/BookAuthor.php');
require_once('./models/BookPublisher.php');
require_once('./models/Publisher.php');

class BookController extends Controller {
    public function index() {  
      $books = Book::all();
      // echo($books[0]['publisher_id']);
      // return;

      for ($i = 0; $i < sizeof($books); $i++) {
        $author = Author::where('id', $books[$i]['author_id'])->first();
        $publisher = Publisher::where('id', $books[$i]['publisher_id'])->first();
        $books[$i]['author_name'] = $author[0]['author'];
        $books[$i]['publisher_name'] = $publisher[0]['publisher'];
      }
        return view('book/index',
         ['books'=>$books,
          'title'=>'Página principal de Libros',
          'user'=>Cookie::get('userId'),
          'login'=>Auth::check()]);
    }
      public function show($id) {
        $book = Book::find($id);
        $author = Author::where('id', $book[0]['author_id'])->first();
        $publisher = Publisher::where('id', $book[0]['publisher_id'])->first();
        $book[0]['author_name'] = $author[0]['author'];
        $book[0]['author_id'] = $author[0]['id'];
        $book[0]['publisher_name'] = $publisher[0]['publisher'];
        $book[0]['publisher_id'] = $publisher[0]['id'];
        return view('book/show',
          ['book'=>$book,
           'title'=>'Book Detail',
           'show'=>true, 'create'=>false, 'edit'=>false]);
      }

      public function create() {
        $authors = Author::all();
        $publishers = Publisher::all();
        $book = ['title'=>'','author'=>$authors,
                 'publisher'=>$publishers,'language'=>'',
                 'pages'=>'', 'copyright'=>'',
                 'edition'=>''];
                 //llamar a todos los autores y editoriales para hacer un dropdown con los nombres y los values serán las ids
                 //de esa forma evitamos campos inválidos
        // echo($authors[1]['author']);
        // return;
      return view('book/show',
        ['page_title'=>'Book Create',
        'book'=>$book,
        'publishers'=>$publishers,
        'authors'=>$authors,
        'show'=>false,'create'=>true,'edit'=>false]);
    }
    public function store($smth = null) {
      //Form fields
      $title = Input::get('title');
      $author_id = Input::get('author_id');
      $publisher_id = Input::get('publisher_id');
      $language = Input::get('language');
      $pages = Input::get('pages');
      $copyright = Input::get('copyright');
      $edition = Input::get('edition');
      $book = ['title'=>$title,
               'language'=>$language,'pages'=>$pages,
               'copyright'=>$copyright,'edition'=>$edition,
               'publisher_id'=>$publisher_id,'author_id'=>$author_id];
      DB::table('book')->insert($book);
    
      return redirect('/book');
    }
      //EL UPDATE QUEDA PENDIENTE
    public function edit($book_id) {
      $book = DB::table('book')->find($book_id);
      $authors = Author::all();
      $publishers = Publisher::all();
      return view('book/show',
        ['book'=>$book,
         'title'=>'Book Edit',
         'publishers'=>$publishers,
         'authors'=>$authors,
         'show'=>false,'create'=>false,'edit'=>true]);
    }
    public function update($_,$book_id = null) {
      $title = Input::get('title');
      $author_id = Input::get('author_id');
      $publisher_id = Input::get('publisher_id');
      $language = Input::get('language');
      $pages = Input::get('pages');
      $copyright = Input::get('copyright');
      $edition = Input::get('edition');
      $book = ['title'=>$title,
               'language'=>$language,'pages'=>$pages,
               'copyright'=>$copyright,'edition'=>$edition,
               'publisher_id'=>$publisher_id,'author_id'=>$author_id];
      DB::table('book')->update($book_id,$book);
      return redirect('/book');
    }

    public function destroy($book_id) {  
      DB::table('book')->delete($book_id);
      return redirect('/book');
    }

  
}